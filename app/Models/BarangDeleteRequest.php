<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangDeleteRequest extends Model
{
    use HasFactory;

    protected $table = 'barang_delete_requests';

    protected $fillable = [
        'barang_id',
        'barang_kode',
        'barang_nama',
        'user_id',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
