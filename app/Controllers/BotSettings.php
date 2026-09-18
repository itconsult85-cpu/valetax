<?php

namespace App\Controllers;

use App\Models\BotGlobalModel;
use App\Models\BotFlowModel;

class BotSettings extends BaseController
{
    // Fungsi bantuan untuk cek hak akses
    private function isAdmin()
    {
        return (session()->get('role') === 'admin' || session()->get('username') === 'admin');
    }

    public function index()
    {
        // Proteksi Halaman: Jika bukan admin, tendang kembali ke Dashboard Utama
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak! Halaman ini khusus Admin.');
        }

        $globalModel = new BotGlobalModel();
        $flowModel = new BotFlowModel();

        // CEK & AUTO-CREATE KATA_SAPAAN JIKA HILANG
        $cekSapaan = $globalModel->where('key_name', 'KATA_SAPAAN')->first();
        if (!$cekSapaan) {
            $globalModel->insert([
                'key_name'  => 'KATA_SAPAAN',
                'key_value' => 'hei,halo,hai,p,ping,assalamualaikum,test,min,admin',
                'label'     => 'Kata Kunci Sapaan Awal (Pisahkan dengan koma)'
            ]);
        }

        $data = [
            'globals' => $globalModel->findAll(),
            'flows'   => $flowModel->orderBy('step_level', 'ASC')->findAll()
        ];

        return view('bot_settings', $data);
    }

    public function updateGlobals()
    {
        // Proteksi Aksi Simpan
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak! Anda tidak memiliki izin menyimpan pengaturan.');
        }

        $globalModel = new BotGlobalModel();
        $configs = $this->request->getPost('config'); // Mengambil array input

        if ($configs) {
            foreach ($configs as $key_name => $key_value) {
                // Update berdasarkan key_name (cth: LINK_MIFX)
                $globalModel->where('key_name', $key_name)
                    ->set(['key_value' => $key_value])
                    ->update();
            }
            session()->setFlashdata('pesan', 'Pengaturan Global berhasil diperbarui!');
        }

        return redirect()->to(base_url('BotSettings'));
    }

    public function saveFlow()
    {
        // Proteksi Aksi Simpan Flow
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $flowModel = new \App\Models\BotFlowModel();
        $id = $this->request->getPost('id');

        $data = [
            'step_level'       => $this->request->getPost('step_level'),
            'step_name'        => $this->request->getPost('step_name'),
            'trigger_keywords' => $this->request->getPost('trigger_keywords'),
            'reply_message'    => $this->request->getPost('reply_message'),
            'fallback_message' => $this->request->getPost('fallback_message'),
        ];

        // Proses Upload File Video
        $fileVideo = $this->request->getFile('fallback_video');
        if ($fileVideo && $fileVideo->isValid() && !$fileVideo->hasMoved()) {
            $newName = $fileVideo->getRandomName();
            $fileVideo->move(FCPATH . 'uploads', $newName);
            $data['fallback_video_url'] = base_url('uploads/' . $newName);
        }

        // Proses Upload File Gambar Contoh
        $fileImage = $this->request->getFile('fallback_image');
        if ($fileImage && $fileImage->isValid() && !$fileImage->hasMoved()) {
            $newName = $fileImage->getRandomName();
            $fileImage->move(FCPATH . 'uploads', $newName);
            $data['fallback_image_url'] = base_url('uploads/' . $newName);
        }

        if (!empty($id)) {
            $flowModel->update($id, $data);
            session()->setFlashdata('pesan', 'Tahap berhasil diperbarui!');
        } else {
            $flowModel->insert($data);
            session()->setFlashdata('pesan', 'Tahap baru berhasil ditambahkan!');
        }

        return redirect()->to(site_url('BotSettings'));
    }

    public function deleteFlow($id)
    {
        // Proteksi Aksi Hapus Flow
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $flowModel = new BotFlowModel();
        $flowModel->delete($id);

        session()->setFlashdata('pesan', 'Tahap Alur Pesan berhasil dihapus!');
        return redirect()->to(base_url('BotSettings'));
    }

    public function hapusMedia($id, $type)
    {
        // Proteksi Aksi Hapus Media
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $flowModel = new \App\Models\BotFlowModel();
        $flow = $flowModel->find($id);

        if (!$flow) return redirect()->back()->with('pesan', 'Data tidak ditemukan!');

        $field = ($type === 'video') ? 'fallback_video_url' : 'fallback_image_url';
        $filePath = str_replace(base_url(), FCPATH, $flow[$field]);

        // Hapus file fisik jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Kosongkan database
        $flowModel->update($id, [$field => null]);

        session()->setFlashdata('pesan', 'Media berhasil dihapus!');
        return redirect()->to(site_url('BotSettings'));
    }
}
