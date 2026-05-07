<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\LokasiPenyimpanan;
use App\Models\User;
use App\Services\FifoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FifoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_fifo_withdrawal_consumes_oldest_stock(): void
    {
        $user = User::factory()->owner()->create();
        $cabang = Cabang::factory()->create();
        $lokasi = LokasiPenyimpanan::factory()->create(['cabang_id' => $cabang->id]);
        $barang = Barang::factory()->create();

        $masukOld = BarangMasuk::factory()->create([
            'barang_id' => $barang->id,
            'lokasi_id' => $lokasi->id,
            'tanggal_masuk' => '2026-05-01',
            'jumlah' => 5,
            'user_id' => $user->id,
        ]);

        $masukNew = BarangMasuk::factory()->create([
            'barang_id' => $barang->id,
            'lokasi_id' => $lokasi->id,
            'tanggal_masuk' => '2026-05-02',
            'jumlah' => 7,
            'user_id' => $user->id,
        ]);

        $service = app(FifoService::class);
        $records = $service->createFifoWithdrawal([
            'barang_id' => $barang->id,
            'lokasi_id' => $lokasi->id,
            'cabang_id' => $cabang->id,
            'jumlah' => 6,
            'tanggal_keluar' => '2026-05-03',
            'user_id' => $user->id,
        ]);

        $this->assertCount(2, $records);

        $first = BarangKeluar::orderBy('id')->first();
        $second = BarangKeluar::orderBy('id')->skip(1)->first();

        $this->assertSame($masukOld->id, $first->barang_masuk_id);
        $this->assertSame(5, $first->jumlah);
        $this->assertSame($masukNew->id, $second->barang_masuk_id);
        $this->assertSame(1, $second->jumlah);
    }
}
