<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h3>Ganti Password</h3>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <form action="/ganti-password/process" method="post">
        <div class="mb-3">
            <label>Password Lama</label>
            <input type="password" class="form-control" name="password_lama" required>
        </div>
        <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" class="form-control" name="password_baru" required>
        </div>
        <div class="mb-3">
            <label>Konfirmasi Password Baru</label>
            <input type="password" class="form-control" name="password_konfirmasi" required>
        </div>
        <button type="submit" class="btn btn-primary">Ganti Password</button>
    </form>
</div>
<?= $this->endSection() ?>
