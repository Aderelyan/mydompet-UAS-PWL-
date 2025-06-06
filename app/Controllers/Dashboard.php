<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return view('dashboardGuest'); // Dashboard kosong untuk guest (belum login)
        } else {
            return view('dashboardUser'); // Dashboard dengan fitur penuh setelah login
        }

        
        $bulan = $this->request->getGet('bulan') ?: date('m'); // Default ke bulan sekarang
        $db = \Config\Database::connect();

        // Query pemasukan dan pengeluaran berdasarkan bulan
        $pemasukan = $db->table('transaksi')->where('jenis', 'pemasukan')->where('MONTH(tanggal)', $bulan)->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $pengeluaran = $db->table('transaksi')->where('jenis', 'pengeluaran')->where('MONTH(tanggal)', $bulan)->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;

        $data = [
            'pemasukan'  => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo'       => $pemasukan - $pengeluaran
        ];

        return view('dashboard', $data);
    }
}
