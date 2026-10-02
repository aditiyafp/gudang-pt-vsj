<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">EDIT BARANG KEBERSIHAN</h2>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui data produk {{ $barang->nama_barang }} ({{ $barang->kode_barang }})</p>
            </div>
            <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('barang.update', $barang->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none uppercase font-mono font-bold">
                        @error('kode_barang') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('nama_barang') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_id" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan <span class="text-red-500">*</span></label>
                        <select name="satuan_id" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            @foreach($satuans as $s)
                                <option value="{{ $s->id }}" {{ old('satuan_id', $barang->satuan_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Masuk / Beli (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_masuk" value="{{ old('harga_masuk', $barang->harga_masuk) }}" min="0" step="100" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Keluar / Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_keluar" value="{{ old('harga_keluar', $barang->harga_keluar) }}" min="0" step="100" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}" min="0" step="0.01" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-indigo-700">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok Minimal (Alert) <span class="text-red-500">*</span></label>
                        <input type="number" name="stok_minimal" value="{{ old('stok_minimal', $barang->stok_minimal) }}" min="0" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" value="{{ old('lokasi_rak', $barang->lokasi_rak) }}" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan / Deskripsi</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('keterangan', $barang->keterangan) }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-2">
                    <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-xs transition">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-xs shadow transition">Update Barang</button>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
