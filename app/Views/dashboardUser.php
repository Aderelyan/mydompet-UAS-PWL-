<?= $this->include('layouts/header') ?>
<h2>Selamat Datang <?= session('user_name') ?: 'Guest' ?></h2><br>

<!-- Form Filter -->
<form method="get" action="<?= base_url('catatan') ?>">
        <div class="row mb-3">
            <div class="col-md-5">
                <label for="tanggal_awal" class="form-label">Dari Tanggal:</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required>
            </div>
            <div class="col-md-5">
                <label for="tanggal_akhir" class="form-label">Sampai Tanggal:</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h4>Pemasukan</h4>
                <h2>Rp 5.000.000</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h4>Pengeluaran</h4>
                <h2>Rp 2.000.000</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4>Saldo</h4>
                <h2>Rp 3.000.000</h2>
            </div>
        </div>
    </div>
</div><br><br><br><hr>


<!-- Section Artikel Keuangan -->
<div class="container mt-5">
    <h3 class="text-center">Artikel Keuangan</h3><br>
    <div class="row">
        <?php 
        $articles = [
            ["img" => "artikel1.png", "title" => "7 Majalah Keuangan Terbaik (Maret 2025)", 
             "desc" => "Informasi mungkin merupakan aset paling berharga bagi seorang investor, karena pasar pada akhirnya didorong oleh faktor fundamental.", 
             "link" => "https://www.securities.io/id/majalah-keuangan-terbaik/"],

            ["img" => "artikel3.png", "title" => "Metode Budgeting 50/30/20 untuk Pengelolaan Keuangan", 
             "desc" => "Jika Anda mulai menyadari pentingnya budgeting atau berniat mendalami pengelolaan keuangan, mungkin Anda perlu mengetahui metode atau aturan 50/30/20.", 
             "link" => "https://www.manulife.co.id/id/artikel/metode-budgeting-50-30-20-untuk-pengelolaan-keuangan.html"],

            ["img" => "artikel4.png", "title" => "Pengelolaan Keuangan Keluarga", 
             "desc" => "Terbiasa dan ahli dalam pengelolaan keuangan di lingkungan kantor, ternyata tidak menjamin hal tersebut berjalan di lingkungan keluarga.",
             "link" => "https://www.djkn.kemenkeu.go.id/kpknl-lhokseumawe/baca-artikel/16342/Pengelolaan-Keuangan-Keluarga.html"],

            ["img" => "artikel2.jpg", "title" => "Tips Investasi dan Mengelola Keuangan di Era Modern",
             "desc" => "Di era digital saat ini, keputusan finansial yang tepat menjadi semakin krusial, terutama dengan meningkatnya akses ke berbagai instrumen investasi.",
             "link" => "https://feb.ugm.ac.id/id/berita/4831-tips-investasi-dan-mengelola-keuangan-di-era-modern"],

            ["img" => "artikel5.jpg", "title" => "Mengenal Manajemen Keuangan Syariah",
             "desc" => "Keuangan syariah semakin diminati masyarakat Indonesia. Hal itu terbukti dengan data OJK yang mencatat aset keuangan berbasis syariat mencapai Rp1.836 triliun.",
             "link" => "https://www.jurnal.id/id/blog/manajemen-keuangan-syariah/"],

            ["img" => "artikel6.png", "title" => "Dear Gen Z, Hati-Hati Ini Bisa Bikin Keuanganmu Bermasalah!",
             "desc" => "Gen Z di Indonesia ternyata sudah mendominasi populasi usia produktif dengan persentase mencapai 27,94 persen.",
             "link" => "https://blog.principal.co.id/dear-gen-z-hati-hati-ini-bisa-bikin-keuanganmu-bermasalah"]
        ];

        foreach ($articles as $article) :
        ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <img src="<?= base_url('img/' . $article['img']) ?>" class="card-img-top" alt="<?= $article['title'] ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= $article['title'] ?></h5>
                    <p class="card-text"><?= $article['desc'] ?></p>                
                    <a href="<?= $article['link'] ?>" class="btn btn-primary mt-auto" target="_blank">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>



<?= $this->include('layouts/footer') ?>