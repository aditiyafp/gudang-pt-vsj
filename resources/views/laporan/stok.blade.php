<x-app-layout>
    <div class="space-y-6" x-data="{ showCloseModal: false }">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">LAPORAN STOK PERBULAN & TUTUP BUKU</h2>
                <p class="text-xs text-slate-500 mt-0.5">Laporan saldo awal, mutasi masuk/keluar, dan saldo akhir stok barang per bulan PT Gudang Kebersihan VSJ</p>
            </div>
            
            <div class="flex items-center space-x-2 no-print">
                @if(!$isClosed)
                    <button @click="showCloseModal = true" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-extrabold text-xs shadow-md flex items-center space-x-2 transition">
                        <i class="fa-solid fa-lock"></i>
                        <span>Tutup Laporan Akhir Bulan</span>
                    </button>
                @else
                    <form method="POST" action="{{ route('laporan.stok.buka') }}" onsubmit="return confirm('Apakah Anda yakin ingin membuka kembali laporan bulan ini?')">
                        @csrf
                        <input type="hidden" name="periode" value="{{ $periode }}">
                        <button type="submit" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-extrabold text-xs shadow-md flex items-center space-x-2 transition">
                            <i class="fa-solid fa-lock-open"></i>
                            <span>Buka Kembali Laporan</span>
                        </button>
                    </form>
                @endif

                <button onclick="window.print()" class="px-4 py-2.5 bg-slate-800 text-white hover:bg-slate-900 rounded-xl font-bold text-xs shadow-sm flex items-center space-x-2 transition">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        <!-- Filter Month & Year -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm no-print">
            <form method="GET" action="{{ route('laporan.stok') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Pilih Bulan</label>
                    <select name="bulan" class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Pilih Tahun</label>
                    <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2050" class="w-full px-3 py-1.5 text-xs border border-slate-300 rounded-lg">
                </div>

                <div class="sm:col-span-2 flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition flex-1">
                        <i class="fa-solid fa-calendar-days mr-1"></i> Tampilkan Laporan Stok Bulan Ini
                    </button>
                    <a href="{{ route('laporan.stok') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-300 transition flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>

            </form>
        </div>

        <!-- Status Banner -->
        <div class="rounded-2xl p-4 border flex flex-col md:flex-row md:items-center justify-between gap-3 {{ $isClosed ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-amber-50 border-amber-200 text-amber-900' }}">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $isClosed ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                    <i class="fa-solid {{ $isClosed ? 'fa-lock' : 'fa-lock-open' }}"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm">
                        STATUS LAPORAN PERIODE {{ strtoupper($namaBulan) }} {{ $tahun }} : 
                        <span class="underline">{{ $isClosed ? 'LAPORAN DITUTUP (RESMI)' : 'LAPORAN MASIH DIBUKA' }}</span>
                    </h3>
                    <p class="text-xs opacity-80 mt-0.5">
                        @if($isClosed)
                            Laporan telah ditutup secara permanen pada {{ $closing->tanggal_tutup->format('d/m/Y H:i') }} WIB oleh {{ $closing->closedBy->name ?? 'Admin' }}. Angka stok telah dibekukan (snapshot).
                        @else
                            Laporan masih aktif. Data mutasi transaksi barang masuk & keluar dihitung secara real-time. Anda dapat menutup laporan di akhir bulan.
                        @endif
                    </p>
                </div>
            </div>
            @if($isClosed)
                <span class="px-3 py-1 bg-emerald-200 text-emerald-900 rounded-full font-black text-xs uppercase tracking-wider text-center">
                    Snapshot Verified <i class="fa-solid fa-circle-check ml-1"></i>
                </span>
            @endif
        </div>

        <!-- Print Title -->
        <div class="hidden print:block text-center border-b-2 border-black pb-4 mb-4">
            <h1 class="text-xl font-extrabold uppercase">PT GUDANG KEBERSIHAN VSJ</h1>
            <h2 class="text-base font-bold uppercase">LAPORAN MUTASI STOK BARANG BULANAN</h2>
            <p class="text-xs">Periode: {{ $namaBulan }} {{ $tahun }} | Status: {{ $isClosed ? 'DITUTUP (RESMI)' : 'REALTIME' }}</p>
        </div>

        <!-- Stok Report Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Kode Barang</th>
                            <th class="py-3 px-4">Nama Barang Kebersihan</th>
                            <th class="py-3 px-4">Satuan</th>
                            <th class="py-3 px-4 text-center">Saldo Awal</th>
                            <th class="py-3 px-4 text-center text-emerald-600">Barang Masuk</th>
                            <th class="py-3 px-4 text-center text-red-600">Barang Keluar</th>
                            <th class="py-3 px-4 text-center">Saldo Akhir</th>
                            <th class="py-3 px-4 text-right">Harga Satuan</th>
                            <th class="py-3 px-4 text-right">Total Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php 
                            $grandTotalNilai = 0;
                            $grandTotalStokAkhir = 0;
                        @endphp
                        @forelse($reportData as $index => $row)
                            @php 
                                $grandTotalNilai += $row['total_nilai'];
                                $grandTotalStokAkhir += $row['stok_akhir'];
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $row['barang']->kode_barang ?? '-' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $row['barang']->nama_barang ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $row['barang']->satuan->nama_satuan ?? 'Pcs' }}</td>
                                <td class="py-3 px-4 text-center font-semibold text-slate-600">{{ number_format($row['stok_awal']) }}</td>
                                <td class="py-3 px-4 text-center font-bold text-emerald-600">+ {{ number_format($row['total_masuk']) }}</td>
                                <td class="py-3 px-4 text-center font-bold text-red-600">- {{ number_format($row['total_keluar']) }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-800 rounded-lg font-black text-xs">
                                        {{ number_format($row['stok_akhir']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right text-slate-600">Rp {{ number_format($row['harga_satuan'], 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-800">Rp {{ number_format($row['total_nilai'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400">Belum ada data barang untuk laporan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-100 font-black text-slate-800 border-t-2 border-slate-300">
                            <td colspan="7" class="py-3.5 px-4 text-right uppercase">TOTAL SALDO AKHIR & NILAI STOK:</td>
                            <td class="py-3.5 px-4 text-center text-indigo-700 text-sm">{{ number_format($grandTotalStokAkhir) }} Pcs</td>
                            <td colspan="2" class="py-3.5 px-4 text-right text-emerald-700 text-sm">Rp {{ number_format($grandTotalNilai, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Modal Tutup Laporan Akhir Bulan -->
        <div x-show="showCloseModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-transition>
            <div @click.away="showCloseModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
                <div class="p-4 bg-rose-700 text-white flex justify-between items-center">
                    <h3 class="font-bold text-sm flex items-center">
                        <i class="fa-solid fa-lock mr-2"></i> Konfirmasi Tutup Laporan Akhir Bulan
                    </h3>
                    <button @click="showCloseModal = false" class="text-rose-200 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('laporan.stok.tutup') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 space-y-1">
                        <p class="font-bold">Apakah Anda yakin ingin menutup laporan periode {{ $namaBulan }} {{ $tahun }}?</p>
                        <p>Tutup laporan akan merekam & membekukan saldo awal, total barang masuk, total barang keluar, dan saldo akhir setiap barang secara permanen sebagai <strong>Laporan Resmi Bulanan PT Gudang Kebersihan VSJ</strong>.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Penutupan Laporan (Opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Contoh: Laporan bulanan telah diperiksa dan disetujui Manajer Gudang..." class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="showCloseModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow">Tutup Laporan Resmi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
