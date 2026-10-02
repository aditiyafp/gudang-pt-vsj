<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulan',
        'tahun',
        'periode',
        'tanggal_tutup',
        'closed_by',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_tutup' => 'datetime',
    ];

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function details()
    {
        return $this->hasMany(MonthlyClosingDetail::class);
    }
}
