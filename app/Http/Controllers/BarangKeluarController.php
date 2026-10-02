<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::with(['user', 'details.barang']);

        if ($request->search) {
            $query->where('no_surat_jalan', 'like', "%{$request->search}%")
                  ->orWhere('kirim_ke', 'like', "%{$request->search}%")
                  ->orWhere('ref_no', 'like', "%{$request->search}%");
        }

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai . ' 23:59:59']);
        }

        $barangKeluars = $query->latest()->paginate(15);
        return view('transaksi.keluar.index', compact('barangKeluars'));
    }

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        $noSuratJalan = sprintf('%05d', BarangKeluar::count() + 47111);

        return view('transaksi.keluar.create', compact('barangs', 'noSuratJalan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat_jalan' => 'required|unique:barang_keluars,no_surat_jalan',
            'tanggal' => 'required|date',
            'ref_no' => 'nullable|string|max:100',
            'kirim_ke' => 'required|string|max:255',
            'halaman' => 'nullable|integer|min:1',
            'penerima' => 'nullable|string|max:100',
            'sopir' => 'nullable|string|max:100',
            'petugas_gudang' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.bekas_tukar' => 'nullable|string|max:100',
            'items.*.inventaris_status' => 'nullable|string|max:100',
            'items.*.keterangan_kondisi' => 'nullable|string',
        ]);

        // Check stock availability
        foreach ($request->items as $item) {
            $barang = Barang::findOrFail($item['barang_id']);
            if ($barang->stok < $item['qty']) {
                return back()->withInput()->with('error', "Stok barang '{$barang->nama_barang}' tidak mencukupi! Stok saat ini: {$barang->stok}, permintaan: {$item['qty']}");
            }
        }

        DB::transaction(function () use ($request) {
            $bk = BarangKeluar::create([
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal' => $request->tanggal,
                'ref_no' => $request->ref_no,
                'kirim_ke' => $request->kirim_ke,
                'halaman' => $request->halaman ?? 1,
                'penerima' => $request->penerima,
                'sopir' => $request->sopir,
                'petugas_gudang' => $request->petugas_gudang,
                'user_id' => Auth::id() ?? 1,
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                $hargaSatuan = $barang->harga_keluar;
                $subtotal = $item['qty'] * $hargaSatuan;

                BarangKeluarDetail::create([
                    'barang_keluar_id' => $bk->id,
                    'barang_id' => $barang->id,
                    'qty' => $item['qty'],
                    'bekas_tukar' => $item['bekas_tukar'] ?? null,
                    'inventaris_status' => $item['inventaris_status'] ?? null,
                    'keterangan_kondisi' => $item['keterangan_kondisi'] ?? null,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                ]);

                // Update Stock (Kurangi Stok: misal 10 dikurangi 3 menjadi 7!)
                $barang->decrement('stok', $item['qty']);
            }
        });

        return redirect()->route('transaksi.keluar.index')->with('success', 'Surat Jalan Barang Keluar berhasil dibuat dan stok barang berkurang!');
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load(['user', 'details.barang.satuan']);
        return view('transaksi.keluar.show', compact('barangKeluar'));
    }

    public function print(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load(['user', 'details.barang.satuan']);
        return view('transaksi.keluar.print', compact('barangKeluar'));
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::transaction(function () use ($barangKeluar) {
            foreach ($barangKeluar->details as $detail) {
                // Restore Stock
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->increment('stok', $detail->qty);
                }
            }
            $barangKeluar->delete();
        });

        return redirect()->route('transaksi.keluar.index')->with('success', 'Transaksi Barang Keluar berhasil dihapus dan stok dikembalikan!');
    }
}
