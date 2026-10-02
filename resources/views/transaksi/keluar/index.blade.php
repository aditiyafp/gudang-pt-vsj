<x-app-layout>
    <div class="space-y-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">TRANSAKSI BARANG KELUAR & SURAT JALAN</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pencatatan barang keluar/dijual yang secara otomatis mengurangi stok barang & mencetak Surat Jalan</p>
            </div>
            <a href="{{ route('transaksi.keluar.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center justify-center space-x-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Surat Jalan Barang Keluar</span>
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('transaksi.keluar.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Surat Jalan / Kirim Ke / Ref..." class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg text-xs font-bold hover:bg-blue-800 transition flex-1">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('transaksi.keluar.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300 transition flex items-center justify-center">
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
                            <th class="py-3 px-4">No. Surat Jalan</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Ref.#</th>
                            <th class="py-3 px-4">Kirim Ke</th>
                            <th class="py-3 px-4">Total Qty Keluar</th>
                            <th class="py-3 px-4 text-center">Cetak Surat Jalan</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barangKeluars as $index => $bk)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 text-slate-400 font-semibold">{{ $barangKeluars->firstItem() + $index }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-blue-700">{{ $bk->no_surat_jalan }}</td>
                                <td class="py-3 px-4 text-slate-600 font-medium">{{ $bk->tanggal->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono">{{ $bk->ref_no ?? '-' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $bk->kirim_ke }}</td>
                                <td class="py-3 px-4 font-black text-red-600">- {{ number_format($bk->details->sum('qty')) }} Pcs</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('transaksi.keluar.print', $bk->id) }}" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[11px] inline-flex items-center space-x-1 shadow-sm">
                                        <i class="fa-solid fa-print"></i>
                                        <span>Cetak Surat Jalan</span>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="{{ route('transaksi.keluar.show', $bk->id) }}" class="p-1.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-md transition" title="Detail">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('transaksi.keluar.destroy', $bk->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Surat Jalan ini? Stok barang akan dikembalikan.')">
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
                                <td colspan="8" class="py-8 text-center text-slate-400">Belum ada transaksi barang keluar / Surat Jalan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $barangKeluars->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
