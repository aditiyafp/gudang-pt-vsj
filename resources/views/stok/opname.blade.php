<x-app-layout>
    <div class="space-y-6" x-data="{ showModal: false }">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">STOK OPNAME</h2>
                <p class="text-xs text-slate-500 mt-0.5">Penyesuaian stok fisik barang gudang dengan stok sistem</p>
            </div>
            <button @click="showModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center space-x-2 transition">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Input Stok Opname Baru</span>
            </button>
        </div>

        <!-- Table Opnames -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-4">No Opname</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Barang</th>
                            <th class="py-3 px-4 text-center">Stok Sistem</th>
                            <th class="py-3 px-4 text-center">Stok Fisik</th>
                            <th class="py-3 px-4 text-center">Selisih</th>
                            <th class="py-3 px-4">Keterangan</th>
                            <th class="py-3 px-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($opnames as $op)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $op->no_opname }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $op->tanggal->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    [{{ $op->barang->kode_barang ?? '-' }}] {{ $op->barang->nama_barang ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-slate-600">{{ number_format($op->stok_sistem) }}</td>
                                <td class="py-3 px-4 text-center font-black text-indigo-700">{{ number_format($op->stok_fisik) }}</td>
                                <td class="py-3 px-4 text-center font-black">
                                    @if($op->selisih == 0)
                                        <span class="text-slate-500">0 (Sesuai)</span>
                                    @elseif($op->selisih > 0)
                                        <span class="text-emerald-600">+{{ number_format($op->selisih) }}</span>
                                    @else
                                        <span class="text-red-600">{{ number_format($op->selisih) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $op->keterangan ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-medium">{{ $op->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">Belum ada riwayat stok opname.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $opnames->links() }}
            </div>
        </div>

        <!-- Modal Form Opname -->
        <div x-show="showModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-transition>
            <div @click.away="showModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
                <div class="p-4 bg-indigo-900 text-white flex justify-between items-center">
                    <h3 class="font-bold text-sm">Input Penyesuaian Stok Opname</h3>
                    <button @click="showModal = false" class="text-indigo-200 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('stok.opname.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Opname</label>
                        <input type="datetime-local" name="tanggal" value="{{ date('Y-m-d\TH:i') }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Barang Kebersihan</label>
                        <select name="barang_id" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}">[{{ $b->kode_barang }}] {{ $b->nama_barang }} (Stok Sistem: {{ number_format($b->stok) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hasil Hitungan Stok Fisik (Aktual)</label>
                        <input type="number" name="stok_fisik" min="0" step="0.01" required placeholder="Jumlah fisik nyata di gudang..." class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-bold text-indigo-700">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan / Alasan Selisih</label>
                        <textarea name="keterangan" rows="3" placeholder="Contoh: Barang rusak / hilang / tertukar..." class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow">Simpan & Adjust Stok</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
