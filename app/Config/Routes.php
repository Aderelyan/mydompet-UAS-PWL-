<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/catatan', 'Catatan::index');
$routes->get('/diagram', 'Diagram::index');
$routes->get('/catatan', 'Catatan::index');
$routes->post('/catatan/simpan', 'Catatan::simpan');
$routes->get('/register', 'Auth::register');
$routes->post('/process-register', 'Auth::processRegister');
$routes->get('/login', 'Auth::login');
$routes->post('/process-login', 'Auth::processLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/ganti-password', 'User::gantiPassword');
$routes->post('/ganti-password/process', 'User::processGantiPassword');
$routes->post('user/upload_foto', 'UserController::upload_foto');
$routes->get('/hutang', 'Hutang::index');
$routes->post('/hutang/simpan', 'Hutang::simpan');
$routes->post('/hutang/hapus/(:num)', 'Hutang::hapus/$1');
$routes->get('login-demo', 'Auth::loginDemo');


