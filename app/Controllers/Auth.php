<?php

namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
{
    // [PERBAIKAN] 1. Aturan Validasi
    $rules = [
        'username' => 'required|min_length[3]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'password'     => 'required|min_length[8]',
        'pass_confirm' => 'required|matches[password]'
    ];

    if (!$this->validate($rules)) {
        // Jika validasi gagal, kembalikan ke form register dengan error
        return redirect()->to('/register')->withInput()->with('errors', $this->validator->getErrors());
    }

    // [PERBAIKAN] 2. Gunakan model untuk memanggil Stored Procedure
    $userModel = new UserModel();

    // Data disesuaikan dengan kolom tabel baru
    $data = [
        'username' => $this->request->getPost('username'),
        'email'        => $this->request->getPost('email'),
        'password_hash'=> password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
    ];

    // Kita bisa buat method baru di UserModel untuk memanggil procedure
    // atau langsung memanggilnya dari sini. Untuk simpelnya, kita panggil dari model.
    // Asumsi kita sudah buat method registerUser di UserModel
    // $userModel->registerUser($data);

    // Cara simpel tanpa procedure juga tidak apa-apa untuk register
    $userModel->save($data);

    return redirect()->to('/login')->with('success', 'Akun berhasil dibuat. Silakan login!');
}


    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
{
    $session = session();
    $userModel = new UserModel();

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $user = $userModel->where('email', $email)->first();

    // [PERBAIKAN] Sesuaikan nama kolom dengan database
    if ($user && password_verify($password, $user['password_hash'])) {
        $session->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['username'], // Gunakan username
            'user_email' => $user['email'],
            'user_foto'  => $user['foto'], // Simpan juga foto di session
            'logged_in'  => true
        ]);
        return redirect()->to('/dashboard');
    } else {
        return redirect()->to('/login')->with('error', 'Email atau password salah');
    }
}

public function loginDemo()
{
    $model = new \App\Models\UserModel();
    $guest = $model->where('username', 'guest')->first();

    if ($guest) {
        $session = session();
        $session->set([
            'user_id' => $guest['id'],
            'username' => $guest['username'],
            'role' => $guest['role'],
            'logged_in' => true
        ]);
        return redirect()->to('/hutang'); // arahkan ke dashboard demo
    } else {
        return redirect()->to('/login')->with('error', 'Akun demo tidak ditemukan');
    }
}


public function logout()
{
    session()->destroy();
    // [PERUBAHAN] Arahkan ke halaman utama ('/'), yang akan menampilkan dashboard guest
    return redirect()->to('/')->with('success', 'Anda telah berhasil logout.'); 
}

public function deleteAccount()
{
    return view('auth/delete_account');
}

/**
 * Memproses penghapusan akun setelah konfirmasi password.
 */
public function processDeleteAccount()
{
    $session = session();
    $userModel = new \App\Models\UserModel();
    
    // 1. Ambil ID pengguna dari session (agar aman)
    $userId = $session->get('user_id');
    $passwordKonfirmasi = $this->request->getPost('password');

    // 2. Validasi bahwa password diisi
    if (empty($passwordKonfirmasi)) {
        return redirect()->back()->with('error', 'Password harus diisi untuk konfirmasi.');
    }

    // 3. Ambil data pengguna untuk verifikasi password
    $user = $userModel->find($userId);

    // 4. Verifikasi password yang dimasukkan
    if ($user && password_verify($passwordKonfirmasi, $user['password_hash'])) {
        
        // 5. Jika password benar, HAPUS pengguna
        // Karena ON DELETE CASCADE, semua data terkait (transaksi, dompet, kategori) akan ikut terhapus
        $userModel->delete($userId);

        // 6. Hancurkan sesi dan arahkan ke halaman utama
        $session->destroy();
        return redirect()->to('/')->with('success', 'Akun Anda telah berhasil dihapus secara permanen.');

    } else {
        // 7. Jika password salah, kembalikan dengan pesan error
        return redirect()->back()->with('error', 'Password yang Anda masukkan salah.');
    }
}
}
