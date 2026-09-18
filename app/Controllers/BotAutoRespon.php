<?php

namespace App\Controllers;

class BotAutoRespon extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->cekDanBuatTabel();
    }

    // Fungsi bantuan untuk cek hak akses
    private function isAdmin()
    {
        return (session()->get('role') === 'admin' || session()->get('username') === 'admin');
    }

    private function cekDanBuatTabel()
    {
        if (!$this->db->tableExists('bot_faqs')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'keywords'      => ['type' => 'VARCHAR', 'constraint' => 255],
                'reply_message' => ['type' => 'TEXT'],
                'action_type'   => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'reply_only'], // Kolom baru
            ]);
            $forge->addKey('id', true);
            $forge->createTable('bot_faqs');
        } else {
            // Jika tabel sudah ada, pastikan kolom action_type ada
            $fields = $this->db->getFieldNames('bot_faqs');
            if (!in_array('action_type', $fields)) {
                $this->db->query("ALTER TABLE bot_faqs ADD action_type VARCHAR(50) DEFAULT 'reply_only' AFTER reply_message");
            }
        }
    }

    public function index()
    {
        // Proteksi Halaman Utama Auto Respon
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak! Halaman ini khusus Admin.');
        }

        $data['faqs'] = $this->db->table('bot_faqs')->orderBy('id', 'DESC')->get()->getResultArray();

        // AMBIL DAFTAR ACTION DINAMIS
        $data['action_types'] = $this->db->table('bot_action_types')->get()->getResultArray();

        $keys = ['TIMEOUT_DURATION', 'TIMEOUT_MESSAGE'];
        $globals = $this->db->table('bot_globals')->whereIn('key_name', $keys)->get()->getResultArray();

        $data['config'] = [];
        foreach ($globals as $row) {
            $data['config'][$row['key_name']] = $row['key_value'];
        }

        $data['config']['TIMEOUT_DURATION'] = $data['config']['TIMEOUT_DURATION'] ?? '5';
        $data['config']['TIMEOUT_MESSAGE']  = $data['config']['TIMEOUT_MESSAGE'] ?? "Halo Kak {NAMA_USER}...";

        return view('bot_autorespon', $data);
    }

    public function simpanCerdas()
    {
        // Proteksi Aksi Simpan Timer
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $keys = ['TIMEOUT_DURATION', 'TIMEOUT_MESSAGE']; // Hapus keyword bingung dari sini
        foreach ($keys as $key) {
            $value = $this->request->getPost($key);
            $exist = $this->db->table('bot_globals')->where('key_name', $key)->countAllResults();
            if ($exist > 0) {
                $this->db->table('bot_globals')->where('key_name', $key)->update(['key_value' => $value]);
            } else {
                $this->db->table('bot_globals')->insert(['key_name' => $key, 'key_value' => $value]);
            }
        }
        session()->setFlashdata('pesan', 'Pengaturan Timer berhasil diperbarui!');
        return redirect()->to(site_url('BotAutoRespon'));
    }

    // Fungsi Simpan dengan Action Type
    public function simpan()
    {
        // Proteksi Aksi Simpan FAQ
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $id = $this->request->getPost('id');
        $data = [
            'keywords'      => strtolower($this->request->getPost('keywords')),
            'reply_message' => $this->request->getPost('reply_message'),
            'action_type'   => $this->request->getPost('action_type') // Menangkap pilihan aksi dari Dashboard
        ];

        if (empty($id)) {
            $this->db->table('bot_faqs')->insert($data);
            session()->setFlashdata('pesan', 'Pencegat baru berhasil ditambahkan!');
        } else {
            $this->db->table('bot_faqs')->where('id', $id)->update($data);
            session()->setFlashdata('pesan', 'Auto-Respon berhasil diperbarui!');
        }
        return redirect()->to(site_url('BotAutoRespon'));
    }

    public function simpanAksi()
    {
        // Proteksi Aksi Simpan Tipe Aksi
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $data = [
            'action_code'  => strtolower(trim($this->request->getPost('action_code'))),
            'action_label' => $this->request->getPost('action_label')
        ];

        // Pastikan tidak ada duplikat kode
        $exist = $this->db->table('bot_action_types')->where('action_code', $data['action_code'])->countAllResults();

        if ($exist == 0) {
            $this->db->table('bot_action_types')->insert($data);
            session()->setFlashdata('pesan', 'Tindakan bot baru berhasil ditambahkan ke menu!');
        } else {
            session()->setFlashdata('pesan', 'Gagal: Kode aksi tersebut sudah ada.');
        }

        return redirect()->to(site_url('BotAutoRespon'));
    }

    public function hapus($id)
    {
        // Proteksi Aksi Hapus
        if (!$this->isAdmin()) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak!');
        }

        $this->db->table('bot_faqs')->where('id', $id)->delete();
        session()->setFlashdata('pesan', 'Auto-Respon berhasil dihapus!');
        return redirect()->to(site_url('BotAutoRespon'));
    }
}
