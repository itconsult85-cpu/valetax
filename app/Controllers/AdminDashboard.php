<?php

namespace App\Controllers;

use App\Models\BotGlobalModel;
use App\Models\BotFlowModel;
use App\Models\AdminModel;

class AdminDashboard extends BaseController
{
    public function index()
    {
        $globalModel = new BotGlobalModel();
        $flowModel = new BotFlowModel();

        $cekSapaan = $globalModel->where('key_name', 'KATA_SAPAAN')->first();
        if (!$cekSapaan) {
            $globalModel->insert([
                'key_name'  => 'KATA_SAPAAN',
                'key_value' => 'hei,halo,hai,p,ping,assalamualaikum,test,min,admin,test,permisi,punten,tanya',
                'label'     => 'Kata Kunci Sapaan Awal (Pisahkan dengan koma)'
            ]);
        }

        $data = [
            'globals' => $globalModel->findAll(),
            'flows'   => $flowModel->orderBy('step_level', 'ASC')->findAll()
        ];

        return view('dashboard_utama', $data);
    }

    public function tambahUser()
    {
        $isAdmin = (session()->get('role') === 'admin' || session()->get('username') === 'admin');

        if (!$isAdmin) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Akses ditolak! Hanya Admin yang dapat menambah user.');
        }

        $adminModel = new AdminModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        $adminModel->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $role
        ]);

        return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'User baru berhasil ditambahkan!');
    }

    // ==========================================
    // FITUR GANTI PASSWORD
    // ==========================================
    public function gantiPassword()
    {
        $username = session()->get('username');
        if (!$username) {
            return redirect()->to(site_url('auth'))->with('pesan', 'Sesi habis, silakan login kembali.');
        }

        $password_baru = $this->request->getPost('password_baru');
        $konfirmasi_password = $this->request->getPost('konfirmasi_password');

        if ($password_baru !== $konfirmasi_password) {
            return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Gagal: Konfirmasi password tidak cocok dengan password baru!');
        }

        $adminModel = new \App\Models\AdminModel();

        $adminModel->where('username', $username)->set([
            'password_hash' => password_hash($password_baru, PASSWORD_DEFAULT)
        ])->update();

        return redirect()->to(site_url('AdminDashboard'))->with('pesan', 'Password akun Anda berhasil diperbarui!');
    }

    public function getBotStatus()
    {
        $vps_url = getenv('BOT_STATUS_URL') ?: "http://202.10.34.128:3001/api/bot-status";

        $ch = curl_init($vps_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $payload = json_decode($response, true);
            if (is_array($payload)) {
                return $this->response->setJSON($payload);
            }
        }

        return $this->response->setJSON([
            'status' => 'Disconnected',
            'active' => false,
            'pm2_status' => 'unreachable',
            'process_name' => 'bot_tele_valetax',
            'qr' => null,
        ]);
    }

    public function botControl($action)
    {
        $vps_url = "http://202.10.34.128:3001/api/bot-control";
        $token   = "Bonichi#2026";

        $data = json_encode([
            'action' => $action,
            'token' => $token
        ]);

        $ch = curl_init($vps_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $responseData = json_decode($response, true);
            session()->setFlashdata('pesan', 'Perintah Diterima VPS: ' . ($responseData['message'] ?? 'Berhasil'));
        } else {
            session()->setFlashdata('pesan', 'Gagal ke VPS. Status Code: ' . $httpCode);
        }

        return redirect()->to(site_url('AdminDashboard'));
    }
}
