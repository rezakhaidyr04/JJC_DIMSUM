<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'stok',
    ];

    /**
     * Get all barang masuk for this barang
     */
    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Get all barang keluar for this barang
     */
    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Get total barang masuk
     */
    public function getTotalMasuk()
    {
        return $this->barangMasuk()->sum('jumlah');
    }

    /**
     * Get total barang keluar
     */
    public function getTotalKeluar()
    {
        return $this->barangKeluar()->sum('jumlah');
    }
}
