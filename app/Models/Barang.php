<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'stok',
        'cabang_id',
        'lokasi_default_id',
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
     * Get cabang distribution items related to this barang.
     */
    public function cabangDistribusiItems(): HasMany
    {
        return $this->hasMany(CabangDistribusiItem::class);
    }

    /**
     * Get the cabang this barang belongs to
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Get the default storage location for this barang
     */
    public function lokasiDefault(): BelongsTo
    {
        return $this->belongsTo(LokasiPenyimpanan::class, 'lokasi_default_id');
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

    /**
     * Scope untuk mendapatkan barang dengan stok rendah (≤ 20)
     */
    public function scopeLowStock($query, $threshold = 20)
    {
        return $query->where('stok', '<=', $threshold);
    }

    /**
     * Mendapatkan array barang dengan stok rendah untuk notifikasi
     */
    public static function getLowStockNotifications($threshold = 20)
    {
        return self::lowStock($threshold)->get()->map(function ($barang) {
            return [
                'id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'stok' => $barang->stok,
                'status' => $barang->stok == 0 ? 'habis' : 'hampir_habis',
                'pesan' => $barang->stok == 0 ? 'Stok habis' : 'Stok hampir habis'
            ];
        })->toArray();
    }
}
