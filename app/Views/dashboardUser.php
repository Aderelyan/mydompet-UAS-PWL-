<?= $this->include('layouts/header') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<h2>Selamat Datang, <span class="text-primary fw-bold"><?= esc(session('user_name')) ?: 'Guest' ?></span>!</h2><br>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Filter Ringkasan Kartu</h5>
        <form method="get" action="<?= site_url('dashboard') ?>">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label for="kartu_tanggal_awal" class="form-label">Dari Tanggal:</label>
                    <input type="date" name="kartu_tanggal_awal" id="kartu_tanggal_awal" class="form-control" value="<?= esc($filter_kartu['tanggal_awal'] ?? '') ?>">
                </div>
                <div class="col-md-5">
                    <label for="kartu_tanggal_akhir" class="form-label">Sampai Tanggal:</label>
                    <input type="date" name="kartu_tanggal_akhir" id="kartu_tanggal_akhir" class="form-control" value="<?= esc($filter_kartu['tanggal_akhir'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter Kartu</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h4>Pemasukan</h4>
                <h2>Rp <?= number_format($summary['pemasukan'] ?? 0, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h4>Pengeluaran</h4>
                <h2>Rp <?= number_format($summary['pengeluaran'] ?? 0, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4>Total Saldo Dompet</h4>
                <h2>Rp <?= number_format($summary['total_saldo'] ?? 0, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<br><hr><br>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Riwayat Transaksi</h4>
    <a href="<?= site_url('transaksi') ?>" class="btn btn-success fw-bold">+ Tambah / Lihat Semua</a>
</div>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Filter Riwayat Transaksi</h5>
        <form method="get" action="<?= site_url('dashboard') ?>">
             <div class="row align-items-end">
                <div class="col-md-5">
                    <label for="tabel_tanggal_awal" class="form-label">Dari Tanggal:</label>
                    <input type="date" name="tabel_tanggal_awal" id="tabel_tanggal_awal" class="form-control" value="<?= esc($filter_tabel['tanggal_awal'] ?? '') ?>">
                </div>
                <div class="col-md-5">
                    <label for="tabel_tanggal_akhir" class="form-label">Sampai Tanggal:</label>
                    <input type="date" name="tabel_tanggal_akhir" id="tabel_tanggal_akhir" class="form-control" value="<?= esc($filter_tabel['tanggal_akhir'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter Tabel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle bg-white shadow-sm rounded">
        <thead class="table-light text-center">
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th class="text-end">Jumlah</th>
                <th class="text-center">Jenis</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="4" class="text-center py-4">Belum ada transaksi pada rentang tanggal ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($transactions as $tx) : ?>
                <tr>
                    <td class="text-center"><?= date('d M Y', strtotime($tx['tanggal_transaksi'])) ?></td>
                    <td><?= esc($tx['keterangan']) ?></td>
                    <td class="text-end fw-bold fs-5 <?= $tx['tipe_transaksi'] === 'pemasukan' ? 'text-success' : 'text-danger' ?>">
                        <?= $tx['tipe_transaksi'] === 'pemasukan' ? '+' : '-' ?> Rp <?= number_format($tx['jumlah'], 0, ',', '.') ?>
                    </td>
                    <td class="text-center">
                        <span class="badge rounded-pill fs-6 text-bg-<?= $tx['tipe_transaksi'] === 'pemasukan' ? 'success' : 'danger' ?>">
                            <?= ucfirst($tx['tipe_transaksi']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<br><br><hr>

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