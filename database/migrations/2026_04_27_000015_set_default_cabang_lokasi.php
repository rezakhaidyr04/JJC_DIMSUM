<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default cabang untuk existing barang
        $defaultCabangId = DB::table('cabangs')->first()?->id;
        if ($defaultCabangId) {
            DB::table('barang')->whereNull('cabang_id')->update(['cabang_id' => $defaultCabangId]);
        }

        // Set default lokasi untuk existing barang
        $defaultLokasiId = DB::table('lokasi_penyimpanans')->first()?->id;
        if ($defaultLokasiId) {
            DB::table('barang')->whereNull('lokasi_default_id')->update(['lokasi_default_id' => $defaultLokasiId]);
        }

        // Set default cabang & lokasi untuk existing barang_masuk
        if ($defaultCabangId) {
            DB::table('barang_masuk')->whereNull('cabang_id')->update(['cabang_id' => $defaultCabangId]);
        }
        if ($defaultLokasiId) {
            DB::table('barang_masuk')->whereNull('lokasi_id')->update(['lokasi_id' => $defaultLokasiId]);
        }

        // Set default cabang & lokasi untuk existing barang_keluar
        if ($defaultCabangId) {
            DB::table('barang_keluar')->whereNull('cabang_id')->update(['cabang_id' => $defaultCabangId]);
        }
        if ($defaultLokasiId) {
            DB::table('barang_keluar')->whereNull('lokasi_id')->update(['lokasi_id' => $defaultLokasiId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset to null
        DB::table('barang')->update(['cabang_id' => null, 'lokasi_default_id' => null]);
        DB::table('barang_masuk')->update(['cabang_id' => null, 'lokasi_id' => null]);
        DB::table('barang_keluar')->update(['cabang_id' => null, 'lokasi_id' => null]);
    }
};
