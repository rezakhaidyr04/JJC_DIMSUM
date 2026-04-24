<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CabangDistribusi extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Cabang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CabangDistribusiItem::class);
    }
}
