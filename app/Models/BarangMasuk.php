<?php

namespace App\Models;

use App\Models\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class BarangMasuk extends Model
{
    use HasFactory, SoftDeletes, HasCustomId;

    protected static ?string $resolvedPrimaryKey = null;

    protected $table = 'barang_masuk';

    protected $primaryKey = 'id_barang_masuk';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'barang_id',
        'user_id',
        'cabang_id',
        'lokasi_id',
        'jumlah',
        'sumber',
        'tanggal_masuk',
        'void_status',
        'void_reason',
        'void_requested_by',
        'void_requested_at',
        'void_approved_by',
        'void_approved_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'void_requested_at' => 'datetime',
        'void_approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getKeyName(): string
    {
        if (static::$resolvedPrimaryKey !== null) {
            return static::$resolvedPrimaryKey;
        }

        static::$resolvedPrimaryKey = Schema::hasColumn($this->getTable(), 'id_barang_masuk') ? 'id_barang_masuk' : 'id';

        return static::$resolvedPrimaryKey;
    }

    /**
     * Get the barang that this entry belongs to
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Get the user who created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the cabang for this barang masuk
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id_cabang');
    }

    /**
     * Get the lokasi penyimpanan for this barang masuk
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiPenyimpanan::class, 'lokasi_id', 'id_lokasi');
    }

    /**
     * Helper: check if this masuk is Restock Manual
     */
    public function isRestockManual(): bool
    {
        return strtolower((string) $this->sumber) === 'manual';
    }

    /**
     * Human readable label for sumber
     */
    public function sumberLabel(): string
    {
        $s = strtolower((string) $this->sumber);

        return match ($s) {
            'manual' => 'Restock Manual',
            'sisa_cabang' => 'Sisa Cabang',
            default => $this->sumber ?? '-',
        };
    }

    /**
     * Get user who requested void.
     */
    public function voidRequester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'void_requested_by');
    }

    /**
     * Get owner who approved void.
     */
    public function voidApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'void_approved_by');
    }
}
