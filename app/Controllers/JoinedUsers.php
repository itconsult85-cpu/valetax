<?php

namespace App\Controllers;

use App\Models\UserProgressModel;

class JoinedUsers extends BaseController
{
    public function index()
    {
        $model = new UserProgressModel();

        return view('joined_users', [
            'users' => $model->getJoinedUsers(),
            'total' => $model->countJoinedUsers(),
        ]);
    }

    public function download()
    {
        $model = new UserProgressModel();
        $users = $model->getJoinedUsers();

        $escape = static fn ($value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
        $rows = '';
        foreach ($users as $index => $user) {
            $rows .= '<tr>'
                . '<td>' . ($index + 1) . '</td>'
                . '<td style="mso-number-format:\\@">' . $escape($user['user_id'] ?? '') . '</td>'
                . '<td>' . $escape($user['user_name'] ?? '') . '</td>'
                . '<td style="mso-number-format:\\@">' . $escape($user['phone_number'] ?? '') . '</td>'
                . '<td>' . (int) ($user['current_step'] ?? 0) . '</td>'
                . '<td>' . (int) ($user['screenshots_sent'] ?? 0) . '</td>'
                . '<td>' . $escape($user['started_at'] ?? '') . '</td>'
                . '<td>' . $escape($user['completed_at'] ?? '') . '</td>'
                . '<td>' . $escape($user['admin_sent_at'] ?? '') . '</td>'
                . '<td>' . $escape($user['last_active'] ?? '') . '</td>'
                . '</tr>';
        }

        $excel = '<!DOCTYPE html><html><head><meta charset="UTF-8">'
            . '<style>body{font-family:Calibri,Arial,sans-serif}table{border-collapse:collapse}th,td{border:1px solid #b7c3d0;padding:7px 9px;white-space:nowrap}th{background:#0d6efd;color:#fff;font-weight:bold;text-align:center}caption{font-size:16px;font-weight:bold;text-align:left;padding:10px 0}</style>'
            . '</head><body><table><caption>Datasheet Anggota Bergabung</caption><thead><tr>'
            . '<th>No</th><th>ID Telegram</th><th>Nama</th><th>Nomor Telepon</th><th>Progress</th><th>Screenshot</th><th>Mulai Daftar</th><th>Selesai Daftar</th><th>Dikirim ke Admin</th><th>Aktif Terakhir</th>'
            . '</tr></thead><tbody>' . $rows . '</tbody></table></body></html>';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="datasheet-anggota-bergabung-' . date('Y-m-d') . '.xls"')
            ->setBody("\xEF\xBB\xBF" . $excel);
    }
}

/* End of file JoinedUsers.php */
