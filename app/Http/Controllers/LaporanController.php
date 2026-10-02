<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluarDetail;
use App\Models\MonthlyClosing;
use App\Models\MonthlyClosingDetail;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function transaksi(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $jenis = $request->jenis ?? 'semua'; // masuk, keluar, semua

        $barangMasuks = collect();
        $barangKeluars = collect();

        if ($jenis == 'semua' || $jenis == 'masuk') {
            $bmQuery = BarangMasuk::with(['supplier', 'user', 'details.barang.satuan'])
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
            if ($request->tanggal_awal && $request->tanggal_akhir) {
                $bmQuery->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir . ' 23:59:59']);
            }
            $barangMasuks = $bmQuery->latest()->get();
        }

        if ($jenis == 'semua' || $jenis == 'keluar') {
            $bkQuery = BarangKeluar::with(['user', 'details.barang.satuan'])
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
            if ($request->tanggal_awal && $request->tanggal_akhir) {
                $bkQuery->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir . ' 23:59:59']);
            }
            $barangKeluars = $bkQuery->latest()->get();
        }

        return view('laporan.transaksi', compact('barangMasuks', 'barangKeluars', 'bulan', 'tahun', 'jenis'));
    }

    public function stok(Request $request)
    {
        $bulan = (int) ($request->bulan ?? date('m'));
        $tahun = (int) ($request->tahun ?? date('Y'));
        $periode = sprintf('%04d-%02d', $tahun, $bulan);

        $closing = MonthlyClosing::with('details.barang.satuan', 'closedBy')->where('periode', $periode)->first();
        $isClosed = $closing && $closing->status === 'CLOSED';

        $reportData = [];

        if ($isClosed) {
            // Load frozen snapshot
            foreach ($closing->details as $detail) {
                $reportData[] = [
                    'barang' => $detail->barang,
                    'stok_awal' => $detail->stok_awal,
                    'total_masuk' => $detail->total_masuk,
                    'total_keluar' => $detail->total_keluar,
                    'stok_akhir' => $detail->stok_akhir,
                    'harga_satuan' => $detail->harga_satuan,
                    'total_nilai' => $detail->total_nilai,
                ];
            }
        } else {
            // Calculate realtime monthly metrics
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

            // Previous month period
            $prevDate = $startDate->copy()->subMonth();
            $prevPeriode = $prevDate->format('Y-m');
            $prevClosing = MonthlyClosing::with('details')->where('periode', $prevPeriode)->first();

            $barangs = Barang::with(['kategori', 'satuan'])->get();

            foreach ($barangs as $barang) {
                // Determine stok_awal
                $stokAwal = 0;
                if ($prevClosing && $prevClosing->status === 'CLOSED') {
                    $prevDetail = $prevClosing->details->where('barang_id', $barang->id)->first();
                    $stokAwal = $prevDetail ? $prevDetail->stok_akhir : 0;
                } else {
                    // Total transactions prior to start Date
                    $masukSebelumnya = BarangMasukDetail::whereHas('barangMasuk', function ($q) use ($startDate) {
                        $q->where('tanggal', '<', $startDate);
                    })->where('barang_id', $barang->id)->sum('qty');

                    $keluarSebelumnya = BarangKeluarDetail::whereHas('barangKeluar', function ($q) use ($startDate) {
                        $q->where('tanggal', '<', $startDate);
                    })->where('barang_id', $barang->id)->sum('qty');

                    $stokAwal = max(0, $masukSebelumnya - $keluarSebelumnya);
                }

                // Total Masuk in current month
                $totalMasuk = BarangMasukDetail::whereHas('barangMasuk', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                })->where('barang_id', $barang->id)->sum('qty');

                // Total Keluar in current month
                $totalKeluar = BarangKeluarDetail::whereHas('barangKeluar', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                })->where('barang_id', $barang->id)->sum('qty');

                $stokAkhir = $stokAwal + $totalMasuk - $totalKeluar;
                $hargaSatuan = $barang->harga_keluar;
                $totalNilai = $stokAkhir * $hargaSatuan;

                $reportData[] = [
                    'barang' => $barang,
                    'stok_awal' => $stokAwal,
                    'total_masuk' => $totalMasuk,
                    'total_keluar' => $totalKeluar,
                    'stok_akhir' => $stokAkhir,
                    'harga_satuan' => $hargaSatuan,
                    'total_nilai' => $totalNilai,
                ];
            }
        }

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        return view('laporan.stok', compact('reportData', 'bulan', 'tahun', 'namaBulan', 'periode', 'closing', 'isClosed'));
    }

    public function closeMonthlyReport(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'catatan' => 'nullable|string',
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;
        $periode = sprintf('%04d-%02d', $tahun, $bulan);

        DB::transaction(function () use ($bulan, $tahun, $periode, $request) {
            $closing = MonthlyClosing::updateOrCreate(
                ['periode' => $periode],
                [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal_tutup' => Carbon::now(),
                    'closed_by' => Auth::id() ?? 1,
                    'status' => 'CLOSED',
                    'catatan' => $request->catatan,
                ]
            );

            // Clear old details if any
            $closing->details()->delete();

            // Calculate metrics for snapshot
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

            $prevDate = $startDate->copy()->subMonth();
            $prevPeriode = $prevDate->format('Y-m');
            $prevClosing = MonthlyClosing::with('details')->where('periode', $prevPeriode)->first();

            $barangs = Barang::all();

            foreach ($barangs as $barang) {
                $stokAwal = 0;
                if ($prevClosing && $prevClosing->status === 'CLOSED') {
                    $prevDetail = $prevClosing->details->where('barang_id', $barang->id)->first();
                    $stokAwal = $prevDetail ? $prevDetail->stok_akhir : 0;
                } else {
                    $masukSebelumnya = BarangMasukDetail::whereHas('barangMasuk', function ($q) use ($startDate) {
                        $q->where('tanggal', '<', $startDate);
                    })->where('barang_id', $barang->id)->sum('qty');

                    $keluarSebelumnya = BarangKeluarDetail::whereHas('barangKeluar', function ($q) use ($startDate) {
                        $q->where('tanggal', '<', $startDate);
                    })->where('barang_id', $barang->id)->sum('qty');

                    $stokAwal = max(0, $masukSebelumnya - $keluarSebelumnya);
                }

                $totalMasuk = BarangMasukDetail::whereHas('barangMasuk', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                })->where('barang_id', $barang->id)->sum('qty');

                $totalKeluar = BarangKeluarDetail::whereHas('barangKeluar', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                })->where('barang_id', $barang->id)->sum('qty');

                $stokAkhir = $stokAwal + $totalMasuk - $totalKeluar;
                $hargaSatuan = $barang->harga_keluar;
                $totalNilai = $stokAkhir * $hargaSatuan;

                MonthlyClosingDetail::create([
                    'monthly_closing_id' => $closing->id,
                    'barang_id' => $barang->id,
                    'stok_awal' => $stokAwal,
                    'total_masuk' => $totalMasuk,
                    'total_keluar' => $totalKeluar,
                    'stok_akhir' => $stokAkhir,
                    'harga_satuan' => $hargaSatuan,
                    'total_nilai' => $totalNilai,
                ]);
            }
        });

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        return redirect()->route('laporan.stok', ['bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', "Laporan bulan {$namaBulan} {$tahun} berhasil DITUTUP dan dibekukan sebagai Laporan Bulanan Resmi PT Gudang Kebersihan VSJ!");
    }

    public function reopenMonthlyReport(Request $request)
    {
        $request->validate([
            'periode' => 'required|string',
        ]);

        $closing = MonthlyClosing::where('periode', $request->periode)->first();
        if ($closing) {
            $closing->update(['status' => 'OPEN']);
        }

        return back()->with('success', 'Laporan bulanan berhasil dibuka kembali untuk pengeditan.');
    }
}
