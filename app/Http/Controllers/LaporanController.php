<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Display stock report
     */
    public function index(): View
    {
        $barang = Barang::with(['barangMasuk', 'barangKeluar'])->get();

        $laporan = $barang->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'stok_awal' => 0, // Can be calculated based on first transaction
                'barang_masuk' => $item->getTotalMasuk(),
                'barang_keluar' => $item->getTotalKeluar(),
                'stok_akhir' => $item->stok,
            ];
        });

        return view('laporan.index', compact('laporan'));
    }
}
