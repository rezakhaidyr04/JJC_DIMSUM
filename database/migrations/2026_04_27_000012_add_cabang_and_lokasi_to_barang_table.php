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
        Schema::table('barang', function (Blueprint $table) {
            // Add cabang and lokasi references
            $table->unsignedBigInteger('cabang_id')->nullable()->after('stok');
            $table->unsignedBigInteger('lokasi_default_id')->nullable()->after('cabang_id');
            
            // Add foreign keys
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('set null');
            $table->foreign('lokasi_default_id')->references('id')->on('lokasi_penyimpanans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['cabang_id']);
            $table->dropForeignKeyIfExists(['lokasi_default_id']);
            $table->dropColumn(['cabang_id', 'lokasi_default_id']);
        });
    }
};
