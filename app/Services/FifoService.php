<?php

namespace App\Services;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FifoService
{
    /**
     * Get FIFO candidates for a specific barang from a location
     * Returns barang masuk records ordered by tanggal_masuk (oldest first)
     *
     * @param  int  $barangId  - ID of the barang to withdraw
     * @param  int  $lokasiId  - ID of the storage location
     * @param  int  $quantity  - Quantity to withdraw
     * @return Collection - Collection of BarangMasuk records to use for FIFO
     */
    public function getFifoCandidates(int $barangId, int $lokasiId, int $quantity): Collection
    {
        $keluarSum = BarangKeluar::selectRaw('COALESCE(SUM(jumlah), 0)')
            ->whereColumn('barang_keluar.barang_masuk_id', 'barang_masuk.id_barang_masuk')
            ->whereNull('barang_keluar.deleted_at');

        $candidates = BarangMasuk::query()
            ->select('barang_masuk.*')
            ->selectSub($keluarSum, 'jumlah_keluar')
            ->where('barang_id', $barangId)
            ->where('lokasi_id', $lokasiId)
            ->whereNull('barang_masuk.deleted_at')
            ->orderBy('tanggal_masuk', 'asc')
            ->get()
            ->filter(function ($masuk) {
                return ($masuk->jumlah - (int) ($masuk->jumlah_keluar ?? 0)) > 0;
            })
            ->values();

        return $candidates;
    }

    /**
     * Calculate remaining stock for a barang_masuk record
     * = tanggal_masuk.jumlah - sum(barang_keluar where barang_masuk_id)
     */
    public function getRemainingStock(int $barangMasukId): int
    {
        $masuk = BarangMasuk::find($barangMasukId);
        if (! $masuk) {
            return 0;
        }

        $withdrawn = BarangKeluar::where('barang_masuk_id', $barangMasukId)
            ->whereNull('deleted_at')
            ->sum('jumlah');

        return $masuk->jumlah - $withdrawn;
    }

    /**
     * Get stock details for barang at a location
     * Shows breakdown by tanggal_masuk
     */
    public function getStockDetails(int $barangId, int $lokasiId): Collection
    {
        return BarangMasuk::where('barang_id', $barangId)
            ->where('lokasi_id', $lokasiId)
            ->whereNull('deleted_at')
            ->orderBy('tanggal_masuk', 'asc')
            ->get()
            ->map(function ($masuk) {
                return [
                    'id' => $masuk->id,
                    'tanggal_masuk' => $masuk->tanggal_masuk,
                    'jumlah_masuk' => $masuk->jumlah,
                    'jumlah_keluar' => BarangKeluar::where('barang_masuk_id', $masuk->getKey())
                        ->whereNull('deleted_at')
                        ->sum('jumlah'),
                    'sisa_stok' => $this->getRemainingStock($masuk->getKey()),
                ];
            });
    }

    /**
     * Create barang_keluar records following FIFO method
     * Automatically matches with oldest barang_masuk records
     *
     * @param  array  $data  - Data barang_keluar
     * @return array - Array of created BarangKeluar records
     */
    public function createFifoWithdrawal(array $data): array
    {
        $barangId = $data['barang_id'];
        $lokasiId = $data['lokasi_id'];
        $quantity = $data['jumlah'];
        $tanggalKeluar = $data['tanggal_keluar'];
        $userId = $data['user_id'];
        $cabangId = $data['cabang_id'];

        return DB::transaction(function () use ($barangId, $lokasiId, $quantity, $tanggalKeluar, $userId, $cabangId) {
            $keluarRecords = [];
            $remainingQty = $quantity;

            // Get FIFO candidates with remaining stock computed
            $candidates = $this->getFifoCandidates($barangId, $lokasiId, $quantity);

            foreach ($candidates as $masuk) {
                if ($remainingQty <= 0) {
                    break;
                }

                $availableQty = $masuk->jumlah - (int) ($masuk->jumlah_keluar ?? 0);
                $qtyToTake = min($remainingQty, $availableQty);

                $keluar = BarangKeluar::create([
                    'barang_id' => $barangId,
                    'cabang_id' => $cabangId,
                    'lokasi_id' => $lokasiId,
                    'barang_masuk_id' => $masuk->getKey(),
                    'jumlah' => $qtyToTake,
                    'tanggal_keluar' => $tanggalKeluar,
                    'user_id' => $userId,
                ]);

                $keluarRecords[] = $keluar;
                $remainingQty -= $qtyToTake;
            }

            if ($remainingQty > 0) {
                throw new \Exception("Insufficient stock for FIFO withdrawal. Remaining needed: {$remainingQty}");
            }

            return $keluarRecords;
        });
    }
}
