<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\LokasiPenyimpanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMoveOnUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_barang_masuk_update_moves_stock_to_new_barang(): void
    {
        $user = User::factory()->owner()->create();
        $cabang = Cabang::factory()->create();
        $lokasi = LokasiPenyimpanan::factory()->create(['cabang_id' => $cabang->id]);

        $barang1 = Barang::factory()->create(['stok' => 14]);
        $barang2 = Barang::factory()->create(['stok' => 5]);

        $barangMasuk = BarangMasuk::factory()->create([
            'barang_id' => $barang1->getKey(),
            'user_id' => $user->getKey(),
            'cabang_id' => $cabang->getKey(),
            'lokasi_id' => $lokasi->getKey(),
            'jumlah' => 4,
            'tanggal_masuk' => '2026-06-01',
        ]);

        $this->actingAs($user)
            ->put(route('barang-masuk.update', $barangMasuk), [
                'barang_id' => $barang2->getKey(),
                'jumlah' => 3,
                'tanggal' => '2026-06-01',
            ])
            ->assertRedirect(route('barang-masuk.index'));

        $this->assertDatabaseHas('barang', [
            'id_barang' => $barang1->getKey(),
            'stok' => 10,
        ]);

        $this->assertDatabaseHas('barang', [
            'id_barang' => $barang2->getKey(),
            'stok' => 8,
        ]);
    }

    public function test_barang_keluar_update_moves_stock_to_new_barang(): void
    {
        $user = User::factory()->owner()->create();
        $cabang = Cabang::factory()->create();
        $lokasi = LokasiPenyimpanan::factory()->create(['cabang_id' => $cabang->id]);

        $barang1 = Barang::factory()->create(['stok' => 6]);
        $barang2 = Barang::factory()->create(['stok' => 5]);

        $barangKeluar = BarangKeluar::factory()->create([
            'barang_id' => $barang1->getKey(),
            'user_id' => $user->getKey(),
            'cabang_id' => $cabang->getKey(),
            'lokasi_id' => $lokasi->getKey(),
            'jumlah' => 4,
            'tanggal_keluar' => '2026-06-01',
        ]);

        $this->actingAs($user)
            ->put(route('barang-keluar.update', $barangKeluar), [
                'barang_id' => $barang2->getKey(),
                'jumlah' => 3,
                'tanggal' => '2026-06-01',
            ])
            ->assertRedirect(route('barang-keluar.index'));

        $this->assertDatabaseHas('barang', [
            'id_barang' => $barang1->getKey(),
            'stok' => 10,
        ]);

        $this->assertDatabaseHas('barang', [
            'id_barang' => $barang2->getKey(),
            'stok' => 2,
        ]);
    }
}
