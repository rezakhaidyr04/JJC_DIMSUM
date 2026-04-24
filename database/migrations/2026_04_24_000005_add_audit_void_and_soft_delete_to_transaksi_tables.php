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
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('barang_id')->constrained('users')->nullOnDelete();
            $table->string('void_status', 20)->default('none')->after('tanggal');
            $table->text('void_reason')->nullable()->after('void_status');
            $table->foreignId('void_requested_by')->nullable()->after('void_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('void_requested_at')->nullable()->after('void_requested_by');
            $table->foreignId('void_approved_by')->nullable()->after('void_requested_at')->constrained('users')->nullOnDelete();
            $table->timestamp('void_approved_at')->nullable()->after('void_approved_by');
            $table->softDeletes();
            $table->index(['void_status', 'created_at']);
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('barang_id')->constrained('users')->nullOnDelete();
            $table->string('void_status', 20)->default('none')->after('tanggal');
            $table->text('void_reason')->nullable()->after('void_status');
            $table->foreignId('void_requested_by')->nullable()->after('void_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('void_requested_at')->nullable()->after('void_requested_by');
            $table->foreignId('void_approved_by')->nullable()->after('void_requested_at')->constrained('users')->nullOnDelete();
            $table->timestamp('void_approved_at')->nullable()->after('void_approved_by');
            $table->softDeletes();
            $table->index(['void_status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropIndex(['void_status', 'created_at']);
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('void_approved_by');
            $table->dropColumn('void_approved_at');
            $table->dropColumn('void_requested_at');
            $table->dropConstrainedForeignId('void_requested_by');
            $table->dropColumn('void_reason');
            $table->dropColumn('void_status');
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropIndex(['void_status', 'created_at']);
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('void_approved_by');
            $table->dropColumn('void_approved_at');
            $table->dropColumn('void_requested_at');
            $table->dropConstrainedForeignId('void_requested_by');
            $table->dropColumn('void_reason');
            $table->dropColumn('void_status');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
