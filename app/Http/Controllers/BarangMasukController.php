<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['supplier', 'user', 'details.barang']);

        if ($request->search) {
            $query->where('no_transaksi', 'like', "%{$request->search}%");
        }

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai . ' 23:59:59']);
        }

        $barangMasuks = $query->latest()->paginate(15);
        return view('transaksi.masuk.index', compact('barangMasuks'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $noTransaksi = 'BM-' . date('Ym') . '-' . sprintf('%04d', BarangMasuk::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count() + 1);

        return view('transaksi.masuk.create', compact('suppliers', 'barangs', 'noTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|unique:barang_masuks,no_transaksi',
            'tanggal' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $bm = BarangMasuk::create([
                'no_transaksi' => $request->no_transaksi,
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id() ?? 1,
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                $hargaSatuan = $item['harga_satuan'] ?? $barang->harga_masuk;
                $subtotal = $item['qty'] * $hargaSatuan;

                BarangMasukDetail::create([
                    'barang_masuk_id' => $bm->id,
                    'barang_id' => $barang->id,
                    'qty' => $item['qty'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                ]);

                // Update Stock (Tambah)
                $barang->increment('stok', $item['qty']);
            }
        });

        return redirect()->route('transaksi.masuk.index')->with('success', 'Transaksi barang masuk berhasil disimpan dan stok bertambah!');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['supplier', 'user', 'details.barang.satuan']);
        return view('transaksi.masuk.show', compact('barangMasuk'));
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        DB::transaction(function () use ($barangMasuk) {
            foreach ($barangMasuk->details as $detail) {
                // Reverse Stock
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->decrement('stok', $detail->qty);
                }
            }
            $barangMasuk->delete();
        });

        return redirect()->route('transaksi.masuk.index')->with('success', 'Transaksi barang masuk berhasil dihapus!');
    }
}
