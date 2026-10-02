<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Supplier;
use App\Models\MonthlyClosing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalSupplier = Supplier::count();
        $totalMasukBulanIni = BarangMasuk::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();
        $totalKeluarBulanIni = BarangKeluar::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();
        
        $barangStokMenipis = Barang::whereColumn('stok', '<=', 'stok_minimal')->get();
        $barangTerbaru = Barang::with(['kategori', 'satuan'])->latest()->take(5)->get();

        $recentBarangKeluar = BarangKeluar::with('details.barang')->latest()->take(5)->get();
        
        $currentClosing = MonthlyClosing::where('periode', Carbon::now()->format('Y-m'))->first();

        return view('dashboard', compact(
            'totalBarang',
            'totalSupplier',
            'totalMasukBulanIni',
            'totalKeluarBulanIni',
            'barangStokMenipis',
            'barangTerbaru',
            'recentBarangKeluar',
            'currentClosing'
        ));
    }
}
