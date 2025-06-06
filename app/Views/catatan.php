<?= $this->include('layouts/header') ?>

<div class="container mt-4">
    <h2 class="text-center text-primary">Catatan Keuangan</h2><br>
    
    <!-- Form Input -->
    <div class="card shadow-lg p-4 rounded border-0 bg-white">
        <h4 class="text-center text-secondary">Tambah Catatan</h4>
        <form action="<?= base_url('catatan/simpan') ?>" method="post">
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" required>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <select class="form-control" id="jenis" name="jenis" required>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
    </div><br><br>
    
    <hr><br>

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



    <br>
    
    <!-- Daftar Catatan Keuangan -->
    
    <div class="table-responsive">
        <table class="table table-striped table-hover text-center shadow rounded">
            <thead class="table-white">
                <tr>
                    <th>No</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($catatan as $c) : ?>
                <tr class="<?= $c['jenis'] === 'pemasukan' ? 'table-success' : 'table-danger' ?>">
                    <td><?= $no++ ?></td>
                    <td><?= $c['keterangan'] ?></td>
                    <td>Rp <?= number_format($c['jumlah'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($c['jenis']) ?></td>
                    <td><?= date('d-m-Y', strtotime($c['tanggal'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<br>
<?= $this->include('layouts/footer') ?>
