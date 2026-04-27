<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            // Add cabang and lokasi tracking
            $table->unsignedBigInteger('cabang_id')->nullable()->after('barang_id');
            $table->unsignedBigInteger('lokasi_id')->nullable()->after('cabang_id');
            
            // Add FIFO reference - link to BarangMasuk record used for FIFO
            $table->unsignedBigInteger('barang_masuk_id')->nullable()->after('lokasi_id');
            
            // Add foreign keys
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('set null');
            $table->foreign('lokasi_id')->references('id')->on('lokasi_penyimpanans')->onDelete('set null');
            $table->foreign('barang_masuk_id')->references('id')->on('barang_masuk')->onDelete('set null');
            
            // Rename tanggal to tanggal_keluar for clarity
            $table->renameColumn('tanggal', 'tanggal_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->renameColumn('tanggal_keluar', 'tanggal');
            $table->dropForeignKeyIfExists(['cabang_id']);
            $table->dropForeignKeyIfExists(['lokasi_id']);
            $table->dropForeignKeyIfExists(['barang_masuk_id']);
            $table->dropColumn(['cabang_id', 'lokasi_id', 'barang_masuk_id']);
        });
    }
};
