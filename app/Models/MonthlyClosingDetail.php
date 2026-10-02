<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyClosingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_closing_id',
        'barang_id',
        'stok_awal',
        'total_masuk',
        'total_keluar',
        'stok_akhir',
        'harga_satuan',
        'total_nilai',
    ];

    public function monthlyClosing()
    {
        return $this->belongsTo(MonthlyClosing::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
