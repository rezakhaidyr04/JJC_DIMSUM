<?php

namespace Tests\Unit;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\LokasiPenyimpanan;
use App\Models\User;
use App\Services\FifoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FifoServiceInsufficientStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_fifo_throws_when_stock_is_insufficient(): void
    {
        $user = User::factory()->owner()->create();
        $cabang = Cabang::factory()->create();
        $lokasi = LokasiPenyimpanan::factory()->create(['cabang_id' => $cabang->id]);
        $barang = Barang::factory()->create();

        BarangMasuk::factory()->create([
            'barang_id' => $barang->id,
            'lokasi_id' => $lokasi->id,
            'tanggal_masuk' => '2026-05-01',
            'jumlah' => 2,
            'user_id' => $user->id,
        ]);

        $service = app(FifoService::class);

        $this->expectException(\Exception::class);
        $service->createFifoWithdrawal([
            'barang_id' => $barang->id,
            'lokasi_id' => $lokasi->id,
            'cabang_id' => $cabang->id,
            'jumlah' => 5,
            'tanggal_keluar' => '2026-05-03',
            'user_id' => $user->id,
        ]);
    }
}
