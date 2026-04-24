<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CabangDistribusiItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabang_distribusi_id',
        'barang_id',
        'jumlah_bawa',
        'jumlah_sisa',
        'jumlah_terpakai',
        'barang_keluar_id',
        'barang_masuk_id',
    ];

    public function distribusi(): BelongsTo
    {
        return $this->belongsTo(CabangDistribusi::class, 'cabang_distribusi_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function barangKeluar(): BelongsTo
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}
