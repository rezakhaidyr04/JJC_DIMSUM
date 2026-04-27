<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Cabang;
use App\Services\FifoService;
use Illuminate\View\View;

class RiwayatTransaksiController extends Controller
{
    protected $fifoService;

    public function __construct(FifoService $fifoService)
    {
        $this->fifoService = $fifoService;
    }

    /**
     * Display riwayat transaksi with filtering
     */
    public function index(): View
    {
        // Get filter parameters
        $barangId = request('barang_id');
        $cabangId = request('cabang_id');
        $tipe = request('tipe');
        $dariTanggal = request('dari_tanggal');
        $sampaiTanggal = request('sampai_tanggal');

        // Build transaction list from both barang_masuk and barang_keluar
        $transactions = $this->getFilteredTransactions(
            $barangId,
            $cabangId,
            $tipe,
            $dariTanggal,
            $sampaiTanggal
        );

        // Get lists for filter dropdown
        $barangList = Barang::orderBy('nama_barang')->get();
        $cabangList = Cabang::where('aktif', true)->orderBy('nama_cabang')->get();

        return view('riwayat-transaksi.index', [
            'transactions' => $transactions,
            'barangList' => $barangList,
            'cabangList' => $cabangList,
        ]);
    }

    /**
     * Get filtered transactions from both masuk and keluar tables
     */
    private function getFilteredTransactions($barangId, $cabangId, $tipe, $dariTanggal, $sampaiTanggal)
    {
        $transactions = [];

        // Get barang_masuk transactions
        if (!$tipe || $tipe === 'masuk') {
            $masukQuery = BarangMasuk::query()
                ->with(['barang', 'cabang', 'lokasi', 'user'])
                ->whereNull('deleted_at');

            if ($barangId) {
                $masukQuery->where('barang_id', $barangId);
            }
            if ($cabangId) {
                $masukQuery->where('cabang_id', $cabangId);
            }
            if ($dariTanggal) {
                $masukQuery->whereDate('tanggal_masuk', '>=', $dariTanggal);
            }
            if ($sampaiTanggal) {
                $masukQuery->whereDate('tanggal_masuk', '<=', $sampaiTanggal);
            }

            $masukRecords = $masukQuery->get();

            foreach ($masukRecords as $record) {
                $transactions[] = [
                    'id' => 'M-' . $record->id,
                    'tipe' => 'masuk',
                    'tanggal' => $record->created_at,
                    'barang_nama' => $record->barang->nama_barang,
                    'cabang_nama' => $record->cabang->nama_cabang ?? '-',
                    'lokasi_nama' => $record->lokasi->nama_lokasi ?? '-',
                    'jumlah' => $record->jumlah,
                    'user_name' => $record->user->name,
                    'fifo_info' => null,
                ];
            }
        }

        // Get barang_keluar transactions
        if (!$tipe || $tipe === 'keluar') {
            $keluarQuery = BarangKeluar::query()
                ->with(['barang', 'cabang', 'lokasi', 'user', 'barangMasukFifo'])
                ->whereNull('deleted_at');

            if ($barangId) {
                $keluarQuery->where('barang_id', $barangId);
            }
            if ($cabangId) {
                $keluarQuery->where('cabang_id', $cabangId);
            }
            if ($dariTanggal) {
                $keluarQuery->whereDate('tanggal_keluar', '>=', $dariTanggal);
            }
            if ($sampaiTanggal) {
                $keluarQuery->whereDate('tanggal_keluar', '<=', $sampaiTanggal);
            }

            $keluarRecords = $keluarQuery->get();

            foreach ($keluarRecords as $record) {
                // Get FIFO info
                $fifoInfo = null;
                if ($record->barangMasukFifo) {
                    $umurHari = $record->barangMasukFifo->tanggal_masuk->diffInDays(now());
                    $fifoInfo = [
                        'tanggal_masuk' => $record->barangMasukFifo->tanggal_masuk->format('d M Y'),
                        'umur_hari' => $umurHari,
                    ];
                }

                $transactions[] = [
                    'id' => 'K-' . $record->id,
                    'tipe' => 'keluar',
                    'tanggal' => $record->created_at,
                    'barang_nama' => $record->barang->nama_barang,
                    'cabang_nama' => $record->cabang->nama_cabang ?? '-',
                    'lokasi_nama' => $record->lokasi->nama_lokasi ?? '-',
                    'jumlah' => $record->jumlah,
                    'user_name' => $record->user->name,
                    'fifo_info' => $fifoInfo,
                ];
            }
        }

        // Sort by created_at descending (newest first)
        usort($transactions, function ($a, $b) {
            return $b['tanggal']->timestamp <=> $a['tanggal']->timestamp;
        });

        // Paginate manually
        $perPage = 20;
        $page = request('page', 1);
        $total = count($transactions);
        $items = array_slice($transactions, ($page - 1) * $perPage, $perPage);

        return collect($items)->paginate($perPage, ['page' => $page, 'path' => request()->url()]);
    }
}
