<?php

namespace App\Models;

use App\Models\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CabangDistribusiItem extends Model
{
    use HasFactory, HasCustomId;

    protected $primaryKey = 'id_cabang_distribusi_item';

    public $incrementing = true;

    protected $keyType = 'int';

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
        return $this->belongsTo(CabangDistribusi::class, 'cabang_distribusi_id', 'id_cabang_distribusi');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function barangKeluar(): BelongsTo
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id', 'id_barang_keluar');
    }

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id', 'id_barang_masuk');
    }
}
