<?php

namespace App\Controllers;

use App\Models\UserProgressModel;
use ZipArchive;

class JoinedUsers extends BaseController
{
    public function index()
    {
        $model = new UserProgressModel();

        return view('joined_users', [
            'total' => $model->countJoinedUsers(),
        ]);
    }

    public function data()
    {
        $request = $this->request;
        $model = new UserProgressModel();
        $columns = ['id', 'user_name', 'phone_number', 'current_step', 'screenshots_sent', 'started_at', 'completed_at', 'admin_sent_at', 'last_active'];
        $orderIndex = (int) ($request->getGet('order')[0]['column'] ?? 6);
        $orderColumn = $columns[$orderIndex] ?? 'completed_at';
        $orderDir = strtolower((string) ($request->getGet('order')[0]['dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
        $start = max(0, (int) ($request->getGet('start') ?? 0));
        $requestedLength = (int) ($request->getGet('length') ?? 10);
        // Client-side DataTable meminta seluruh dataset satu kali, lalu
        // menangani search, sorting, dan pagination di browser.
        $length = $requestedLength <= 0 ? 5000 : min(5000, max(10, $requestedLength));
        $search = trim((string) ($request->getGet('search')['value'] ?? ''));

        return $this->response->setJSON([
            'draw' => (int) ($request->getGet('draw') ?? 0),
            'recordsTotal' => $model->countJoinedUsers(),
            'recordsFiltered' => $model->countJoinedUsers($search),
            'data' => $model->getJoinedUsersPage($start, $length, $search, $orderColumn, $orderDir),
        ]);
    }

    public function download()
    {
        if (! class_exists(ZipArchive::class)) {
            return $this->response->setStatusCode(500)->setBody('PHP extension ext-zip wajib diaktifkan untuk export XLSX.');
        }

        $model = new UserProgressModel();
        $users = $model->getJoinedUsers();
        $headers = ['No', 'ID Telegram', 'Nama', 'Nomor Telepon', 'Progress', 'Screenshot', 'Mulai Daftar', 'Selesai Daftar', 'Dikirim ke Admin', 'Aktif Terakhir'];
        $rows = [$headers];

        foreach ($users as $index => $user) {
            $rows[] = [
                $index + 1,
                $user['user_id'] ?? '',
                $user['user_name'] ?? '',
                $user['phone_number'] ?? '',
                (int) ($user['current_step'] ?? 0),
                (int) ($user['screenshots_sent'] ?? 0),
                $user['started_at'] ?? '',
                $user['completed_at'] ?? '',
                $user['admin_sent_at'] ?? '',
                $user['last_active'] ?? '',
            ];
        }

        $file = tempnam(sys_get_temp_dir(), 'valetax_xlsx_');
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->relsXml());
        $zip->addFromString('docProps/core.xml', $this->coreXml());
        $zip->addFromString('docProps/app.xml', $this->appXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
        $zip->addFromString('xl/styles.xml', $this->stylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->sheetXml($rows));
        $zip->close();

        $xlsx = file_get_contents($file);
        unlink($file);

        return $this->response
            ->download('datasheet-anggota-bergabung-' . date('Y-m-d') . '.xlsx', $xlsx)
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private function sheetXml(array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetViews><sheetView workbookViewId="0" showGridLines="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews><cols>';
        foreach ([18, 18, 28, 20, 12, 14, 22, 22, 22, 22] as $i => $width) {
            $n = $i + 1;
            $xml .= '<col min="' . $n . '" max="' . $n . '" width="' . $width . '" customWidth="1"/>';
        }
        $xml .= '</cols><sheetData>';
        foreach ($rows as $rowIndex => $row) {
            $number = $rowIndex + 1;
            $xml .= '<row r="' . $number . '">';
            foreach ($row as $columnIndex => $value) {
                $ref = $this->columnName($columnIndex + 1) . $number;
                $value = $this->xml((string) ($value ?? ''));
                $style = $rowIndex === 0 ? ' s="1"' : '';
                if ($rowIndex > 0 && is_int($row[$columnIndex])) {
                    $xml .= '<c r="' . $ref . '"' . $style . ' t="n"><v>' . $value . '</v></c>';
                } else {
                    $xml .= '<c r="' . $ref . '"' . $style . ' t="inlineStr"><is><t xml:space="preserve">' . $value . '</t></is></c>';
                }
            }
            $xml .= '</row>';
        }
        return $xml . '</sheetData><autoFilter ref="A1:J' . count($rows) . '"/><mergeCells count="0"/></worksheet>';
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $name = chr(65 + $remainder) . $name;
            $number = intdiv($number - 1, 26);
        }
        return $name;
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/><Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/><Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/></Types>';
    }

    private function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/><Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/></Relationships>';
    }

    private function coreXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/"><dc:creator>Valetax</dc:creator><dc:title>Datasheet Anggota Bergabung</dc:title><dcterms:created xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="dcterms:W3CDTF">' . date('c') . '</dcterms:created></cp:coreProperties>';
    }

    private function appXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"><Application>Valetax</Application><DocSecurity>0</DocSecurity><ScaleCrop>false</ScaleCrop></Properties>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Anggota Bergabung" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF0D6EFD"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="2"><border/><border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border></borders><cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf></cellXfs></styleSheet>';
    }
}
