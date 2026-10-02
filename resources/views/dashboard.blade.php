<x-app-layout>
    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-blue-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <span class="px-3 py-1 bg-indigo-700/50 backdrop-blur text-indigo-200 rounded-full text-xs font-semibold uppercase tracking-wider">Dashboard Sistem Gudang</span>
                <h2 class="text-2xl md:text-3xl font-extrabold mt-2">Selamat Datang di PT GUDANG KEBERSIHAN VSJ</h2>
                <p class="text-indigo-200 text-sm mt-1 max-w-2xl">
                    Kelola stok barang kebersihan, transaksi barang masuk, barang keluar, pengerluaran Surat Jalan, serta laporan bulanan dengan cepat & terintegrasi.
                </p>
            </div>
            <div class="absolute -right-8 -bottom-8 opacity-10 text-white">
                <i class="fa-solid fa-warehouse text-9xl"></i>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Total Barang -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Barang</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($totalBarang) }}</h3>
                        <p class="text-xs text-indigo-600 font-semibold mt-1"><i class="fa-solid fa-boxes-stacked mr-1"></i> Jenis Produk</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-box font-bold"></i>
                    </div>
                </div>
            </div>

            <!-- Barang Masuk Bulan Ini -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Barang Masuk (Bulan Ini)</p>
                        <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalMasukBulanIni) }}</h3>
                        <p class="text-xs text-emerald-600 font-semibold mt-1"><i class="fa-solid fa-arrow-down mr-1"></i> Transaksi Masuk</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                    </div>
                </div>
            </div>

            <!-- Barang Keluar Bulan Ini -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Barang Keluar / SJ (Bulan Ini)</p>
                        <h3 class="text-2xl font-black text-blue-600 mt-1">{{ number_format($totalKeluarBulanIni) }}</h3>
                        <p class="text-xs text-blue-600 font-semibold mt-1"><i class="fa-solid fa-file-invoice mr-1"></i> Surat Jalan Keluar</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                </div>
            </div>

            <!-- Status Laporan Bulan Ini -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan {{ date('F Y') }}</p>
                        @if($currentClosing && $currentClosing->status === 'CLOSED')
                            <span class="inline-block mt-2 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold">
                                <i class="fa-solid fa-lock mr-1"></i> DITUTUP (RESMI)
                            </span>
                        @else
                            <span class="inline-block mt-2 px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-extrabold">
                                <i class="fa-solid fa-lock-open mr-1"></i> MASIH TERBUKA
                            </span>
                        @endif
                        <p class="text-[11px] text-slate-500 mt-1">Status Laporan Stok</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Middle Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Barang Keluar & Surat Jalan Terbaru (2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 text-base flex items-center">
                        <i class="fa-solid fa-file-invoice text-blue-600 mr-2"></i> Transaksi Surat Jalan Barang Keluar Terbaru
                    </h3>
                    <a href="{{ route('transaksi.keluar.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Lihat Semua &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-y border-slate-200">
                                <th class="py-2.5 px-3">No. Surat Jalan</th>
                                <th class="py-2.5 px-3">Tanggal</th>
                                <th class="py-2.5 px-3">Kirim Ke</th>
                                <th class="py-2.5 px-3">Total Qty</th>
                                <th class="py-2.5 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentBarangKeluar as $bk)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2.5 px-3 font-bold text-blue-700">{{ $bk->no_surat_jalan }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $bk->tanggal->format('d/m/Y H:i') }}</td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-800">{{ $bk->kirim_ke }}</td>
                                    <td class="py-2.5 px-3 font-bold text-slate-700">{{ number_format($bk->details->sum('qty')) }} Pcs</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <a href="{{ route('transaksi.keluar.print', $bk->id) }}" target="_blank" class="px-2.5 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-bold text-[11px] inline-flex items-center">
                                            <i class="fa-solid fa-print mr-1"></i> Surat Jalan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">Belum ada transaksi barang keluar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stok Menipis Warning (1 col) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 text-base flex items-center">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-2"></i> Stok Menipis / Perlu Restok
                    </h3>
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-extrabold rounded-full">{{ $barangStokMenipis->count() }} Item</span>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($barangStokMenipis as $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50/50 border border-amber-200/60">
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $item->nama_barang }}</p>
                                <p class="text-[11px] text-slate-500">Kode: {{ $item->kode_barang }} | Rak: {{ $item->lokasi_rak ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 bg-red-600 text-white rounded-lg font-black text-xs">
                                    {{ number_format($item->stok) }} {{ $item->satuan->nama_satuan ?? 'Pcs' }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-0.5">Min: {{ number_format($item->stok_minimal) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Semua stok barang dalam kondisi aman!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
