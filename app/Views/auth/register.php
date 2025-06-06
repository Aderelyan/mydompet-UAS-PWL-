<!DOCTYPE html>
<html lang="id">
<head>
    <title>MyDompet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(23, 79, 184),rgb(82, 131, 214));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
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
            background:rgb(82, 212, 143);
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background:rgb(91, 235, 158);
        }
        a {
            color:rgb(255, 214, 52);
        }
    </style>
</head>
<body>
    <div class="card text-center">
        <h2 class="mb-3">Register</h2>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?php echo session()->getFlashdata('error'); ?></div>
        <?php endif; ?>
        
        <form action="/process-register" method="post">
            <input type="text" name="username" placeholder="Username" class="form-control mt-2" required>
            <input type="email" name="email" placeholder="Email" class="form-control mt-2" required>
            <input type="password" name="password" placeholder="Password" class="form-control mt-2" required>
            <button type="submit" class="btn btn-primary mt-3 w-100">Register</button>
        </form>
        <p class="mt-3">Sudah punya akun? <a href="/login">Login</a></p>
    </div>
</body>
</html>