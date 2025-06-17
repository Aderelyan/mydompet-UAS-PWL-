<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\WalletModel;
use App\Models\CategoryModel;

class Transaksi extends BaseController
{
    public function index()
    {
        $transactionModel = new TransactionModel();
        $walletModel = new WalletModel();
        $categoryModel = new CategoryModel();
        $userId = session()->get('user_id');

        // Mengambil data filter dari URL (GET request)
        $filter = [
            'tanggal_awal'  => $this->request->getGet('tanggal_awal'),
            'tanggal_akhir' => $this->request->getGet('tanggal_akhir'),
        ];

        // Jika tidak ada filter, set default 1 bulan terakhir
        if (empty($filter['tanggal_awal']) && empty($filter['tanggal_akhir'])) {
            $filter['tanggal_awal'] = date('Y-m-01'); // Tanggal 1 bulan ini
            $filter['tanggal_akhir'] = date('Y-m-t'); // Tanggal terakhir bulan ini
        }
        
        // Siapkan data untuk dikirim ke model
        $filtersForQuery = [
            'user_id'       => $userId,
            'tanggal_awal'  => $filter['tanggal_awal'],
            'tanggal_akhir' => $filter['tanggal_akhir'],
        ];

        $data = [
            'transactions' => $transactionModel->getTransactions($filtersForQuery),
            'wallets'      => $walletModel->where('user_id', $userId)->findAll(),
            'categories'   => $categoryModel->where('user_id', $userId)->findAll(),
            'filter'       => $filter, // Kirim nilai filter ke view
        ];

        // Ganti nama view dari 'form_tambah' menjadi 'index'
        return view('transaksi/index', $data);
    }

    // Method create tetap sama untuk memproses form
    public function create()
    {
        // ... (kode method create tidak berubah) ...
        $rules = [
            'wallet_id'         => 'required|numeric',
            'category_id'       => 'required|numeric',
            'jumlah'            => 'required|numeric',
            'tanggal_transaksi' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/transaksi')->withInput()->with('errors', $this->validator->getErrors());
        }

        $transactionModel = new TransactionModel();

        $data = [
            'user_id'           => session()->get('user_id'),
            'wallet_id'         => $this->request->getPost('wallet_id'),
            'category_id'       => $this->request->getPost('category_id'),
            'jumlah'            => $this->request->getPost('jumlah'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi')
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->to('/transaksi')->withInput()->with('errors', $this->validator->getErrors());
        }
    
        $transactionModel = new \App\Models\TransactionModel();
        $data = [
            'user_id'           => session()->get('user_id'),
            'wallet_id'         => $this->request->getPost('wallet_id'),
            'category_id'       => $this->request->getPost('category_id'),
            'jumlah'            => $this->request->getPost('jumlah'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi')
        ];
    
        // [PERBAIKAN] Gunakan try...catch untuk memanggil method model
        try {
            // Coba jalankan method yang memanggil stored procedure
            $transactionModel->addTransaction($data);
            return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil ditambahkan!');
    
        } catch (\Throwable $th) {
            // Jika ada 'SIGNAL' atau error dari database, tangkap pesannya di sini
            return redirect()->to('/transaksi')->withInput()->with('error', $th->getMessage());
        }
    }

    // Tambahkan method ini di dalam kelas Transaksi

// Versi baru yang cerdas dan aman
public function delete($id = null)
{
    $transactionModel = new \App\Models\TransactionModel();
    $db = \Config\Database::connect(); // Panggil koneksi database
    $userId = session()->get('user_id');

    // 1. Cari transaksi utama berdasarkan ID dan pastikan itu milik user yang login
    $transaction = $transactionModel->where(['id' => $id, 'user_id' => $userId])->first();

    if ($transaction) {
        // Ambil ID transaksi pasangannya jika ada
        $linked_id = $transaction['linked_transaction_id'];

        // 2. Gunakan Transaction di CodeIgniter untuk memastikan kedua delete berhasil
        $db->transStart();

        // Hapus transaksi utama
        $transactionModel->delete($id);

        // Jika ada transaksi pasangan, hapus juga
        if ($linked_id) {
            $transactionModel->delete($linked_id);
        }

        $db->transComplete();

        // 3. Cek apakah transaksi database berhasil
        if ($db->transStatus() === false) {
            // Jika gagal, kembalikan dengan pesan error
            return redirect()->to('/transaksi')->with('error', 'Terjadi kesalahan saat menghapus data.');
        } else {
            // Jika berhasil
            return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil dihapus.');
        }
    }

    // Jika transaksi awal tidak ditemukan, beri pesan error
    return redirect()->to('/transaksi')->with('error', 'Transaksi tidak ditemukan.');
}
}