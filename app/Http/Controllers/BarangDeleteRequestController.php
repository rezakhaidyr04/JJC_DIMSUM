<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDeleteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangDeleteRequestController extends Controller
{
    // Karyawan: submit request
    public function store(Request $request, Barang $barang)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        BarangDeleteRequest::create([
            'barang_id' => $barang->getKey(),
            'barang_kode' => $barang->kode_barang,
            'barang_nama' => $barang->nama_barang,
            'user_id' => Auth::id(),
            'reason' => $request->input('reason'),
        ]);

        return back()->with('success', 'Pengajuan hapus barang telah dikirim ke owner.');
    }

    // Owner: list requests
    public function index()
    {
        $requests = BarangDeleteRequest::with('barang', 'user')->orderByDesc('created_at')->get();
        return view('barang.delete_requests.index', compact('requests'));
    }

    // Owner: approve (delete barang)
    public function approve(Request $request, BarangDeleteRequest $deleteRequest)
    {
        // delete barang record (soft or hard according to existing behavior)
        $barang = $deleteRequest->barang;
        if ($barang) {
            $deleteRequest->update([
                'barang_kode' => $deleteRequest->barang_kode ?: $barang->kode_barang,
                'barang_nama' => $deleteRequest->barang_nama ?: $barang->nama_barang,
            ]);

            $barang->delete();
        }

        $deleteRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Barang dihapus dan pengajuan disetujui.');
    }

    // Owner: reject
    public function reject(Request $request, BarangDeleteRequest $deleteRequest)
    {
        $request->validate(['reason' => 'nullable|string']);

        $deleteRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}
