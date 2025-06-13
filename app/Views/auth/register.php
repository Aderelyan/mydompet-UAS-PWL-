<!DOCTYPE html>
<html lang="id">
<head>
    <title>Register - MyDompet</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, rgb(23, 79, 184), rgb(82, 131, 214));
            color: white;
            /* Menggunakan min-height agar bisa scroll jika konten lebih panjang */
            min-height: 100vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0; /* Memberi sedikit padding atas bawah */
        }
        .register-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            color: white;
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .btn-primary {
            background: rgb(82, 212, 143);
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: rgb(91, 235, 158);
        }
        a {
            color: rgb(255, 214, 52);
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .alert-danger ul {
            margin-bottom: 0;
            padding-left: 0;
            list-style-type: none;
        }
    </style>
</head>
<body>
    <div class="register-container text-center">
        <h2 class="mb-3">Register Akun Baru</h2>
        
        <?php if(session()->has('errors')): ?>
            <div class="alert alert-danger p-2 text-start">
                <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?= site_url('process-register') ?>" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Nama Lengkap" required value="<?= old('username') ?>">
                <label for="username">username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?= old('email') ?>">
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password (min. 8 karakter)" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" placeholder="Konfirmasi Password" required>
                <label for="pass_confirm">Konfirmasi Password</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>

<a href="<?= site_url('/') ?>" class="btn btn-outline-light w-100 mt-2">Batal</a>
</form>
        <p class="mt-4">Sudah punya akun? <a href="<?= site_url('login') ?>">Login di sini</a></p>
    </div>
</body>
</html>