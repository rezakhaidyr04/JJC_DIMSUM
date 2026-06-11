<?php

namespace App\Models;

use App\Models\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LokasiPenyimpanan extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'lokasi_penyimpanans';

    protected $primaryKey = 'id_lokasi';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'cabang_id',
        'nama_lokasi',
        'tipe',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Get the cabang that owns this lokasi penyimpanan
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id_cabang');
    }

    /**
     * Get all barang items stored at this location
     */
    public function barangItems(): HasMany
    {
        return $this->hasMany(Barang::class, 'lokasi_default_id', 'id_lokasi');
    }

    /**
     * Scope untuk mendapatkan lokasi aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Get full location name (Cabang - Lokasi)
     */
    public function getNamaLengkapAttribute()
    {
        return "{$this->cabang->nama_cabang} - {$this->nama_lokasi}";
    }
}
