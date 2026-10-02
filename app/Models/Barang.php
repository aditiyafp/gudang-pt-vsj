<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan_id',
        'harga_masuk',
        'harga_keluar',
        'stok',
        'stok_minimal',
        'lokasi_rak',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function barangMasukDetails()
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    public function barangKeluarDetails()
    {
        return $this->hasMany(BarangKeluarDetail::class);
    }

    public function stokOpnames()
    {
        return $this->hasMany(StokOpname::class);
    }
}
