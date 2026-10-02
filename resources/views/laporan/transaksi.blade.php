<x-app-layout>
    <div class="space-y-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">LAPORAN TRANSAKSI GUDANG</h2>
                <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi riwayat transaksi barang masuk & barang keluar berdasarkan rentang tanggal & waktu</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white hover:bg-slate-900 rounded-xl font-bold text-xs shadow-sm flex items-center justify-center space-x-2 transition no-print">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Laporan</span>
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm no-print">
            <form method="GET" action="{{ route('laporan.transaksi') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ $tahun }}" class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Jenis Transaksi</label>
                    <select name="jenis" class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg">
                        <option value="semua" {{ $jenis == 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                        <option value="masuk" {{ $jenis == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="keluar" {{ $jenis == 'keluar' ? 'selected' : '' }}>Barang Keluar (Surat Jalan)</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition flex-1">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Tampilkan Laporan
                    </button>
                    <a href="{{ route('laporan.transaksi') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300 transition flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Print Header -->
        <div class="hidden print:block text-center border-b-2 border-black pb-4 mb-6">
            <h1 class="text-xl font-extrabold uppercase">PT GUDANG KEBERSIHAN VSJ</h1>
            <h2 class="text-lg font-bold">LAPORAN TRANSAKSI BARANG</h2>
            <p class="text-xs">Periode: {{ Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</p>
        </div>

        <!-- Barang Masuk Table -->
        @if($jenis == 'semua' || $jenis == 'masuk')
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                <h3 class="font-bold text-emerald-800 text-sm flex items-center border-b border-emerald-100 pb-2">
                    <i class="fa-solid fa-arrow-down text-emerald-600 mr-2"></i> Rekapitulasi Barang Masuk
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-100 font-bold uppercase border-b border-slate-200">
                                <th class="py-2.5 px-3">No Transaksi</th>
                                <th class="py-2.5 px-3">Tanggal & Waktu</th>
                                <th class="py-2.5 px-3">Supplier</th>
                                <th class="py-2.5 px-3">Rincian Barang</th>
                                <th class="py-2.5 px-3 text-center">Total Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($barangMasuks as $bm)
                                <tr>
                                    <td class="py-2.5 px-3 font-mono font-bold text-emerald-700">{{ $bm->no_transaksi }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $bm->tanggal->format('d/m/Y H:i') }}</td>
                                    <td class="py-2.5 px-3 font-semibold">{{ $bm->supplier->nama_supplier ?? 'Tanpa Supplier' }}</td>
                                    <td class="py-2.5 px-3">
                                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-slate-700">
                                            @foreach($bm->details as $d)
                                                <li>{{ $d->barang->nama_barang ?? '-' }}: <strong>+{{ number_format($d->qty) }} {{ $d->barang->satuan->nama_satuan ?? 'Pcs' }}</strong></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-black text-emerald-600">+ {{ number_format($bm->details->sum('qty')) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-400">Tidak ada transaksi barang masuk pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Barang Keluar Table -->
        @if($jenis == 'semua' || $jenis == 'keluar')
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                <h3 class="font-bold text-blue-800 text-sm flex items-center border-b border-blue-100 pb-2">
                    <i class="fa-solid fa-arrow-up text-blue-600 mr-2"></i> Rekapitulasi Barang Keluar (Surat Jalan)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-100 font-bold uppercase border-b border-slate-200">
                                <th class="py-2.5 px-3">No. Surat Jalan</th>
                                <th class="py-2.5 px-3">Tanggal & Waktu</th>
                                <th class="py-2.5 px-3">Kirim Ke</th>
                                <th class="py-2.5 px-3">Rincian Barang Keluar</th>
                                <th class="py-2.5 px-3 text-center">Total Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($barangKeluars as $bk)
                                <tr>
                                    <td class="py-2.5 px-3 font-mono font-bold text-blue-700">{{ $bk->no_surat_jalan }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $bk->tanggal->format('d/m/Y H:i') }}</td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800">{{ $bk->kirim_ke }}</td>
                                    <td class="py-2.5 px-3">
                                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-slate-700">
                                            @foreach($bk->details as $d)
                                                <li>{{ $d->barang->nama_barang ?? '-' }}: <strong>-{{ number_format($d->qty) }} {{ $d->barang->satuan->nama_satuan ?? 'Pcs' }}</strong> ({{ $d->keterangan_kondisi ?? 'Baik' }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-black text-red-600">- {{ number_format($bk->details->sum('qty')) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-400">Tidak ada transaksi barang keluar pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
