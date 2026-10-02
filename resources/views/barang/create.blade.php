<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">TAMBAH BARANG KEBERSIHAN BARU</h2>
                <p class="text-xs text-slate-500 mt-0.5">Isi data lengkap produk kebersihan untuk disimpan ke dalam sistem gudang</p>
            </div>
            <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('barang.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: SB-001 / A05" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none uppercase font-mono font-bold">
                        @error('kode_barang') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Sabun Cuci Tangan Liquid 500ml" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('nama_barang') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_id" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan <span class="text-red-500">*</span></label>
                        <select name="satuan_id" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $s)
                                <option value="{{ $s->id }}" {{ old('satuan_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_satuan }}</option>
                            @endforeach
                        </select>
                        @error('satuan_id') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Masuk / Beli (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_masuk" value="{{ old('harga_masuk', 0) }}" min="0" step="100" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('harga_masuk') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Keluar / Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_keluar" value="{{ old('harga_keluar', 0) }}" min="0" step="100" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('harga_keluar') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0" step="0.01" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-indigo-700">
                        @error('stok') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok Minimal (Alert) <span class="text-red-500">*</span></label>
                        <input type="number" name="stok_minimal" value="{{ old('stok_minimal', 5) }}" min="0" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('stok_minimal') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" value="{{ old('lokasi_rak') }}" placeholder="Contoh: Rak A-01" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan / Deskripsi</label>
                    <textarea name="keterangan" rows="3" placeholder="Catatan tambahan mengenai produk ini..." class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('keterangan') }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-2">
                    <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-xs transition">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-xs shadow transition">Simpan Barang</button>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
