<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'barang_id',
        'user_id',
        'jumlah',
        'tanggal',
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
        'tanggal' => 'date',
        'void_requested_at' => 'datetime',
        'void_approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the barang that this entry belongs to
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the user who created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
