<?php

namespace App\Controllers;

class Diagram extends BaseController {
    public function index() {
        // Nanti di sini kita akan query data dari model
        // Untuk sekarang, kita siapkan data dummy dulu
        $dataForChart = [
            'labels' => ['Pemasukan', 'Pengeluaran'],
            'values' => [5000000, 2000000] // Data ini nanti diambil dari DB
        ];
        
        $data['chartData'] = json_encode($dataForChart);
        return view('diagram', $data);
    }
}
