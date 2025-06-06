<!DOCTYPE html>
<html lang="id">
<head>
    <title>MyDompet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Background futuristik */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg,rgb(23, 79, 184),rgb(163, 197, 255));
            color: white;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 0.3);
        }
        
        /* Efek Neon */
        .btn-neon {
            font-weight: bold;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease-in-out;
        }
        .btn-neon:hover {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.9);
        }

        /* Carousel dengan shadow */
        .carousel-item img {
            border: 5px solid white;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        /* Animasi Slide */
        .carousel-item {
            transition: transform 0.8s ease-in-out;
        }
        .navbar {
            background-color: rgb(23, 79, 184);
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
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">MyDompet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#heroGuest">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#catatanGuest">Catatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#diagramGuest">Diagram</a></li>
                    <li class="nav-item"><a class="nav-link" href="#hutangGuest">Hutang</a></li>
                    <a href="<?= base_url('login') ?>" class="btn btn-light btn-lg me-2 btn-neon">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-lg btn-neon">Daftar</a>

                
                </ul>
            </div>
        </div>
    </nav>

    <section id="heroGuest" class="hero d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h1 class="fw-bold">Kelola Keuangan dengan Mudah</h1>
                    <p class="lead">Catat pemasukan & pengeluaranmu secara praktis, dan pantau laporan keuangan dengan grafik yang informatif.</p>
                    <a href="<?= base_url('login-demo') ?>" class="btn btn-light btn-lg me-2 btn-neon">Coba Demo</a>

                </div>
                <div class="col-md-6">
                    <!-- Carousel Bootstrap 5 -->
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= base_url('img/hero_guest_1.jpg') ?>" class="d-block w-100" alt="Ilustrasi 1">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('img/hero_guest_2.jpg') ?>" class="d-block w-100" alt="Ilustrasi 2">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('img/hero_guest_3.jpg') ?>" class="d-block w-100" alt="Ilustrasi 3">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('img/hero_guest_4.jpg') ?>" class="d-block w-100" alt="Ilustrasi 4">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('img/hero_guest_5.jpg') ?>" class="d-block w-100" alt="Ilustrasi 5">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="catatanGuest" class="hero d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                   
                    <div>
                        <div>
                            <div class="">
                                <img src="<?= base_url('img/catatan.png') ?>" class="d-block w-100" alt="Ilustrasi 1">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-5 text-center text-md-start">
                    <h1 class="fw-bold">Fitur Catatan</h1>
                    <p class="lead">Anda dapat mencatat pemasukan dan pengeluaran beserta tanggalnya , serta dapat menampilkan history catatan. Anda juga bisa menampilkan history catatan dengan memfilter mulai dari tanggal berapa. </p>
                   
                </div>
                
            </div>
        </div>
    </section>

    <section id="diagramGuest" class="hero d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 text-center text-md-start">
                    <h1 class="fw-bold">Fitur Diagram</h1>
                    <p class="lead"> Anda dapat melihat pemasukan dan pengeluaran yang divisualisasikan dengan diagram, serta dapat dengan memfilter mulai dari tanggal berapa untuk data yang di tampilkan pada diagram.</p>
                   
                </div>
                <div class="col-md-7">
                   
                    <div>
                        <div>
                            <div class="">
                                <img src="<?= base_url('img/diagram.png') ?>" class="d-block w-100" alt="Ilustrasi 1">
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                
            </div>
        </div>
    </section>

    <section id="hutangGuest" class="hero d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                   
                    <div>
                        <div>
                            <div class="">
                                <img src="<?= base_url('img/hutang.png') ?>" class="d-block w-100" alt="Ilustrasi 1">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-5 text-center text-md-start">
                    <h1 class="fw-bold">Fitur Hutang/Piutang</h1>
                    <p class="lead">Anda dapat mencatat hutang dan piutang beserta tanggalnya , serta dapat menampilkan history catatan hutang/piutang. Anda juga bisa menampilkan history catatan hutang/piutang dengan memfilter mulai dari tanggal berapa. </p>
                   
                </div>
                
            </div>
        </div>
    </section>

    <section id="hutangGuest" class="hero d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                   
                 
                
                
            </div>
        </div>
    </section>


</body>
</html>
