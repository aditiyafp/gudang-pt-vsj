<x-app-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">STOK TERSEDIA REALTIME</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pantau jumlah sisa stok barang kebersihan yang tersedia di gudang saat ini</p>
            </div>
            <a href="{{ route('stok.opname') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center justify-center space-x-2 transition">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Lakukan Stok Opname</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('stok.tersedia') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Barang..." class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                
                <select name="kategori_id" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <select name="filter_stok" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">-- Semua Kondisi Stok --</option>
                    <option value="menipis" {{ request('filter_stok') == 'menipis' ? 'selected' : '' }}>Stok Menipis (<= Minimal)</option>
                    <option value="habis" {{ request('filter_stok') == 'habis' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>

                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition flex-1">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('stok.tersedia') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300 transition flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Stok Cards Grid -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Nama Barang Kebersihan</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Satuan</th>
                            <th class="py-3 px-4 text-center">Stok Minimal</th>
                            <th class="py-3 px-4 text-center">Stok Tersedia</th>
                            <th class="py-3 px-4 text-center">Status Stok</th>
                            <th class="py-3 px-4 text-center">Lokasi Rak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barangs as $b)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $b->kode_barang }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $b->nama_barang }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-600 font-semibold">{{ $b->satuan->nama_satuan ?? 'Pcs' }}</td>
                                <td class="py-3 px-4 text-center font-bold text-slate-500">{{ number_format($b->stok_minimal) }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="text-base font-black {{ $b->stok <= 0 ? 'text-red-600' : ($b->stok <= $b->stok_minimal ? 'text-amber-600' : 'text-emerald-600') }}">
                                        {{ number_format($b->stok) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($b->stok <= 0)
                                        <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full font-black text-xs">STOK HABIS</span>
                                    @elseif($b->stok <= $b->stok_minimal)
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-black text-xs">MENIPIS</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-black text-xs">AMAN</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-slate-500">{{ $b->lokasi_rak ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data stok.</td>
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
