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

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, [
            'No',
            'ID User',
            'Nama',
            'Nomor Telepon',
            'Progress',
            'Screenshot Diterima',
            'Mulai Daftar',
            'Selesai Daftar',
            'Dikirim ke Admin',
            'Aktif Terakhir',
        ]);

        foreach ($users as $index => $user) {
            fputcsv($handle, [
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
            ]);
        }

        rewind($handle);
        $csv = "\xEF\xBB\xBF" . stream_get_contents($handle);
        fclose($handle);

        return $this->response
            ->download('datasheet-anggota-bergabung-' . date('Y-m-d') . '.csv', $csv)
            ->setContentType('text/csv; charset=UTF-8');
    }
}

/* End of file JoinedUsers.php */
