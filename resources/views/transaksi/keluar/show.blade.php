<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">DETAIL SURAT JALAN BARANG KELUAR</h2>
                <p class="text-xs text-slate-500 mt-0.5">No. Surat Jalan: <span class="font-mono font-bold text-blue-700">{{ $barangKeluar->no_surat_jalan }}</span></p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('transaksi.keluar.print', $barangKeluar->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center space-x-1">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Surat Jalan</span>
                </a>
                <a href="{{ route('transaksi.keluar.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center space-x-1">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                <div>
                    <p class="text-slate-400 font-bold uppercase">No. Surat Jalan</p>
                    <p class="font-mono font-bold text-blue-700 text-sm mt-0.5">{{ $barangKeluar->no_surat_jalan }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase">Tanggal</p>
                    <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $barangKeluar->tanggal->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase">Ref.#</p>
                    <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $barangKeluar->ref_no ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase">Halaman</p>
                    <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $barangKeluar->halaman }}</p>
                </div>
            </div>

            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 text-xs space-y-2">
                <p class="text-slate-500 font-bold uppercase">Kirim Ke (Tujuan):</p>
                <p class="font-black text-slate-800 text-sm">{{ $barangKeluar->kirim_ke }}</p>
            </div>

            <div class="grid grid-cols-3 gap-4 text-xs p-3 bg-slate-50 rounded-lg">
                <div><span class="text-slate-400 font-bold">Penerima:</span> <span class="font-semibold text-slate-800">{{ $barangKeluar->penerima ?? '-' }}</span></div>
                <div><span class="text-slate-400 font-bold">Sopir/Pengirim:</span> <span class="font-semibold text-slate-800">{{ $barangKeluar->sopir ?? '-' }}</span></div>
                <div><span class="text-slate-400 font-bold">Petugas Gudang:</span> <span class="font-semibold text-slate-800">{{ $barangKeluar->petugas_gudang ?? '-' }}</span></div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-y border-slate-200">
                            <th class="py-2.5 px-3">No</th>
                            <th class="py-2.5 px-3">Kode</th>
                            <th class="py-2.5 px-3">Detail Barang</th>
                            <th class="py-2.5 px-3 text-center">Qty</th>
                            <th class="py-2.5 px-3">Bekas Jumlah / Tukar</th>
                            <th class="py-2.5 px-3">Inventaris (+/-)</th>
                            <th class="py-2.5 px-3">Keterangan Kondisi Barang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($barangKeluar->details as $index => $detail)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-3 text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-blue-700">{{ $detail->barang->kode_barang ?? '-' }}</td>
                                <td class="py-3 px-3 font-bold text-slate-800">{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td class="py-3 px-3 text-center font-black text-red-600">- {{ number_format($detail->qty) }} {{ $detail->barang->satuan->nama_satuan ?? 'Pcs' }}</td>
                                <td class="py-3 px-3 text-slate-700 font-medium">{{ $detail->bekas_tukar ?? '-' }}</td>
                                <td class="py-3 px-3 text-slate-700 font-bold">{{ $detail->inventaris_status ?? '-' }}</td>
                                <td class="py-3 px-3 text-slate-600">{{ $detail->keterangan_kondisi ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</x-app-layout>
