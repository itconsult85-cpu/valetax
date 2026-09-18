<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatLogModel extends Model
{
    protected $table            = 'chat_logs';
    protected $primaryKey       = 'id';

    // Sesuaikan allowedFields dengan struktur tabel Kakak
    protected $allowedFields    = ['phone_number', 'sender', 'message', 'created_at'];

    /**
     * Mengambil daftar kontak unik yang pernah chat
     * beserta nama dari tabel user_progress
     */
    public function getUniqueUsers()
    {
        $builder = $this->db->table($this->table . ' c');
        $builder->select('c.phone_number, MAX(c.id) as last_msg_id, u.user_name');
        $builder->join('user_progress u', 'c.phone_number = u.phone_number', 'left');
        $builder->groupBy('c.phone_number');
        $builder->orderBy('last_msg_id', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil riwayat detail chat berdasarkan nomor telepon
     */
    public function getDetailChat($phone)
    {
        return $this->where('phone_number', $phone)
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
