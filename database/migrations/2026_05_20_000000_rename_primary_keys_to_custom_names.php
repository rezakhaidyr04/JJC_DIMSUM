<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropForeignKeysIfExists('cabang_distribusi_items', [
            'cabang_distribusi_id',
            'barang_id',
            'barang_keluar_id',
            'barang_masuk_id',
        ]);

        $this->dropForeignKeysIfExists('cabang_distribusis', [
            'cabang_id',
            'user_id',
        ]);

        $this->dropForeignKeysIfExists('stok_opnames', [
            'barang_id',
            'user_id',
        ]);

        $this->dropForeignKeysIfExists('barang_keluar', [
            'barang_id',
            'cabang_id',
            'lokasi_id',
            'barang_masuk_id',
            'user_id',
            'void_requested_by',
            'void_approved_by',
        ]);

        $this->dropForeignKeysIfExists('barang_masuk', [
            'barang_id',
            'cabang_id',
            'lokasi_id',
            'user_id',
            'void_requested_by',
            'void_approved_by',
        ]);

        $this->dropForeignKeysIfExists('barang', [
            'cabang_id',
            'lokasi_default_id',
        ]);

        $this->dropForeignKeysIfExists('lokasi_penyimpanans', [
            'cabang_id',
        ]);

        $this->renameColumnIfExists('cabangs', 'id', 'id_cabang');
        $this->renameColumnIfExists('users', 'id', 'id_user');
        $this->renameColumnIfExists('barang', 'id', 'id_barang');
        $this->renameColumnIfExists('barang_masuk', 'id', 'id_barang_masuk');
        $this->renameColumnIfExists('barang_keluar', 'id', 'id_barang_keluar');
        $this->renameColumnIfExists('lokasi_penyimpanans', 'id', 'id_lokasi');
        $this->renameColumnIfExists('cabang_distribusis', 'id', 'id_cabang_distribusi');
        $this->renameColumnIfExists('cabang_distribusi_items', 'id', 'id_cabang_distribusi_item');
        $this->renameColumnIfExists('stok_opnames', 'id', 'id_stok_opname');

        $this->addForeignIfColumnExists('barang', 'cabang_id', 'id_cabang', 'cabangs', 'set null');
        $this->addForeignIfColumnExists('barang', 'lokasi_default_id', 'id_lokasi', 'lokasi_penyimpanans', 'set null');

        $this->addForeignIfColumnExists('barang_masuk', 'barang_id', 'id_barang', 'barang', 'cascade');
        $this->addForeignIfColumnExists('barang_masuk', 'cabang_id', 'id_cabang', 'cabangs', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'lokasi_id', 'id_lokasi', 'lokasi_penyimpanans', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'user_id', 'id_user', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'void_requested_by', 'id_user', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'void_approved_by', 'id_user', 'users', 'set null');

        $this->addForeignIfColumnExists('barang_keluar', 'barang_id', 'id_barang', 'barang', 'cascade');
        $this->addForeignIfColumnExists('barang_keluar', 'cabang_id', 'id_cabang', 'cabangs', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'lokasi_id', 'id_lokasi', 'lokasi_penyimpanans', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'barang_masuk_id', 'id_barang_masuk', 'barang_masuk', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'user_id', 'id_user', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'void_requested_by', 'id_user', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'void_approved_by', 'id_user', 'users', 'set null');

        $this->addForeignIfColumnExists('stok_opnames', 'barang_id', 'id_barang', 'barang', 'cascade');
        $this->addForeignIfColumnExists('stok_opnames', 'user_id', 'id_user', 'users', 'cascade');

        $this->addForeignIfColumnExists('lokasi_penyimpanans', 'cabang_id', 'id_cabang', 'cabangs', 'cascade');

        $this->addForeignIfColumnExists('cabang_distribusis', 'cabang_id', 'id_cabang', 'cabangs', 'cascade');
        $this->addForeignIfColumnExists('cabang_distribusis', 'user_id', 'id_user', 'users', 'cascade');

        $this->addForeignIfColumnExists('cabang_distribusi_items', 'cabang_distribusi_id', 'id_cabang_distribusi', 'cabang_distribusis', 'cascade');
        $this->addForeignIfColumnExists('cabang_distribusi_items', 'barang_id', 'id_barang', 'barang', 'cascade');
        $this->addForeignIfColumnExists('cabang_distribusi_items', 'barang_keluar_id', 'id_barang_keluar', 'barang_keluar', 'set null');
        $this->addForeignIfColumnExists('cabang_distribusi_items', 'barang_masuk_id', 'id_barang_masuk', 'barang_masuk', 'set null');
    }

    public function down(): void
    {
        $this->dropForeignKeysIfExists('cabang_distribusi_items', [
            'cabang_distribusi_id',
            'barang_id',
            'barang_keluar_id',
            'barang_masuk_id',
        ]);

        $this->dropForeignKeysIfExists('cabang_distribusis', [
            'cabang_id',
            'user_id',
        ]);

        $this->dropForeignKeysIfExists('stok_opnames', [
            'barang_id',
            'user_id',
        ]);

        $this->dropForeignKeysIfExists('barang_keluar', [
            'barang_id',
            'cabang_id',
            'lokasi_id',
            'barang_masuk_id',
            'user_id',
            'void_requested_by',
            'void_approved_by',
        ]);

        $this->dropForeignKeysIfExists('barang_masuk', [
            'barang_id',
            'cabang_id',
            'lokasi_id',
            'user_id',
            'void_requested_by',
            'void_approved_by',
        ]);

        $this->dropForeignKeysIfExists('barang', [
            'cabang_id',
            'lokasi_default_id',
        ]);

        $this->dropForeignKeysIfExists('lokasi_penyimpanans', [
            'cabang_id',
        ]);

        $this->renameColumnIfExists('cabang_distribusi_items', 'id_cabang_distribusi_item', 'id');
        $this->renameColumnIfExists('cabang_distribusis', 'id_cabang_distribusi', 'id');
        $this->renameColumnIfExists('stok_opnames', 'id_stok_opname', 'id');
        $this->renameColumnIfExists('lokasi_penyimpanans', 'id_lokasi', 'id');
        $this->renameColumnIfExists('barang_keluar', 'id_barang_keluar', 'id');
        $this->renameColumnIfExists('barang_masuk', 'id_barang_masuk', 'id');
        $this->renameColumnIfExists('barang', 'id_barang', 'id');
        $this->renameColumnIfExists('users', 'id_user', 'id');
        $this->renameColumnIfExists('cabangs', 'id_cabang', 'id');

        $this->addForeignIfColumnExists('barang', 'cabang_id', 'id', 'cabangs', 'set null');
        $this->addForeignIfColumnExists('barang', 'lokasi_default_id', 'id', 'lokasi_penyimpanans', 'set null');

        $this->addForeignIfColumnExists('barang_masuk', 'barang_id', 'id', 'barang', 'cascade');
        $this->addForeignIfColumnExists('barang_masuk', 'cabang_id', 'id', 'cabangs', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'lokasi_id', 'id', 'lokasi_penyimpanans', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'user_id', 'id', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'void_requested_by', 'id', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_masuk', 'void_approved_by', 'id', 'users', 'set null');

        $this->addForeignIfColumnExists('barang_keluar', 'barang_id', 'id', 'barang', 'cascade');
        $this->addForeignIfColumnExists('barang_keluar', 'cabang_id', 'id', 'cabangs', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'lokasi_id', 'id', 'lokasi_penyimpanans', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'barang_masuk_id', 'id', 'barang_masuk', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'user_id', 'id', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'void_requested_by', 'id', 'users', 'set null');
        $this->addForeignIfColumnExists('barang_keluar', 'void_approved_by', 'id', 'users', 'set null');

        $this->addForeignIfColumnExists('stok_opnames', 'barang_id', 'id', 'barang', 'cascade');
        $this->addForeignIfColumnExists('stok_opnames', 'user_id', 'id', 'users', 'cascade');

        $this->addForeignIfColumnExists('lokasi_penyimpanans', 'cabang_id', 'id', 'cabangs', 'cascade');

        $this->addForeignIfColumnExists('cabang_distribusis', 'cabang_id', 'id', 'cabangs', 'cascade');
        $this->addForeignIfColumnExists('cabang_distribusis', 'user_id', 'id', 'users', 'cascade');

        $this->addForeignIfColumnExists('cabang_distribusi_items', 'cabang_distribusi_id', 'id', 'cabang_distribusis', 'cascade');
        $this->addForeignIfColumnExists('cabang_distribusi_items', 'barang_id', 'id', 'barang', 'cascade');
        $this->addForeignIfColumnExists('cabang_distribusi_items', 'barang_keluar_id', 'id', 'barang_keluar', 'set null');
        $this->addForeignIfColumnExists('cabang_distribusi_items', 'barang_masuk_id', 'id', 'barang_masuk', 'set null');
    }

    private function dropForeignKeysIfExists(string $tableName, array $columns): void
    {
        foreach ($columns as $column) {
            $constraints = DB::select(
                'SELECT CONSTRAINT_NAME AS constraint_name
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = ?
                   AND COLUMN_NAME = ?
                   AND REFERENCED_TABLE_NAME IS NOT NULL',
                [$tableName, $column]
            );

            foreach ($constraints as $constraint) {
                try {
                    Schema::table($tableName, function (Blueprint $table) use ($constraint) {
                        $table->dropForeign($constraint->constraint_name);
                    });
                } catch (\Throwable $e) {
                    // Ignore missing/changed constraints so the migration can proceed on
                    // databases that already diverged slightly from the expected schema.
                }
            }
        }
    }

    private function addForeignIfColumnExists(string $tableName, string $column, string $references, string $onTable, string $onDelete): void
    {
        if (! Schema::hasColumn($tableName, $column)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($column, $references, $onTable, $onDelete) {
            $foreign = $table->foreign($column)->references($references)->on($onTable);

            match ($onDelete) {
                'cascade' => $foreign->cascadeOnDelete(),
                'set null' => $foreign->nullOnDelete(),
                default => $foreign,
            };
        });
    }

    private function renameColumnIfExists(string $tableName, string $from, string $to): void
    {
        if (! Schema::hasColumn($tableName, $from) || Schema::hasColumn($tableName, $to)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($from, $to) {
            $table->renameColumn($from, $to);
        });
    }
};