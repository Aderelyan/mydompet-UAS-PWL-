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
        $userModel = new UserModel();
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $password
        ];
        $userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat. Silakan login!');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
{
    $session = session();
    $userModel = new \App\Models\UserModel();
    
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $user = $userModel->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
        $session->set([
            'user_id' => $user['id'],
            'user_name' => $user['username'],
            'user_email' => $user['email'],
            'logged_in' => true
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
        return redirect()->to('/dashboard');
    }
}
