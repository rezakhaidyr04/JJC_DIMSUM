<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Display stock report
     */
    public function index(Request $request): View|StreamedResponse
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $tanggalMulai = $validated['tanggal_mulai'] ?? null;
        $tanggalSelesai = $validated['tanggal_selesai'] ?? null;
        $export = $validated['export'] ?? null;

        $laporan = $this->buildLaporan($tanggalMulai, $tanggalSelesai);

        if ($export === 'excel') {
            return $this->exportExcel($laporan, $tanggalMulai, $tanggalSelesai);
        }

        if ($export === 'pdf') {
            return response()->view('laporan.pdf', [
                'laporan' => $laporan,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
            ]);
        }

        return view('laporan.index', [
            'laporan' => $laporan,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    }

    /**
     * Build report data with optional date filters.
     */
    private function buildLaporan(?string $tanggalMulai, ?string $tanggalSelesai): Collection
    {
        $barang = Barang::orderBy('nama_barang')->get();

        return $barang->map(function ($item) use ($tanggalMulai, $tanggalSelesai) {
            $masukQuery = $item->barangMasuk();
            $keluarQuery = $item->barangKeluar();

            if ($tanggalMulai && $tanggalSelesai) {
                $masukQuery->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
                $keluarQuery->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            } elseif ($tanggalMulai) {
                $masukQuery->whereDate('tanggal', '>=', $tanggalMulai);
                $keluarQuery->whereDate('tanggal', '>=', $tanggalMulai);
            } elseif ($tanggalSelesai) {
                $masukQuery->whereDate('tanggal', '<=', $tanggalSelesai);
                $keluarQuery->whereDate('tanggal', '<=', $tanggalSelesai);
            }

            $barangMasuk = $masukQuery->sum('jumlah');
            $barangKeluar = $keluarQuery->sum('jumlah');

            $stokAwal = 0;
            $stokAkhir = $item->stok;

            if ($tanggalMulai) {
                $masukSebelum = $item->barangMasuk()->whereDate('tanggal', '<', $tanggalMulai)->sum('jumlah');
                $keluarSebelum = $item->barangKeluar()->whereDate('tanggal', '<', $tanggalMulai)->sum('jumlah');
                $stokAwal = $masukSebelum - $keluarSebelum;
                $stokAkhir = $stokAwal + $barangMasuk - $barangKeluar;
            }

            return [
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'stok_awal' => $stokAwal,
                'barang_masuk' => $barangMasuk,
                'barang_keluar' => $barangKeluar,
                'stok_akhir' => $stokAkhir,
            ];
        });
    }

    /**
     * Export report to CSV (Excel compatible).
     */
    private function exportExcel(Collection $laporan, ?string $tanggalMulai, ?string $tanggalSelesai): StreamedResponse
    {
        $filename = 'laporan-stok-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($laporan, $tanggalMulai, $tanggalSelesai) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Laporan Stok Cikampek Jajanan']);
            fputcsv($handle, ['Periode', ($tanggalMulai ?: '-') . ' s/d ' . ($tanggalSelesai ?: '-')]);
            fputcsv($handle, []);
            fputcsv($handle, ['No', 'Nama Barang', 'Stok Awal', 'Barang Masuk', 'Barang Keluar', 'Stok Akhir']);

            foreach ($laporan as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item['nama_barang'],
                    $item['stok_awal'],
                    $item['barang_masuk'],
                    $item['barang_keluar'],
                    $item['stok_akhir'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
