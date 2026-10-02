<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat_jalan',
        'tanggal',
        'ref_no',
        'kirim_ke',
        'halaman',
        'penerima',
        'sopir',
        'petugas_gudang',
        'user_id',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(BarangKeluarDetail::class);
    }
}
