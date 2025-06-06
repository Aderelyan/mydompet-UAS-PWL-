<?php

namespace App\Models;
use CodeIgniter\Model;

class CatatanModel extends Model
{
    protected $table = 'catatan_keuangan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['keterangan', 'jumlah', 'jenis', 'tanggal', 'users_id'];
}
