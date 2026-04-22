<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBarang = Barang::count();
        $totalMasuk = BarangMasuk::sum('jumlah');
        $totalKeluar = BarangKeluar::sum('jumlah');
        $totalStok = Barang::sum('stok');

        // Get data for chart (last 7 days)
        $chartData = $this->getChartData();

        return view('dashboard.index', compact(
            'totalBarang',
            'totalMasuk',
            'totalKeluar',
            'totalStok',
            'chartData'
        ));
    }

    private function getChartData()
    {
        $days = 7;
        $labels = [];
        $masukData = [];
        $keluarData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $masuk = BarangMasuk::whereDate('tanggal', $date)->sum('jumlah');
            $keluar = BarangKeluar::whereDate('tanggal', $date)->sum('jumlah');

            $masukData[] = $masuk;
            $keluarData[] = $keluar;
        }

        return [
            'labels' => $labels,
            'masukData' => $masukData,
            'keluarData' => $keluarData,
        ];
    }
}
