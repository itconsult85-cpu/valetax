<?php
namespace App\Models;
use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'bot_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['admin_number', 'link_mifx', 'link_valetax'];
}