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
        if (! Schema::hasColumn('barang_masuk', 'sumber')) {
            Schema::table('barang_masuk', function (Blueprint $table) {
                $table->string('sumber', 30)->default('manual')->after('tanggal_masuk');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('barang_masuk', 'sumber')) {
            Schema::table('barang_masuk', function (Blueprint $table) {
                $table->dropColumn('sumber');
            });
        }
    }
};
