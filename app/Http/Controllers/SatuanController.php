<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::latest()->paginate(15);
        return view('satuan.index', compact('satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:satuans,nama_satuan',
            'keterangan' => 'nullable|string',
        ]);

        Satuan::create($request->all());

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan!');
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:satuans,nama_satuan,' . $satuan->id,
            'keterangan' => 'nullable|string',
        ]);

        $satuan->update($request->all());

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui!');
    }

    public function destroy(Satuan $satuan)
    {
        $satuan->delete();
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus!');
    }
}
