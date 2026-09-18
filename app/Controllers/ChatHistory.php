<?php

namespace App\Controllers;

use App\Models\ChatLogModel;

class ChatHistory extends BaseController
{
    protected $chatModel;

    public function __construct()
    {
        $this->chatModel = new ChatLogModel();
    }

    // Fungsi bantuan untuk cek hak akses
    private function isAdmin()
    {
        return (session()->get('role') === 'admin' || session()->get('username') === 'admin');
    }

    /**
     * Menampilkan Halaman Utama Riwayat Chat
     */
    public function index()
    {
        // Proteksi Halaman
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak! Halaman ini khusus Admin.');
        }

        $data = [
            'users' => $this->chatModel->getUniqueUsers()
        ];

        return view('riwayat_chat', $data);
    }

    /**
     * Endpoint AJAX untuk mengambil detail chat
     */
    public function getDetailChat($phone)
    {
        // Proteksi Akses via AJAX
        if (!$this->isAdmin()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $chats = $this->chatModel->getDetailChat($phone);

        return $this->response->setJSON($chats);
    }
}
