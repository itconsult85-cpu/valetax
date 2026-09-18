<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProgressModel extends Model
{
    private ?bool $hasAdminDeliveryColumns = null;

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
        'admin_notified_at',
        'admin_forwarded_at',
        'admin_sent_at',
        'admin_message_id',
        'admin_forward_message_id',
        'last_active',
    ];

    /**
     * Bot lama menandai pendaftaran selesai saat bukti screenshot diterima,
     * sehingga current_step historis tidak selalu bernilai 6. Sumber status
     * selesai yang terverifikasi adalah completed_at + screenshot + admin_sent_at.
     */
    public function getJoinedUsers(): array
    {
        $builder = $this->where('completed_at IS NOT NULL', null, false)
            ->where('screenshots_sent >=', 1);

        if ($this->hasAdminDeliveryColumns()) {
            $builder->where('admin_sent_at IS NOT NULL', null, false);
        }

        return $builder
            ->orderBy('completed_at', 'DESC')
            ->orderBy('last_active', 'DESC')
            ->findAll();
    }

    public function countJoinedUsers(): int
    {
        $builder = $this->where('completed_at IS NOT NULL', null, false)
            ->where('screenshots_sent >=', 1);

        if ($this->hasAdminDeliveryColumns()) {
            $builder->where('admin_sent_at IS NOT NULL', null, false);
        }

        return $builder->countAllResults();
    }

    private function hasAdminDeliveryColumns(): bool
    {
        if ($this->hasAdminDeliveryColumns !== null) {
            return $this->hasAdminDeliveryColumns;
        }

        try {
            $this->hasAdminDeliveryColumns = $this->db->fieldExists('admin_sent_at', $this->table);
        } catch (\Throwable) {
            $this->hasAdminDeliveryColumns = false;
        }

        return $this->hasAdminDeliveryColumns;
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
