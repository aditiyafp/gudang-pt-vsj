<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_keluar_id',
        'barang_id',
        'qty',
        'bekas_tukar',
        'inventaris_status',
        'keterangan_kondisi',
        'harga_satuan',
        'subtotal',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
