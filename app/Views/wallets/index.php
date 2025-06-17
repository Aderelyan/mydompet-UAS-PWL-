<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4">Manajemen Dompet</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session()->has('errors')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Perhatian!</strong> Terdapat kesalahan validasi:
        <ul class="mb-0">
        <?php foreach (session('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Daftar Dompet Anda</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php if (empty($wallets)): ?>
                        <p class="text-center">Anda belum memiliki dompet.</p>
                    <?php else: ?>
                        <?php foreach($wallets as $wallet): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?= esc($wallet['nama_dompet']) ?></h6>
                                <strong class="text-success">Rp <?= number_format($wallet['saldo'], 0, ',', '.') ?></strong>
                            </div>
                            <form action="<?= site_url('wallets/delete/' . $wallet['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dompet ini?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Transfer Saldo</h5></div>
            <div class="card-body">
                <form action="<?= site_url('wallets/transfer') ?>" method="post">
                    <div class="mb-3">
                        <label for="wallet_sumber_id" class="form-label">Dari Dompet</label>
                        <select name="wallet_sumber_id" id="wallet_sumber_id" class="form-select" required>
                            <option value="">-- Pilih Sumber --</option>
                            <?php foreach($wallets as $w) echo "<option value='{$w['id']}'>{$w['nama_dompet']}</option>"; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="wallet_tujuan_id" class="form-label">Ke Dompet</label>
                        <select name="wallet_tujuan_id" id="wallet_tujuan_id" class="form-select" required>
                            <option value="">-- Pilih Tujuan --</option>
                            <?php foreach($wallets as $w) echo "<option value='{$w['id']}'>{$w['nama_dompet']}</option>"; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Transfer</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Transfer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Buat Dompet Baru</h5></div>
            <div class="card-body">
                <form action="<?= site_url('wallets/create') ?>" method="post">
                    <div class="mb-3">
                        <label for="nama_dompet" class="form-label">Nama Dompet</label>
                        <input type="text" name="nama_dompet" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="saldo" class="form-label">Saldo Awal</label>
                        <input type="number" name="saldo" class="form-control" value="0" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Buat</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>