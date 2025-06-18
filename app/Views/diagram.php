<?= $this->include('layouts/header') ?>

<div class="container my-4">
    <h2 class="text-center mb-4">Diagram Keuangan</h2>

    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form method="get" action="<?= site_url('diagram') ?>">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="tanggal_awal" class="form-label">Dari Tanggal:</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= esc($filter['tanggal_awal']) ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="tanggal_akhir" class="form-label">Sampai Tanggal:</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= esc($filter['tanggal_akhir']) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12 mb-5">
            <div class="card shadow-sm">
                <div class="card-header"><h5>Pengeluaran Berdasarkan Kategori</h5></div>
                <div class="card-body" style="height: 400px; position: relative;">
                    <canvas id="chartPie"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card shadow-sm">
                 <div class="card-header"><h5>Tren Transaksi</h5></div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="chartLine"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    // [PERBAIKAN] Mendaftarkan plugin datalabels secara global
    Chart.register(ChartDataLabels);

    const chartData = JSON.parse('<?= $chartDataJson ?? '{}' ?>');
    const ctxPie = document.getElementById('chartPie').getContext('2d');
    
    // Cek apakah ada data untuk pie chart
    if (chartData.pie && chartData.pie.data.length > 0) {
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: chartData.pie.labels,
                datasets: [{
                    data: chartData.pie.data,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#E7E9ED', '#8BC34A'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    // [PERBAIKAN] Konfigurasi datalabels yang lebih kuat
                    datalabels: {
                        display: true, // Pastikan label ditampilkan
                        formatter: (value, ctx) => {
    // [PERBAIKAN] Gunakan Number() untuk memastikan semua data adalah angka saat dijumlahkan
    const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + Number(b), 0);
    
    // Mencegah pembagian dengan nol
    if (sum === 0) {
        return '0%';
    }

    // [PERBAIKAN] Pastikan 'value' yang sedang diproses juga diperlakukan sebagai Angka
    const percentage = (Number(value) * 100 / sum).toFixed(1) + "%";
    return percentage;
},
                        color: '#fff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            }
        });
    } else {
        // Jika tidak ada data, tampilkan pesan
        let canvas = ctxPie.canvas;
        ctxPie.font = "16px Arial";
        ctxPie.fillStyle = "#6c757d";
        ctxPie.textAlign = "center";
        ctxPie.fillText("Tidak ada data pengeluaran pada rentang tanggal ini.", canvas.width / 2, canvas.height / 2);
    }

    // ===================================
    // LINE CHART - TREN HARIAN (Kode ini tetap sama dan seharusnya sudah berfungsi)
    // ===================================
    const ctxLine = document.getElementById('chartLine').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: chartData.line.labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: chartData.line.incomeData,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    fill: true,
                    tension: 0.2
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.line.expenseData,
                    borderColor: '#FF5252',
                    backgroundColor: 'rgba(255, 82, 82, 0.2)',
                    fill: true,
                    tension: 0.2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<?= $this->include('layouts/footer') ?>