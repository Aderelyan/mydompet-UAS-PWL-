<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyDompet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background: linear-gradient(135deg,rgb(139, 180, 250),rgb(23, 79, 184));
            box-shadow: 0 0 10px rgba(0, 174, 255, 0.7);
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color:rgb(255, 255, 255);
        }
        .navbar-brand:hover{
            color: rgb(23, 79, 184);
        }
        .navbar-nav .nav-link {
            color: white;
            transition: 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: #00d9ff;
            text-shadow: 0 0 10px #00d9ff;
        }
        .dropdown-menu {
            background:rgb(255, 255, 255);
            border: none;
            box-shadow: 0 0 10px rgba(0, 174, 255, 0.5);
        }
        
        .dropdown-item:hover {
            background:rgb(139, 180, 250);
            color:rgb(255, 255, 255);
            
            border-radius: 5px;
        }
        .dropdown-item.text-danger:hover {
        color: white !important; 
        background-color: #dc3545 !important; 
         }
        
     
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">MyDompet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/catatan">Catatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="/diagram">Diagram</a></li>
                    <li class="nav-item"><a class="nav-link" href="/hutang">Hutang</a></li>
                    <?php if (session()->has('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= session('user_name') ?: 'Guest' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('ganti-password') ?>">Ganti Password</a></li>
                            <li><a class=" dropdown-item text-danger fw-bold"  href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (session('username') == 'guest'): ?>
                <div class="alert alert-info text-center">
                    Anda sedang dalam mode demo. Data akan dihapus saat logout.
                </div>
            <?php endif ?>

        </div>
    </nav>
    <div class="container mt-4">