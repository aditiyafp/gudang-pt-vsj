<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokOpname;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function tersedia(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan']);

        if ($request->search) {
            $query->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%");
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filter_stok == 'menipis') {
            $query->whereColumn('stok', '<=', 'stok_minimal');
        } elseif ($request->filter_stok == 'habis') {
            $query->where('stok', '<=', 0);
        }

        $barangs = $query->orderBy('nama_barang', 'asc')->paginate(15);
        $kategoris = Kategori::all();

        return view('stok.tersedia', compact('barangs', 'kategoris'));
    }

    public function opnameIndex()
    {
        $opnames = StokOpname::with(['barang.satuan', 'user'])->latest()->paginate(15);
        $barangs = Barang::all();
        return view('stok.opname', compact('opnames', 'barangs'));
    }

    public function opnameStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'stok_fisik' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $barang = Barang::findOrFail($request->barang_id);
            $stokSistem = $barang->stok;
            $selisih = $request->stok_fisik - $stokSistem;

            $noOpname = 'OP-' . date('Ym') . '-' . sprintf('%04d', StokOpname::count() + 1);

            StokOpname::create([
                'no_opname' => $noOpname,
                'tanggal' => $request->tanggal,
                'barang_id' => $barang->id,
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $request->stok_fisik,
                'selisih' => $selisih,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1,
            ]);

            // Adjust stock to physical stock
            $barang->update(['stok' => $request->stok_fisik]);
        });

        return redirect()->route('stok.opname')->with('success', 'Stok Opname berhasil disimpan dan stok barang disesuaikan dengan stok fisik!');
    }
}
