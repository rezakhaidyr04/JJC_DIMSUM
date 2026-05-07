<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\CabangDistribusi;
use App\Models\CabangDistribusiItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StokOpnameFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_karyawan_can_submit_pagi_and_malam_flow(): void
    {
        $karyawan = User::factory()->karyawan()->create();
        $cabang = Cabang::factory()->create();
        $barang = Barang::factory()->create();

        $tanggal = '2026-05-07';

        $this->actingAs($karyawan)
            ->post('/stok-opname-harian/pagi', [
                'cabang_id' => $cabang->id,
                'tanggal' => $tanggal,
                'catatan' => 'Test pagi',
                'berangkat' => [
                    [
                        'barang_id' => $barang->id,
                        'jumlah_bawa' => 10,
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cabang_distribusis', [
            'cabang_id' => $cabang->id,
            'user_id' => $karyawan->id,
            'tanggal' => $tanggal,
        ]);

        $this->assertDatabaseHas('barang_keluar', [
            'barang_id' => $barang->id,
            'jumlah' => 10,
        ]);

        $header = CabangDistribusi::first();
        $item = CabangDistribusiItem::where('cabang_distribusi_id', $header->id)
            ->where('barang_id', $barang->id)
            ->first();

        $this->assertNotNull($item);
        $this->assertSame(10, $item->jumlah_bawa);

        $this->actingAs($karyawan)
            ->post('/stok-opname-harian/malam', [
                'cabang_id' => $cabang->id,
                'tanggal' => $tanggal,
                'sisa' => [
                    [
                        'barang_id' => $barang->id,
                        'jumlah_sisa' => 3,
                    ],
                ],
            ])
            ->assertRedirect();

        $item->refresh();
        $this->assertSame(3, $item->jumlah_sisa);
        $this->assertSame(7, $item->jumlah_terpakai);

        $this->assertDatabaseHas('barang_masuk', [
            'barang_id' => $barang->id,
            'jumlah' => 3,
            'sumber' => 'sisa_cabang',
        ]);

        $this->assertTrue(BarangKeluar::count() >= 1);
        $this->assertTrue(BarangMasuk::count() >= 1);
    }
}
