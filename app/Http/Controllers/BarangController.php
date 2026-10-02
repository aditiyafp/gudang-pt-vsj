<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan']);

        if ($request->search) {
            $query->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%");
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $barangs = $query->latest()->paginate(15);
        $kategoris = Kategori::all();
        $satuans = Satuan::all();

        return view('barang.index', compact('barangs', 'kategoris', 'satuans'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        return view('barang.create', compact('kategoris', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
            'harga_masuk' => 'required|numeric|min:0',
            'harga_keluar' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'lokasi_rak' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        return view('barang.edit', compact('barang', 'kategoris', 'satuans'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
            'harga_masuk' => 'required|numeric|min:0',
            'harga_keluar' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'lokasi_rak' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }
}
