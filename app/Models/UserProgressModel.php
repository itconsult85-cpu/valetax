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
        $builder = $this->joinedBuilder();

        if ($this->hasAdminDeliveryColumns()) {
            $builder->where('admin_sent_at IS NOT NULL', null, false);
        }

        return $builder
            ->orderBy('completed_at', 'DESC')
            ->orderBy('last_active', 'DESC')
            ->findAll();
    }

    public function getJoinedUsersPage(int $start, int $length, string $search = '', string $order = 'completed_at', string $direction = 'desc'): array
    {
        $allowed = ['id', 'user_name', 'phone_number', 'current_step', 'screenshots_sent', 'started_at', 'completed_at', 'admin_sent_at', 'last_active'];
        $order = in_array($order, $allowed, true) ? $order : 'completed_at';
        $direction = $direction === 'asc' ? 'asc' : 'desc';
        $builder = $this->joinedBuilder();
        $this->applySearch($builder, $search);
        return $builder->orderBy($order, $direction)->limit($length, $start)->get()->getResultArray();
    }

    public function countJoinedUsers(string $search = ''): int
    {
        $builder = $this->joinedBuilder();
        $this->applySearch($builder, $search);
        return $builder->countAllResults();
    }

    private function joinedBuilder()
    {
        $builder = $this->db->table($this->table)
            ->where('completed_at IS NOT NULL', null, false)
            ->where('screenshots_sent >=', 1);
        if ($this->hasAdminDeliveryColumns()) {
            $builder->where('admin_sent_at IS NOT NULL', null, false);
        }
        return $builder;
    }

    private function applySearch($builder, string $search): void
    {
        if ($search === '') return;
        $builder->groupStart()
            ->like('user_name', $search)
            ->orLike('user_id', $search)
            ->orLike('phone_number', $search)
            ->groupEnd();
    }

    /*
     * Kept for existing callers; the query now uses the shared builder.
     */
    public function countJoinedUsersLegacy(): int
    {
        $builder = $this->joinedBuilder();
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
