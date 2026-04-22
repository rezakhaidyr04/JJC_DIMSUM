<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $barangKeluar = BarangKeluar::with('barang')->latest()->paginate(10);
        return view('barang_keluar.index', compact('barangKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $barang = Barang::all();
        return view('barang_keluar.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barangKeluar = BarangKeluar::create($validated);

        // Decrease barang stok
        $barang = Barang::find($validated['barang_id']);
        $barang->decrement('stok', $validated['jumlah']);

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar): View
    {
        return view('barang_keluar.show', compact('barangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barangKeluar): View
    {
        $barang = Barang::all();
        return view('barang_keluar.edit', compact('barangKeluar', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangKeluar $barangKeluar): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Calculate the difference in jumlah
        $jumlahDiff = $validated['jumlah'] - $barangKeluar->jumlah;

        // Update barang stok based on difference
        if ($jumlahDiff != 0) {
            $barang = Barang::find($barangKeluar->barang_id);
            $barang->decrement('stok', $jumlahDiff);
        }

        $barangKeluar->update($validated);

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar): RedirectResponse
    {
        // Increase barang stok
        $barang = Barang::find($barangKeluar->barang_id);
        $barang->increment('stok', $barangKeluar->jumlah);

        $barangKeluar->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dihapus.');
    }
}
