<?php

namespace App\Models;

use CodeIgniter\Model;

class DiagramModel extends Model
{
    /**
     * Mengambil total pengeluaran dikelompokkan per kategori untuk Pie Chart.
     */
    public function getExpenseByCategory(array $filters = [])
    {
        $builder = $this->db->table('transactions');
        $builder->select('categories.nama_kategori, SUM(transactions.jumlah) as total');
        $builder->join('categories', 'categories.id = transactions.category_id');

        // Filter wajib
        $builder->where('transactions.user_id', $filters['user_id']);
        $builder->where('transactions.tipe_transaksi', 'pengeluaran');

        // Filter tanggal jika ada
        if (!empty($filters['tanggal_awal'])) {
            $builder->where('transactions.tanggal_transaksi >=', $filters['tanggal_awal']);
        }
        if (!empty($filters['tanggal_akhir'])) {
            $builder->where('transactions.tanggal_transaksi <=', $filters['tanggal_akhir']);
        }

        $builder->groupBy('transactions.category_id');
        $builder->orderBy('total', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil tren transaksi harian untuk Line Chart.
     */
    public function getDailyTrend(array $filters = [])
    {
        $builder = $this->db->table('transactions');
        $builder->select("
            DATE_FORMAT(tanggal_transaksi, '%Y-%m-%d') as tanggal,
            SUM(CASE WHEN tipe_transaksi = 'pemasukan' THEN jumlah ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN tipe_transaksi = 'pengeluaran' THEN jumlah ELSE 0 END) as total_pengeluaran
        ");

        // Filter wajib
        $builder->where('user_id', $filters['user_id']);

        // Filter tanggal jika ada
        if (!empty($filters['tanggal_awal'])) {
            $builder->where('tanggal_transaksi >=', $filters['tanggal_awal']);
        }
        if (!empty($filters['tanggal_akhir'])) {
            $builder->where('tanggal_transaksi <=', $filters['tanggal_akhir']);
        }

        $builder->groupBy('tanggal');
        $builder->orderBy('tanggal', 'ASC');

        return $builder->get()->getResultArray();
    }
}