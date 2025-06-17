<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    // Kolom yang diizinkan untuk diisi melalui form atau API
    protected $allowedFields    = ['username', 'email', 'password_hash', 'foto'];

    // Menggunakan created_at dan updated_at secara otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at'; // Pastikan Anda punya kolom updated_at di tabel users



    
    public function getDashboardSummary(int $userId)
{
    $sql = "CALL sp_get_dashboard_summary(?)";
    $query = $this->db->query($sql, [$userId]);
    return $query->getRow(); // Mengambil satu baris hasil dari stored procedure
}

 // [TAMBAHAN 1] Daftarkan event 'afterInsert' untuk memanggil fungsi 'createDefaultData'
 protected $afterInsert = ['createDefaultData'];

 // [TAMBAHAN 2] Definisikan kategori dan dompet default
 protected array $defaultCategories = [
     ['nama_kategori' => 'Gaji', 'tipe_kategori' => 'pemasukan'],
     ['nama_kategori' => 'Hadiah', 'tipe_kategori' => 'pemasukan'],
     ['nama_kategori' => 'Lainnya (Pemasukan)', 'tipe_kategori' => 'pemasukan'],
     ['nama_kategori' => 'Transfer Masuk', 'tipe_kategori' => 'pemasukan'],
     ['nama_kategori' => 'Makan & Minum', 'tipe_kategori' => 'pengeluaran'],
     ['nama_kategori' => 'Transportasi', 'tipe_kategori' => 'pengeluaran'],
     ['nama_kategori' => 'Belanja', 'tipe_kategori' => 'pengeluaran'],
     ['nama_kategori' => 'Tagihan', 'tipe_kategori' => 'pengeluaran'],
     ['nama_kategori' => 'Hobi', 'tipe_kategori' => 'pengeluaran'],
     ['nama_kategori' => 'Lainnya (Pengeluaran)', 'tipe_kategori' => 'pengeluaran'],
     ['nama_kategori' => 'Transfer Keluar', 'tipe_kategori' => 'pengeluaran'],
 ];

 protected array $defaultWallets = [
     ['nama_dompet' => 'Dompet Utama', 'saldo' => 0],
     ['nama_dompet' => 'Rekening Bank', 'saldo' => 0],
 ];

 protected function createDefaultData(array $data): array
 {
     // Pastikan proses insert berhasil dan kita punya ID user baru
     if (isset($data['id'])) {
         $userId = $data['id'];

         // Inisialisasi model yang dibutuhkan
         $categoryModel = new \App\Models\CategoryModel();
         $walletModel = new \App\Models\WalletModel();

         // Loop dan masukkan kategori default
         foreach ($this->defaultCategories as $category) {
             $category['user_id'] = $userId;
             $categoryModel->insert($category);
         }

         // Loop dan masukkan dompet default
         foreach ($this->defaultWallets as $wallet) {
             $wallet['user_id'] = $userId;
             $walletModel->insert($wallet);
         }
     }
     
     return $data;
 }
}


