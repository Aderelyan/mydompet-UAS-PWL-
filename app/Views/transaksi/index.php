<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2 class="text-center mb-4">Halaman Transaksi</h2>

    <div class="card shadow-sm p-4 mb-5">
        <h4 class="text-center text-primary mb-4">Tambah Transaksi Baru</h4>
        
        <?php if(session()->has('errors')): ?>
            <div class="alert alert-danger p-2">
                <ul class="mb-0" style="list-style-type: none; padding-left: 0;">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('transaksi/create') ?>" method="post">
            <div class="mb-3">
                <label for="jumlah" class="form-label fw-bold">Jumlah (Rp)</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Contoh: 50000" required value="<?= old('jumlah') ?>">
            </div>

            <div class="mb-3">
                <label for="wallet_id" class="form-label fw-bold">Sumber Dana (Dompet)</label>
                <select class="form-select" id="wallet_id" name="wallet_id" required>
                    <option value="">-- Pilih Dompet --</option>
                    <?php foreach ($wallets as $wallet): ?>
                        <option value="<?= $wallet['id'] ?>" <?= old('wallet_id') == $wallet['id'] ? 'selected' : '' ?>><?= esc($wallet['nama_dompet']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label fw-bold">Kategori Transaksi</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <optgroup label="Pemasukan">
                        <?php foreach ($categories as $category): ?>
                            <?php if ($category['tipe_kategori'] == 'pemasukan'): ?>
                                <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>><?= esc($category['nama_kategori']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Pengeluaran">
                        <?php foreach ($categories as $category): ?>
                            <?php if ($category['tipe_kategori'] == 'pengeluaran'): ?>
                                <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>><?= esc($category['nama_kategori']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <div class="mb-3">
                <label for="tanggal_transaksi" class="form-label fw-bold">Tanggal</label>
                <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" required value="<?= old('tanggal_transaksi', date('Y-m-d')) ?>">
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label fw-bold">Keterangan (Opsional)</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Beli makan siang"><?= old('keterangan') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Transaksi</button>
        </form>
    </div>
    <div class="card shadow-sm p-4">
        <h4 class="text-center text-secondary mb-4">Riwayat Transaksi</h4>

        <form method="get" action="<?= site_url('transaksi') ?>">
            <div class="row mb-4 align-items-end">
                <div class="col-md-5">
                    <label for="tanggal_awal" class="form-label">Dari Tanggal:</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= esc($filter['tanggal_awal'] ?? '') ?>">
                </div>
                <div class="col-md-5">
                    <label for="tanggal_akhir" class="form-label">Sampai Tanggal:</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= esc($filter['tanggal_akhir'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
            <thead class="table-light">
    <tr class="text-center">
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Kategori</th>
        <th>Dompet</th>
        <th class="text-end">Jumlah</th>
        <th>Aksi</th> </tr>
</thead>
<tbody>
    <?php if (empty($transactions)): ?>
        <tr><td colspan="6" class="text-center py-4">Tidak ada data...</td></tr> <?php else: ?>
        <?php foreach($transactions as $tx): ?>
        <tr>
            <td class="text-center"><?= date('d M Y', strtotime($tx['tanggal_transaksi'])) ?></td>
            <td><?= esc($tx['keterangan']) ?></td>
            <td class="text-center"><?= esc($tx['nama_kategori']) ?></td>
            <td class="text-center"><?= esc($tx['nama_dompet']) ?></td>
            <td class="text-end fw-bold <?= $tx['tipe_transaksi'] === 'pemasukan' ? 'text-success' : 'text-danger' ?>">
                <?= $tx['tipe_transaksi'] === 'pemasukan' ? '+' : '-' ?> Rp <?= number_format($tx['jumlah'], 0, ',', '.') ?>
            </td>
            <td class="text-center">
                <form action="<?= site_url('transaksi/delete/' . $tx['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo dompet akan dikembalikan.');">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>