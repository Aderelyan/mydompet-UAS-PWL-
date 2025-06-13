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
        
        if ($transactionModel->addTransaction($data)) {
            return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil ditambahkan!');
        } else {
            return redirect()->to('/transaksi')->with('error', 'Gagal menambahkan transaksi. Kategori tidak valid.');
        }
    }

    // Tambahkan method ini di dalam kelas Transaksi

public function delete($id = null)
{
    $transactionModel = new \App\Models\TransactionModel();
    $userId = session()->get('user_id');

    // Cari transaksi berdasarkan ID dan pastikan itu milik user yang login
    $transaction = $transactionModel->where(['id' => $id, 'user_id' => $userId])->first();

    if ($transaction) {
        // Jika ditemukan, hapus. Trigger di database akan otomatis mengupdate saldo dompet.
        $transactionModel->delete($id);
        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Jika tidak ditemukan atau bukan milik user, beri pesan error
    return redirect()->to('/transaksi')->with('error', 'Transaksi tidak ditemukan.');
}
}