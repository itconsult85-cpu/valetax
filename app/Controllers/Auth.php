<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung arahkan ke Dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(site_url('AdminDashboard'));
        }
        return view('auth/login');
    }

    public function process()
    {
        // 1. RATE LIMITING (Anti Brute-Force)
        $throttler = \Config\Services::throttler();
        $ipAddress = $this->request->getIPAddress();

        // Batasi maksimal 5 kali percobaan per 15 menit (900 detik) untuk 1 IP
        if ($throttler->check($ipAddress, 5, 900) === false) {
            return redirect()->to(site_url('auth'))->with('error', 'Terlalu banyak percobaan. Silakan coba lagi dalam 15 menit.');
        }

        // 2. VALIDASI INPUT
        $rules = [
            'username' => 'required|min_length[4]|max_length[50]',
            'password' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('auth'))->with('error', 'Input tidak valid.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $adminModel = new AdminModel();
        $admin = $adminModel->where('username', $username)->first();

        // 3. VERIFIKASI PASSWORD (Bcrypt)
        if ($admin && password_verify($password, $admin['password_hash'])) {

            // 4. SESSION REGENERATION (Mencegah Session Hijacking)
            session()->regenerate();

            // Set data session
            $sessionData = [
                'admin_id'   => $admin['id'],
                'username'   => $admin['username'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);

            // Update waktu login terakhir
            $adminModel->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);

            return redirect()->to(site_url('AdminDashboard'));
        } else {
            return redirect()->to(site_url('auth'))->with('error', 'Username atau Password salah!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('auth'))->with('pesan', 'Berhasil logout.');
    }
}
