<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cabang';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nama_cabang',
        'kode_cabang',
        'alamat',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function distribusis(): HasMany
    {
        return $this->hasMany(CabangDistribusi::class, 'cabang_id', 'id_cabang');
    }

    /**
     * Get all lokasi penyimpanan at this cabang
     */
    public function lokasiPenyimpanans(): HasMany
    {
        return $this->hasMany(LokasiPenyimpanan::class, 'cabang_id', 'id_cabang');
    }
}
