<?php

namespace App\Controllers;
use App\Models\UserModel;

class User extends BaseController
{
    public function gantiPassword()
    {
        return view('user/ganti_password');
    }

    public function processGantiPassword()
    {
        $session = session();
        $userModel = new UserModel();

        $userId = $session->get('user_id');
        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $passwordKonfirmasi = $this->request->getPost('password_konfirmasi');

        $user = $userModel->find($userId);

        if (!password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah');
        }

        if ($passwordBaru !== $passwordKonfirmasi) {
            return redirect()->back()->with('error', 'Password baru tidak cocok');
        }

        $userModel->update($userId, ['password' => password_hash($passwordBaru, PASSWORD_DEFAULT)]);

        return redirect()->to('/ganti-password')->with('success', 'Password berhasil diubah');
    }
}
