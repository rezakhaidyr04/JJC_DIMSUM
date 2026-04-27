<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
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
        $recentActivities = $this->getRecentActivities();
        
        // Get low stock notifications
        $lowStockItems = Barang::getLowStockNotifications();

        return view('dashboard.index', compact(
            'totalBarang',
            'totalMasuk',
            'totalKeluar',
            'totalStok',
            'chartData',
            'recentActivities',
            'lowStockItems'
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

            $masuk = BarangMasuk::whereDate('tanggal_masuk', $date)->sum('jumlah');
            $keluar = BarangKeluar::whereDate('tanggal_keluar', $date)->sum('jumlah');

            $masukData[] = $masuk;
            $keluarData[] = $keluar;
        }

        return [
            'labels' => $labels,
            'masukData' => $masukData,
            'keluarData' => $keluarData,
        ];
    }

    private function getRecentActivities()
    {
        $masuk = DB::table('barang_masuk')
            ->join('users', 'users.id', '=', 'barang_masuk.user_id')
            ->join('barang', 'barang.id', '=', 'barang_masuk.barang_id')
            ->where('users.role', 'karyawan')
            ->whereNull('barang_masuk.deleted_at')
            ->selectRaw("'masuk' as tipe, users.name as penginput, barang.nama_barang, barang_masuk.jumlah, barang_masuk.created_at");

        return DB::table('barang_keluar')
            ->join('users', 'users.id', '=', 'barang_keluar.user_id')
            ->join('barang', 'barang.id', '=', 'barang_keluar.barang_id')
            ->where('users.role', 'karyawan')
            ->whereNull('barang_keluar.deleted_at')
            ->selectRaw("'keluar' as tipe, users.name as penginput, barang.nama_barang, barang_keluar.jumlah, barang_keluar.created_at")
            ->unionAll($masuk)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }
}
