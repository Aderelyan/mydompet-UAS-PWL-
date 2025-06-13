<?php

namespace App\Controllers;

// Kita butuh model untuk mengambil data
use App\Models\UserModel; 
use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Jika belum login, tampilkan halaman marketing/landing page
        if (!session()->has('user_id')) {
            return view('dashboardGuest'); 
        }

        // Jika sudah login, ambil data dan tampilkan dashboard pengguna
        $userId = session()->get('user_id');
        $userModel = new UserModel();

        // Panggil method baru di UserModel untuk mengambil summary
        // yang memanggil stored procedure 'sp_get_dashboard_summary'
        $summary = $userModel->getDashboardSummary($userId); 

        // Ambil juga beberapa transaksi terakhir untuk ditampilkan
        $transactionModel = new TransactionModel();
        // [PERBAIKAN] Menggunakan method baru untuk mengambil transaksi hari ini
$today = date('Y-m-d');
$filters = [
    'user_id'       => $userId,
    'tanggal_awal'  => $today,
    'tanggal_akhir' => $today,
];
$transactions = $transactionModel->getTransactions($filters);

        $data = [
            'summary'      => $summary,
            'transactions' => $transactions
        ];

        return view('dashboardUser', $data); 
    }
}