<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CabangDistribusi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cabang_distribusi';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'tanggal',
        'cabang_id',
        'user_id',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id_cabang');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CabangDistribusiItem::class, 'cabang_distribusi_id', 'id_cabang_distribusi');
    }
}
