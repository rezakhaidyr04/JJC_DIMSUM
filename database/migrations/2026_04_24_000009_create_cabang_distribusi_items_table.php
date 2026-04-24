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
        Schema::create('cabang_distribusi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_distribusi_id')->constrained('cabang_distribusis')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_bawa');
            $table->unsignedInteger('jumlah_sisa');
            $table->unsignedInteger('jumlah_terpakai');
            $table->foreignId('barang_keluar_id')->nullable()->constrained('barang_keluar')->nullOnDelete();
            $table->foreignId('barang_masuk_id')->nullable()->constrained('barang_masuk')->nullOnDelete();
            $table->timestamps();

            $table->index(['barang_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang_distribusi_items');
    }
};
