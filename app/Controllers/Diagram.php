<?php

namespace App\Controllers;

use App\Models\DiagramModel; // Gunakan model baru kita
use CodeIgniter\I18n\Time;

class Diagram extends BaseController
{
    public function index()
    {
        $diagramModel = new DiagramModel();
        $userId = session()->get('user_id');

        // 1. Ambil dan siapkan filter tanggal
        $filter = [
            'tanggal_awal'  => $this->request->getGet('tanggal_awal'),
            'tanggal_akhir' => $this->request->getGet('tanggal_akhir'),
        ];
        // Default: bulan ini
        if (empty($filter['tanggal_awal'])) {
            $filter['tanggal_awal'] = date('Y-m-01');
        }
        if (empty($filter['tanggal_akhir'])) {
            $filter['tanggal_akhir'] = date('Y-m-t');
        }
        $filtersForQuery = array_merge($filter, ['user_id' => $userId]);

        // 2. Ambil data untuk Pie Chart (Pengeluaran per Kategori)
        $expenseByCategory = $diagramModel->getExpenseByCategory($filtersForQuery);
        $pieChartData = [
            'labels' => array_column($expenseByCategory, 'nama_kategori'),
            'data'   => array_column($expenseByCategory, 'total'),
        ];
        
        // 3. Ambil data untuk Line Chart (Tren Harian) dan olah
        $dailyTrend = $diagramModel->getDailyTrend($filtersForQuery);
        $lineChartData = [
            'labels'      => [],
            'incomeData'  => [],
            'expenseData' => [],
        ];
        // Buat rentang tanggal lengkap agar tidak ada hari yang terlewat
        $start = new Time($filter['tanggal_awal']);
        $end = new Time($filter['tanggal_akhir']);
        $trendDataMap = array_column($dailyTrend, null, 'tanggal');

        while($start->isBefore($end) || $start->equals($end)) {
            $dateString = $start->toDateString();
            $lineChartData['labels'][] = $start->format('d M'); // Format label: 01 Jun
            $lineChartData['incomeData'][] = $trendDataMap[$dateString]['total_pemasukan'] ?? 0;
            $lineChartData['expenseData'][] = $trendDataMap[$dateString]['total_pengeluaran'] ?? 0;
            $start = $start->addDays(1);
        }

        // 4. Siapkan semua data chart dalam satu variabel JSON
        $dataForChart = [
            'pie' => $pieChartData,
            'line' => $lineChartData,
        ];
        
        $data = [
            'filter' => $filter,
            'chartDataJson' => json_encode($dataForChart),
        ];

        return view('diagram', $data);
    }
}