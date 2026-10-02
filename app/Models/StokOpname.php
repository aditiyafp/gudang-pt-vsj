<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_opname',
        'tanggal',
        'barang_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
