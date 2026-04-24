<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $barangKeluar = BarangKeluar::with(['barang', 'user', 'voidRequester'])
            ->latest()
            ->paginate(10);

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

        $validated['user_id'] = auth()->id();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        $validated['void_status'] = 'none';

        BarangKeluar::create($validated);

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
        $this->authorize('update', $barangKeluar);

        if ($barangKeluar->void_status === 'pending') {
            return redirect()->route('barang-keluar.index')->withErrors([
                'void' => 'Data dengan status Pending Void tidak dapat diubah.',
            ]);
        }

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
        $this->authorize('delete', $barangKeluar);

        if ($barangKeluar->void_status === 'pending') {
            return redirect()->route('barang-keluar.index')->withErrors([
                'void' => 'Data Pending Void hanya boleh diproses melalui persetujuan void.',
            ]);
        }

        // Increase barang stok
        $barang = Barang::find($barangKeluar->barang_id);
        $barang->increment('stok', $barangKeluar->jumlah);

        $barangKeluar->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dihapus.');
    }

    /**
     * Karyawan request void untuk transaksi yang salah input.
     */
    public function requestVoid(Request $request, BarangKeluar $barangKeluar): RedirectResponse
    {
        $this->authorize('requestVoid', $barangKeluar);

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $barangKeluar->update([
            'void_status' => 'pending',
            'void_reason' => $validated['void_reason'],
            'void_requested_by' => auth()->id(),
            'void_requested_at' => now(),
            'void_approved_by' => null,
            'void_approved_at' => null,
        ]);

        return redirect()->route('barang-keluar.index')->with('success', 'Request void berhasil dikirim ke owner.');
    }

    /**
     * Owner menyetujui void lalu transaksi dihapus (soft delete) dan stok dikoreksi.
     */
    public function approveVoid(BarangKeluar $barangKeluar): RedirectResponse
    {
        $this->authorize('approveVoid', $barangKeluar);

        DB::transaction(function () use ($barangKeluar) {
            $barang = Barang::find($barangKeluar->barang_id);
            $barang->increment('stok', $barangKeluar->jumlah);

            $barangKeluar->update([
                'void_status' => 'approved',
                'void_approved_by' => auth()->id(),
                'void_approved_at' => now(),
            ]);

            $barangKeluar->delete();
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Void disetujui. Data transaksi berhasil dihapus.');
    }
}
