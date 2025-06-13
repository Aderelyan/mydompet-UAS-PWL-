<?php

namespace App\Controllers;

use App\Models\WalletModel;

class Wallet extends BaseController
{
    // Menampilkan halaman utama wallet
    public function index()
    {
        $walletModel = new WalletModel();
        $userId = session()->get('user_id');

        $data = [
            'wallets' => $walletModel->where('user_id', $userId)->findAll(),
        ];

        return view('wallets/index', $data);
    }

    // Memproses pembuatan wallet baru
    public function create()
    {
        $walletModel = new WalletModel();
        $rules = [
            'nama_dompet' => 'required|min_length[3]',
            'saldo'       => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/wallets')->withInput()->with('errors', $this->validator->getErrors());
        }

        $walletModel->save([
            'user_id'     => session()->get('user_id'),
            'nama_dompet' => $this->request->getPost('nama_dompet'),
            'saldo'       => $this->request->getPost('saldo'),
        ]);

        return redirect()->to('/wallets')->with('success', 'Dompet baru berhasil dibuat.');
    }

    // Memproses transfer saldo
    public function transfer()
    {
        // Validasi
        $rules = [
            'wallet_sumber_id' => 'required|numeric|differs[wallet_tujuan_id]',
            'wallet_tujuan_id' => 'required|numeric',
            'jumlah'           => 'required|numeric|greater_than[0]'
        ];
        $errors = [
            'wallet_sumber_id' => ['differs' => 'Dompet sumber dan tujuan tidak boleh sama.']
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->to('/wallets')->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Memanggil Stored Procedure
        $db = \Config\Database::connect();
        $sql = "CALL sp_transfer_saldo(?, ?, ?, ?)";
        
        try {
            $db->query($sql, [
                session()->get('user_id'),
                $this->request->getPost('wallet_sumber_id'),
                $this->request->getPost('wallet_tujuan_id'),
                $this->request->getPost('jumlah')
            ]);
            return redirect()->to('/wallets')->with('success', 'Transfer saldo berhasil.');
        } catch (\Throwable $th) {
            // Menangkap error dari stored procedure (misal: saldo tidak cukup)
            return redirect()->to('/wallets')->with('error', $th->getMessage());
        }
    }

    // Menghapus wallet
    public function delete($id = null)
    {
        $walletModel = new WalletModel();
        $userId = session()->get('user_id');
        $wallet = $walletModel->where(['id' => $id, 'user_id' => $userId])->first();

        // Pastikan wallet ada dan milik user
        if ($wallet) {
            // Praktik yang baik: hanya izinkan hapus dompet yang saldonya 0
            if ($wallet['saldo'] > 0) {
                return redirect()->to('/wallets')->with('error', 'Gagal! Hanya dompet dengan saldo Rp 0 yang bisa dihapus.');
            }
            try {
                $walletModel->delete($id);
                return redirect()->to('/wallets')->with('success', 'Dompet berhasil dihapus.');
            } catch (\Throwable $th) {
                return redirect()->to('/wallets')->with('error', 'Gagal menghapus dompet. Pastikan tidak ada riwayat transaksi pada dompet ini.');
            }
        }
        return redirect()->to('/wallets')->with('error', 'Dompet tidak ditemukan.');
    }
}