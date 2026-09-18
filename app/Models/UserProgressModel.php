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
     * Bot lama menandai pendaftaran selesai saat bukti screenshot diterima,
     * sehingga current_step historis tidak selalu bernilai 6. Sumber status
     * selesai yang konsisten adalah completed_at + minimal satu screenshot.
     */
    public function getJoinedUsers(): array
    {
        return $this->where('completed_at IS NOT NULL', null, false)
            ->where('screenshots_sent >=', 1)
            ->orderBy('completed_at', 'DESC')
            ->orderBy('last_active', 'DESC')
            ->findAll();
    }

    public function countJoinedUsers(): int
    {
        return $this->where('completed_at IS NOT NULL', null, false)
            ->where('screenshots_sent >=', 1)
            ->countAllResults();
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
