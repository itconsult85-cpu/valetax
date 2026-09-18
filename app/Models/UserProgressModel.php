<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProgressModel extends Model
{
    protected $table      = 'user_progress';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'user_name',
        'phone_number',
        'current_step',
        'screenshots_sent',
        'started_at',
        'completed_at',
        'last_active',
    ];

    /**
     * Pendaftaran dianggap selesai saat bot mencapai langkah terakhir (6).
     * completed_at tetap diterima sebagai informasi historis, tetapi tidak
     * dipakai sendiri agar user yang belum mencapai langkah terakhir tidak
     * masuk ke daftar anggota.
     */
    public function getJoinedUsers(): array
    {
        return $this->where('current_step >=', 6)
            ->orderBy('completed_at', 'DESC')
            ->orderBy('last_active', 'DESC')
            ->findAll();
    }

    public function countJoinedUsers(): int
    {
        return $this->where('current_step >=', 6)->countAllResults();
    }

    /**
     * Method ini dapat dipanggil oleh proses pendaftaran ketika langkah
     * terakhir berhasil, tanpa menimpa data progres lain.
     */
    public function markRegistrationCompleted(string $userId): bool
    {
        return $this->where('user_id', $userId)
            ->where('current_step >=', 6)
            ->where('completed_at IS NULL', null, false)
            ->set('completed_at', date('Y-m-d H:i:s'))
            ->update();
    }
}

/* End of file UserProgressModel.php */
/* Location: app/Models/UserProgressModel.php */

// CodeIgniter\Model is intentionally imported above; keep the class isolated
// so it can be reused by the bot integration in a later iteration.
?>
