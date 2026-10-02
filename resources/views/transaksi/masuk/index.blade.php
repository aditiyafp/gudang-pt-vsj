<x-app-layout>
    <div class="space-y-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">TRANSAKSI BARANG MASUK</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pencatatan penerimaan barang dari supplier untuk menambah stok gudang</p>
            </div>
            <a href="{{ route('transaksi.masuk.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center justify-center space-x-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Catat Barang Masuk</span>
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('transaksi.masuk.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Transaksi..." class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-emerald-700 text-white rounded-lg text-xs font-bold hover:bg-emerald-800 transition flex-1">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('transaksi.masuk.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300 transition flex items-center justify-center">
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
                            <th class="py-3 px-4">No Transaksi</th>
                            <th class="py-3 px-4">Tanggal & Waktu</th>
                            <th class="py-3 px-4">Supplier</th>
                            <th class="py-3 px-4">Jumlah Item</th>
                            <th class="py-3 px-4">Total Qty</th>
                            <th class="py-3 px-4">Petugas Input</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barangMasuks as $index => $bm)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 text-slate-400 font-semibold">{{ $barangMasuks->firstItem() + $index }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-700">{{ $bm->no_transaksi }}</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">{{ $bm->tanggal->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $bm->supplier->nama_supplier ?? 'Tanpa Supplier' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-700">{{ $bm->details->count() }} Jenis</td>
                                <td class="py-3 px-4 font-black text-emerald-600">+ {{ number_format($bm->details->sum('qty')) }} Pcs</td>
                                <td class="py-3 px-4 text-slate-500">{{ $bm->user->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="{{ route('transaksi.masuk.show', $bm->id) }}" class="p-1.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-md transition" title="Detail">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('transaksi.masuk.destroy', $bm->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikurangi kembali.')">
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
                                <td colspan="8" class="py-8 text-center text-slate-400">Belum ada transaksi barang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $barangMasuks->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
