<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $barangMasuk = BarangMasuk::with('barang')->latest()->paginate(10);
        return view('barang_masuk.index', compact('barangMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $barang = Barang::all();
        return view('barang_masuk.create', compact('barang'));
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

        $barangMasuk = BarangMasuk::create($validated);

        // Increase barang stok
        $barang = Barang::find($validated['barang_id']);
        $barang->increment('stok', $validated['jumlah']);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk): View
    {
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk): View
    {
        $barang = Barang::all();
        return view('barang_masuk.edit', compact('barangMasuk', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Calculate the difference in jumlah
        $jumlahDiff = $validated['jumlah'] - $barangMasuk->jumlah;

        // Update barang stok based on difference
        if ($jumlahDiff != 0) {
            $barang = Barang::find($barangMasuk->barang_id);
            $barang->increment('stok', $jumlahDiff);
        }

        $barangMasuk->update($validated);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk): RedirectResponse
    {
        // Decrease barang stok
        $barang = Barang::find($barangMasuk->barang_id);
        $barang->decrement('stok', $barangMasuk->jumlah);

        $barangMasuk->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dihapus.');
    }
}
