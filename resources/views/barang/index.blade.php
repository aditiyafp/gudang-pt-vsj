<x-app-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">DATA BARANG KEBERSIHAN</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola daftar produk, stok awal, harga masuk (beli) dan harga keluar (jual)</p>
            </div>
            <a href="{{ route('barang.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center justify-center space-x-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Barang Baru</span>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('barang.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Barang..." class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                
                <select name="kategori_id" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition flex-1">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('barang.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300 transition flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Kode Barang</th>
                            <th class="py-3 px-4">Nama Barang</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Satuan</th>
                            <th class="py-3 px-4 text-right">Harga Masuk</th>
                            <th class="py-3 px-4 text-right">Harga Keluar</th>
                            <th class="py-3 px-4 text-center">Stok</th>
                            <th class="py-3 px-4 text-center">Lokasi Rak</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barangs as $index => $b)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 text-slate-400 font-semibold">{{ $barangs->firstItem() + $index }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $b->kode_barang }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $b->nama_barang }}</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md font-semibold">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                                <td class="py-3 px-4 font-medium text-slate-600">{{ $b->satuan->nama_satuan ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-medium text-slate-600">Rp {{ number_format($b->harga_masuk, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right font-bold text-emerald-600">Rp {{ number_format($b->harga_keluar, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if($b->stok <= 0)
                                        <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full font-black text-xs">0 (Habis)</span>
                                    @elseif($b->stok <= $b->stok_minimal)
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-black text-xs">{{ number_format($b->stok) }} (Menipis)</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-black text-xs">{{ number_format($b->stok) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center text-slate-500 font-medium">{{ $b->lokasi_rak ?? '-' }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="{{ route('barang.edit', $b->id) }}" class="p-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-md transition" title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('barang.destroy', $b->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-md transition" title="Hapus">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400">Tidak ada data barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $barangs->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
