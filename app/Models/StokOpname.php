<?php

namespace App\Models;

use App\Models\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokOpname extends Model
{
    use HasFactory, HasCustomId;

    protected $primaryKey = 'id_stok_opname';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'barang_id',
        'user_id',
        'tanggal',
        'jumlah_fisik',
        'jumlah_sistem',
        'selisih',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
