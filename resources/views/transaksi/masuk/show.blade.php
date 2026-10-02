<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">DETAIL TRANSAKSI BARANG MASUK</h2>
                <p class="text-xs text-slate-500 mt-0.5">No Transaksi: <span class="font-mono font-bold text-emerald-700">{{ $barangMasuk->no_transaksi }}</span></p>
            </div>
            <a href="{{ route('transaksi.masuk.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            
            <!-- Metadata Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                <div>
                    <p class="text-slate-400 font-bold uppercase">No Transaksi</p>
                    <p class="font-mono font-bold text-emerald-700 text-sm mt-0.5">{{ $barangMasuk->no_transaksi }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase">Tanggal & Waktu</p>
                    <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $barangMasuk->tanggal->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase">Supplier</p>
                    <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $barangMasuk->supplier->nama_supplier ?? 'Tanpa Supplier' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase">Petugas Input</p>
                    <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $barangMasuk->user->name ?? '-' }}</p>
                </div>
            </div>

            @if($barangMasuk->catatan)
                <div class="p-3 bg-amber-50 rounded-lg text-xs text-amber-800 border border-amber-200">
                    <span class="font-bold"><i class="fa-solid fa-circle-info mr-1"></i> Catatan:</span> {{ $barangMasuk->catatan }}
                </div>
            @endif

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-y border-slate-200">
                            <th class="py-2.5 px-3">No</th>
                            <th class="py-2.5 px-3">Kode Barang</th>
                            <th class="py-2.5 px-3">Nama Barang</th>
                            <th class="py-2.5 px-3 text-center">Qty Masuk</th>
                            <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                            <th class="py-2.5 px-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($barangMasuk->details as $index => $detail)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-3 text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-emerald-700">{{ $detail->barang->kode_barang ?? '-' }}</td>
                                <td class="py-3 px-3 font-bold text-slate-800">{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td class="py-3 px-3 text-center font-black text-emerald-600">+ {{ number_format($detail->qty) }} {{ $detail->barang->satuan->nama_satuan ?? 'Pcs' }}</td>
                                <td class="py-3 px-3 text-right font-medium">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right font-bold text-slate-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-black text-slate-800 border-t-2 border-slate-200">
                            <td colspan="3" class="py-3 px-3 text-right uppercase">Total:</td>
                            <td class="py-3 px-3 text-center text-emerald-600">+ {{ number_format($barangMasuk->details->sum('qty')) }} Pcs</td>
                            <td colspan="2" class="py-3 px-3 text-right text-indigo-700">Rp {{ number_format($barangMasuk->details->sum('subtotal'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>
</x-app-layout>
