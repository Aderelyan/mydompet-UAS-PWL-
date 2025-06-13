<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-danger shadow-lg">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0 text-center">Konfirmasi Hapus Akun</h4>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-danger">
                    <h5 class="alert-heading fw-bold">PERINGATAN!</h5>
                    <p>Aksi ini tidak dapat dibatalkan. Menghapus akun Anda akan menghapus **SELURUH DATA** secara permanen, termasuk:</p>
                    <ul>
                        <li>Semua riwayat transaksi</li>
                        <li>Semua dompet yang Anda buat</li>
                        <li>Semua kategori yang Anda buat</li>
                    </ul>
                </div>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-warning"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= site_url('process-delete-account') ?>" method="post">
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Untuk melanjutkan, masukkan password Anda:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg">HAPUS AKUN SAYA SECARA PERMANEN</button>
                        <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>