<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokOpname;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StokOpnameController extends Controller
{
    /**
     * Show daily blind stock opname form for karyawan.
     */
    public function index(): View
    {
        $barangList = Barang::orderBy('nama_barang')->get(['id', 'nama_barang']);

        $todayOpname = StokOpname::with('barang')
            ->whereDate('tanggal', now()->toDateString())
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stok_opname.index', [
            'barangList' => $barangList,
            'todayOpname' => $todayOpname,
        ]);
    }

    /**
     * Store daily blind stock opname input.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'opname' => ['required', 'array', 'min:1'],
            'opname.*.barang_id' => ['required', 'exists:barang,id'],
            'opname.*.jumlah_fisik' => ['required', 'integer', 'min:0'],
        ]);

        $tanggal = now()->toDateString();
        $userId = auth()->id();

        DB::transaction(function () use ($validated, $tanggal, $userId) {
            foreach ($validated['opname'] as $entry) {
                $barang = Barang::findOrFail($entry['barang_id']);

                $jumlahFisik = (int) $entry['jumlah_fisik'];
                $jumlahSistem = (int) $barang->stok;
                $selisih = $jumlahFisik - $jumlahSistem;
                $status = match (true) {
                    $selisih > 0 => 'surplus',
                    $selisih < 0 => 'minus',
                    default => 'match',
                };

                StokOpname::updateOrCreate(
                    [
                        'barang_id' => $barang->id,
                        'user_id' => $userId,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'jumlah_fisik' => $jumlahFisik,
                        'jumlah_sistem' => $jumlahSistem,
                        'selisih' => $selisih,
                        'status' => $status,
                    ]
                );
            }
        });

        return redirect()
            ->route('stok-opname.index')
            ->with('success', 'Stok opname harian berhasil disimpan. Sistem otomatis menandai jika ada selisih.');
    }
}
