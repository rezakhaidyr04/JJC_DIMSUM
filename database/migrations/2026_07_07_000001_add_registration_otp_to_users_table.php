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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'registration_otp_hash')) {
                $table->string('registration_otp_hash')->nullable()->after('password');
            }

            if (! Schema::hasColumn('users', 'registration_otp_expires_at')) {
                $table->timestamp('registration_otp_expires_at')->nullable()->after('registration_otp_hash');
            }

            if (! Schema::hasColumn('users', 'registration_otp_verified_at')) {
                $table->timestamp('registration_otp_verified_at')->nullable()->after('registration_otp_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'registration_otp_verified_at')) {
                $table->dropColumn('registration_otp_verified_at');
            }

            if (Schema::hasColumn('users', 'registration_otp_expires_at')) {
                $table->dropColumn('registration_otp_expires_at');
            }

            if (Schema::hasColumn('users', 'registration_otp_hash')) {
                $table->dropColumn('registration_otp_hash');
            }
        });
    }
};