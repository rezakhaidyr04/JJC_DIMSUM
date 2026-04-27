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
        Schema::create('lokasi_penyimpanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cabang_id');
            $table->string('nama_lokasi', 100); // Gudang 1, Rak A1, dll
            $table->enum('tipe', ['gudang', 'rak', 'custom'])->default('custom');
            $table->string('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
            $table->unique(['cabang_id', 'nama_lokasi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_penyimpanans');
    }
};
