<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\View\View;

class VoidRequestController extends Controller
{
    /**
     * Show pending void requests for owner approval.
     */
    public function index(): View
    {
        $pendingMasuk = BarangMasuk::with(['barang', 'user', 'voidRequester'])
            ->where('void_status', 'pending')
            ->latest('void_requested_at')
            ->paginate(10, ['*'], 'masuk_page');

        $pendingKeluar = BarangKeluar::with(['barang', 'user', 'voidRequester'])
            ->where('void_status', 'pending')
            ->latest('void_requested_at')
            ->paginate(10, ['*'], 'keluar_page');

        return view('void_requests.index', [
            'pendingMasuk' => $pendingMasuk,
            'pendingKeluar' => $pendingKeluar,
        ]);
    }
}
