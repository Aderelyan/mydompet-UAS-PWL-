<?= $this->include('layouts/header') ?>
<div class="container my-4">
    <h3 class="text-center mb-4">Catatan Utang Piutang</h3>

    <!-- <div class="text-end mb-3">
        <a href="<?= base_url('utang-piutang/tambah') ?>" class="btn btn-success">+ Tambah Catatan</a>
    </div> -->

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm bg-white rounded">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop data utang/piutang -->
                <tr>
                    <td>1</td>
                    <td>Budi</td>
                    <td>Utang</td>
                    <td>Rp 200.000</td>
                    <td>01-04-2025</td>
                    <td><span class="badge bg-warning text-dark">Belum Lunas</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
                <!-- Akhir loop -->
            </tbody>
        </table>
    </div>
</div>

<div class="container my-4">
    <h3 class="text-center mb-4">Tambah Catatan Utang/Piutang</h3>

    <form action="<?= base_url('utang-piutang/simpan') ?>" method="post" class="bg-white p-4 shadow rounded">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Orang</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <select class="form-select" id="jenis" name="jenis" required>
                <option value="utang">Utang</option>
                <option value="piutang">Piutang</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="belum">Belum Lunas</option>
                <option value="lunas">Lunas</option>
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>


<?= $this->include('layouts/footer') ?>
