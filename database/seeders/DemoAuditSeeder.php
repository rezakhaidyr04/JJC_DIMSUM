<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\StokOpname;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoAuditSeeder extends Seeder
{
    /**
     * Seed demo data for audit trail and void workflow.
     */
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        $karyawan = User::where('role', 'karyawan')->first();
        $barang = Barang::orderBy('id')->take(3)->get();

        if (!$owner || !$karyawan || $barang->isEmpty()) {
            return;
        }

        $now = now();

        foreach ($barang as $index => $item) {
            BarangMasuk::firstOrCreate(
                [
                    'barang_id' => $item->id,
                    'user_id' => $karyawan->id,
                    'tanggal' => $now->copy()->subDays(2 + $index)->toDateString(),
                    'jumlah' => 5 + $index,
                ],
                [
                    'void_status' => 'none',
                    'created_at' => $now->copy()->subDays(2 + $index),
                    'updated_at' => $now->copy()->subDays(2 + $index),
                ]
            );

            BarangKeluar::firstOrCreate(
                [
                    'barang_id' => $item->id,
                    'user_id' => $karyawan->id,
                    'tanggal' => $now->copy()->subDays(1 + $index)->toDateString(),
                    'jumlah' => 2 + $index,
                ],
                [
                    'void_status' => 'none',
                    'created_at' => $now->copy()->subDays(1 + $index),
                    'updated_at' => $now->copy()->subDays(1 + $index),
                ]
            );

            StokOpname::updateOrCreate(
                [
                    'barang_id' => $item->id,
                    'user_id' => $karyawan->id,
                    'tanggal' => $now->toDateString(),
                ],
                [
                    'jumlah_fisik' => max(0, $item->stok - 1),
                    'jumlah_sistem' => $item->stok,
                    'selisih' => -1,
                    'status' => 'minus',
                ]
            );
        }

        $pendingMasuk = BarangMasuk::where('user_id', $karyawan->id)->latest()->first();
        if ($pendingMasuk && $pendingMasuk->void_status === 'none') {
            $pendingMasuk->update([
                'void_status' => 'pending',
                'void_reason' => 'Salah input jumlah saat pergantian shift.',
                'void_requested_by' => $karyawan->id,
                'void_requested_at' => $now->copy()->subHours(2),
                'void_approved_by' => null,
                'void_approved_at' => null,
            ]);
        }
    }
}
