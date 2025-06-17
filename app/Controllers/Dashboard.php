<?php

namespace App\Controllers;

// Kita butuh model untuk mengambil data
use App\Models\UserModel; 
use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    // Di dalam file app/Controllers/Dashboard.php

    public function index()
    {
        if (!session()->has('user_id')) {
            return view('dashboardGuest');
        }
    
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();
        $transactionModel = new \App\Models\TransactionModel();
    
        // ===================================================================
        // [PERUBAHAN] 1. LOGIKA UNTUK FILTER KARTU RINGKASAN
        // ===================================================================
        $filter_kartu = [
            'tanggal_awal'  => $this->request->getGet('kartu_tanggal_awal'),
            'tanggal_akhir' => $this->request->getGet('kartu_tanggal_akhir'),
        ];
    
        // Default untuk filter kartu adalah bulan ini
        $tglAwalKartu = !empty($filter_kartu['tanggal_awal']) ? $filter_kartu['tanggal_awal'] : date('Y-m-01');
        $tglAkhirKartu = !empty($filter_kartu['tanggal_akhir']) ? $filter_kartu['tanggal_akhir'] : date('Y-m-t');
    
        // Query Pemasukan berdasarkan filter kartu
        $pemasukan = $db->table('transactions')
                        ->where('user_id', $userId)
                        ->where('tipe_transaksi', 'pemasukan')
                        ->where('tanggal_transaksi >=', $tglAwalKartu)
                        ->where('tanggal_transaksi <=', $tglAkhirKartu)
                        ->selectSum('jumlah', 'total')->get()->getRow()->total ?? 0;
    
        // Query Pengeluaran berdasarkan filter kartu
        $pengeluaran = $db->table('transactions')
                         ->where('user_id', $userId)
                         ->where('tipe_transaksi', 'pengeluaran')
                         ->where('tanggal_transaksi >=', $tglAwalKartu)
                         ->where('tanggal_transaksi <=', $tglAkhirKartu)
                         ->selectSum('jumlah', 'total')->get()->getRow()->total ?? 0;
    
        // Total Saldo Dompet (ini tetap keseluruhan, tidak terpengaruh filter)
        $totalSaldo = $db->table('wallets')
                         ->where('user_id', $userId)
                         ->selectSum('saldo', 'total')->get()->getRow()->total ?? 0;
    
        $summary = [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'total_saldo' => $totalSaldo,
        ];
    
    
        // ===================================================================
        // [PERUBAHAN] 2. LOGIKA UNTUK FILTER TABEL RIWAYAT
        // ===================================================================
        $filter_tabel = [
            'tanggal_awal'  => $this->request->getGet('tabel_tanggal_awal'),
            'tanggal_akhir' => $this->request->getGet('tabel_tanggal_akhir'),
        ];
    
        // Default untuk filter tabel adalah 30 hari terakhir
        $tglAwalTabel = !empty($filter_tabel['tanggal_awal']) ? $filter_tabel['tanggal_awal'] : date('Y-m-d', strtotime('-30 days'));
        $tglAkhirTabel = !empty($filter_tabel['tanggal_akhir']) ? $filter_tabel['tanggal_akhir'] : date('Y-m-d');
    
        // Menggunakan method getTransactions dari model dengan filter tabel
        $transactions = $transactionModel->getTransactions([
            'user_id'       => $userId,
            'tanggal_awal'  => $tglAwalTabel,
            'tanggal_akhir' => $tglAkhirTabel,
        ]);
    
        // ===================================================================
        // 3. MENGIRIM SEMUA DATA KE VIEW
        // ===================================================================
        $data = [
            'summary'       => $summary,
            'transactions'  => $transactions,
            'filter_kartu'  => ['tanggal_awal' => $tglAwalKartu, 'tanggal_akhir' => $tglAkhirKartu],
            'filter_tabel'  => ['tanggal_awal' => $tglAwalTabel, 'tanggal_akhir' => $tglAkhirTabel],
        ];
    
        return view('dashboardUser', $data);
    }
}