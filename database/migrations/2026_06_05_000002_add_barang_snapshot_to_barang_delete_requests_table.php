<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_delete_requests', function (Blueprint $table) {
            $table->string('barang_kode')->nullable()->after('barang_id');
            $table->string('barang_nama')->nullable()->after('barang_kode');
        });
    }

    public function down(): void
    {
        Schema::table('barang_delete_requests', function (Blueprint $table) {
            $table->dropColumn(['barang_kode', 'barang_nama']);
        });
    }
};
