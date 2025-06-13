<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    
    // Kolom ini untuk referensi, tetapi penambahan data utama akan melalui stored procedure
    protected $allowedFields    = ['user_id', 'wallet_id', 'category_id', 'tipe_transaksi', 'jumlah', 'keterangan', 'tanggal_transaksi'];

    /**
     * Metode utama untuk menambahkan transaksi baru dengan memanggil stored procedure.
     * @param array $data Data transaksi
     * @return bool True jika berhasil, false jika gagal.
     */
    public function addTransaction(array $data): bool
    {
        // Susun parameter sesuai urutan di Stored Procedure `sp_add_transaction`
        $params = [
            $data['user_id'],
            $data['wallet_id'],
            $data['category_id'],
            $data['jumlah'],
            $data['keterangan'],
            $data['tanggal_transaksi']
        ];

        $sql = "CALL sp_add_transaction(?, ?, ?, ?, ?, ?)";

        // Eksekusi query dengan "query binding" untuk keamanan
        $this->db->query($sql, $params);

        // Cek apakah ada error dari database (misal: kategori tidak valid)
        if ($this->db->error()['code'] != 0) {
            return false;
        }

        return true;
    }

    // Tambahkan method ini di dalam kelas TransactionModel

/**
 * Mengambil data transaksi dengan filter tanggal dan join ke tabel lain.
 * @param array $filters Filter data, contoh: ['user_id' => 1, 'tanggal_awal' => '2025-01-01']
 * @return array Hasil transaksi
 */
public function getTransactions(array $filters = []): array
{
    $builder = $this->db->table($this->table);

    // Selalu join dengan kategori dan wallet untuk mendapatkan nama
    $builder->select('transactions.*, categories.nama_kategori, wallets.nama_dompet');
    $builder->join('categories', 'categories.id = transactions.category_id');
    $builder->join('wallets', 'wallets.id = transactions.wallet_id');

    // Filter wajib berdasarkan user yang login
    if (isset($filters['user_id'])) {
        $builder->where('transactions.user_id', $filters['user_id']);
    }

    // Filter berdasarkan rentang tanggal
    if (isset($filters['tanggal_awal']) && !empty($filters['tanggal_awal'])) {
        $builder->where('transactions.tanggal_transaksi >=', $filters['tanggal_awal']);
    }
    if (isset($filters['tanggal_akhir']) && !empty($filters['tanggal_akhir'])) {
        $builder->where('transactions.tanggal_transaksi <=', $filters['tanggal_akhir']);
    }
    
    // Urutkan berdasarkan tanggal terbaru
    $builder->orderBy('transactions.tanggal_transaksi', 'DESC');
    $builder->orderBy('transactions.id', 'DESC');

    return $builder->get()->getResultArray();
}
}