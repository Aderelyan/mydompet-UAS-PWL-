<?= $this->include('layouts/header') ?>
<style>
        .body {
            background-color: #ffffff;
        }
        .filter-container {
            
            border-radius: 10px;
        }
        .chart-container {
            width: 100%;
            max-width: 400px;
            height: 400px;
            background-color: rgb(255, 255, 255);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
        }
        .chart-container-line {
            width: 100%; /* Penuh kanan-kiri */
            height: 400px;
            background-color: rgb(255, 255, 255);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .chart-container-line canvas {
            display: block;
            width: 100% !important;
            max-width: none;
        }

        h1 { color: black; background: #ffffff; padding: 15px; border-radius: 10px; display: inline-block; }
    </style>

<div class="body" >
    <h2 class="text-center">Diagram Keuangan</h2><br>

    <!-- Form Filter -->
    <form method="get" action="<?= base_url('catatan') ?>">
        <div class="row mb-3 justify-content-center">
            <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Dari Tanggal:</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Sampai Tanggal:</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <br><br>

    <div class="row justify-content-center">
    <!-- Grafik Batang -->
    <div class="col-md-6 d-flex justify-content-center">
        <div class="chart-container">
            <canvas id="chartBar"></canvas>
        </div>
    </div>

    <!-- Grafik Pie -->
    <div class="col-md-6 d-flex justify-content-center">
        <div class="chart-container">
            <canvas id="chartPie"></canvas>
        </div>
    </div>
    </div>

    <br><br>

    <!-- Grafik Line (Lebar Penuh) -->
    <div class="row justify-content-center">
    <div class="col-11 mx-auto">
        <div class="chart-container-line">
            <canvas id="chartLine"></canvas>
        </div>
    </div>
    </div><br>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        const ctxBar = document.getElementById('chartBar').getContext('2d');
        const ctxPie = document.getElementById('chartPie').getContext('2d');
        const ctxLine = document.getElementById('chartLine').getContext('2d');

        let pemasukan = 15000000;
        let pengeluaran = 2000000;
        let total = pemasukan + pengeluaran;

        const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        let dataPemasukan = [5000000, 6000000, 7000000, 6500000, 8000000, 9000000];
        let dataPengeluaran = [2000000, 2500000, 3000000, 2800000, 3500000, 4000000];

        const colors = {
            pemasukan: '#4CAF50',
            pengeluaran: '#FF5252',
            linePemasukan: '#00E676',
            linePengeluaran: '#FF4081'
        };

        let barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    label: 'Total Keuangan',
                    data: [pemasukan, pengeluaran],
                    backgroundColor: [colors.pemasukan, colors.pengeluaran],
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 1,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#000000' } },
                    x: { ticks: { color: '#000000' } }
                }
            }
        });

        let pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [pemasukan, pengeluaran],
                    backgroundColor: [colors.pemasukan, colors.pengeluaran]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#000000' } },
                    datalabels: {
                        formatter: (value) => ((value / total) * 100).toFixed(1) + "%",
                        color: '#ffffff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        let lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: bulan,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: dataPemasukan,
                        borderColor: colors.linePemasukan,
                        backgroundColor: 'rgba(4, 255, 134, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Pengeluaran',
                        data: dataPengeluaran,
                        borderColor: colors.linePengeluaran,
                        backgroundColor: 'rgba(255, 11, 92, 0.42)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#000000' } },
                    x: { ticks: { color: '#000000' } }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#000000' // Mengubah warna teks label legend menjadi putih
                        }
                    }
                }
            }

        });

        function updateChart() {
            let selectedMonth = document.getElementById('filterBulan').value;
            let selectedYear = document.getElementById('filterTahun').value;
            let selectedDate = document.getElementById('filterTanggal').value;

            alert(`Filter diterapkan:\nBulan: ${selectedMonth}\nTahun: ${selectedYear}\nTanggal: ${selectedDate}`);

            // Update data di sini (misalnya, mengambil dari database via AJAX)
        }
    </script>

    
</div class="body">
    
<?= $this->include('layouts/footer') ?>
