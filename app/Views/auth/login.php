<!DOCTYPE html>
<html lang="id">
<head>
    <title>MyDompet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(23, 79, 184),rgb(82, 131, 214));
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
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
    <div class="login-container text-center">
        <h2 class="mb-4">Login</h2>
        <form action="/process-login" method="post">
            <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>
            <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3">Belum punya akun? <a href="/register">Daftar di sini</a></p>
    </div>
</body>
</html>