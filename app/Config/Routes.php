<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Publik (Bisa diakses tanpa login)
//================================================
$routes->get('/', 'Dashboard::index'); // <-- PINDAHKAN KE SINI

$routes->get('/login', 'Auth::login');
$routes->post('/process-login', 'Auth::processLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/process-register', 'Auth::processRegister');
$routes->get('login-demo', 'Auth::loginDemo');


// Rute Terproteksi (Hanya bisa diakses setelah login)
//================================================
// Semua rute di dalam grup ini akan dijaga oleh filter 'auth'
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Rute '/dashboard' tetap di dalam karena ini khusus user yang login
    $routes->get('/dashboard', 'Dashboard::index'); 
    
    // ... (sisa rute terproteksi lainnya tetap di sini) ...
    $routes->get('transaksi', 'Transaksi::index');
    $routes->post('transaksi/create', 'Transaksi::create');
    $routes->delete('transaksi/delete/(:num)', 'Transaksi::delete/$1');

    $routes->get('/diagram', 'Diagram::index');
    $routes->get('/ganti-password', 'User::gantiPassword');
    $routes->post('/ganti-password', 'User::processGantiPassword');
    $routes->post('user/upload-foto', 'User::uploadFoto');
    $routes->get('/hutang', 'Hutang::index');
    $routes->post('/hutang', 'Hutang::simpan');
    $routes->delete('/hutang/hapus/(:num)', 'Hutang::hapus/$1');
    $routes->get('/logout', 'Auth::logout');

    // Rute untuk Manajemen Wallet
    $routes->get('/wallets', 'Wallet::index');
    $routes->post('/wallets/create', 'Wallet::create');
    $routes->post('/wallets/transfer', 'Wallet::transfer');
    $routes->delete('/wallets/delete/(:num)', 'Wallet::delete/$1');

    // delete
    $routes->get('/delete-account', 'Auth::deleteAccount');
    $routes->post('/process-delete-account', 'Auth::processDeleteAccount');

});