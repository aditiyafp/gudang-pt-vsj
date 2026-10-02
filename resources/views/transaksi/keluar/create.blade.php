<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6" x-data="barangKeluarForm()">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">BUAT SURAT JALAN BARANG KELUAR</h2>
                <p class="text-xs text-slate-500 mt-0.5">Surat Jalan Resmi Barang Keluar PT GUDANG KEBERSIHAN VSJ. Mengurangi stok secara otomatis.</p>
            </div>
            <a href="{{ route('transaksi.keluar.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <form method="POST" action="{{ route('transaksi.keluar.store') }}" class="space-y-6">
            @csrf

            <!-- Form Header Surat Jalan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-2 flex items-center">
                    <i class="fa-solid fa-file-signature text-blue-600 mr-2"></i> Header Surat Jalan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Surat Jalan <span class="text-red-500">*</span></label>
                        <input type="text" name="no_surat_jalan" value="{{ old('no_surat_jalan', $noSuratJalan) }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-mono font-bold text-blue-700 bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tanggal" value="{{ old('tanggal', date('Y-m-d\TH:i')) }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ref.# (No Ref/PO/SPK)</label>
                        <input type="text" name="ref_no" value="{{ old('ref_no') }}" placeholder="Contoh: PO-VSJ-2023-089" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Halaman</label>
                        <input type="number" name="halaman" value="{{ old('halaman', 1) }}" min="1" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kirim Ke (Tujuan Pengiriman) <span class="text-red-500">*</span></label>
                        <input type="text" name="kirim_ke" value="{{ old('kirim_ke') }}" placeholder="Contoh: Apt. SenRes Bp. Sunoto BD / PT ABC" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Yang Menerima</label>
                        <input type="text" name="penerima" value="{{ old('penerima') }}" placeholder="Nama Penerima" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sopir / Pengirim</label>
                        <input type="text" name="sopir" value="{{ old('sopir') }}" placeholder="Nama Sopir" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Petugas Gudang</label>
                        <input type="text" name="petugas_gudang" value="{{ old('petugas_gudang', auth()->user()->name) }}" placeholder="Petugas Gudang" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Detail Items Table matching Image 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center">
                        <i class="fa-solid fa-list-check text-blue-600 mr-2"></i> Detail Barang Dalam Surat Jalan
                    </h3>
                    <button type="button" @click="addItem()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center space-x-1">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Barang</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-y border-slate-200">
                                <th class="py-2.5 px-3 w-10">No</th>
                                <th class="py-2.5 px-3 w-64">Detail Barang (Kode & Nama)</th>
                                <th class="py-2.5 px-3 w-24 text-center">Stok</th>
                                <th class="py-2.5 px-3 w-24">Qty Keluar</th>
                                <th class="py-2.5 px-3 w-32">Bekas / Tukar</th>
                                <th class="py-2.5 px-3 w-28">Inventaris (+/-)</th>
                                <th class="py-2.5 px-3">Keterangan Kondisi Barang Yang Dikembalikan</th>
                                <th class="py-2.5 px-3 w-10 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-2.5 px-3 font-bold text-slate-400" x-text="index + 1"></td>
                                    <td class="py-2.5 px-3">
                                        <select :name="'items['+index+'][barang_id]'" x-model="item.barang_id" @change="updateBarangInfo(index)" required class="w-full px-2 py-1.5 text-xs border border-slate-300 rounded-lg">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($barangs as $b)
                                                <option value="{{ $b->id }}" data-stok="{{ $b->stok }}">
                                                    [{{ $b->kode_barang }}] {{ $b->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-bold text-slate-700" x-text="item.stok_tersedia"></td>
                                    <td class="py-2.5 px-3">
                                        <input type="number" :name="'items['+index+'][qty]'" x-model="item.qty" min="0.01" step="0.01" required placeholder="3" class="w-full px-2 py-1.5 text-xs border border-slate-300 rounded-lg font-bold text-blue-700">
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <input type="text" :name="'items['+index+'][bekas_tukar]'" x-model="item.bekas_tukar" placeholder="misal: Jumlah 3 / Bekas 2" class="w-full px-2 py-1.5 text-xs border border-slate-300 rounded-lg">
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <input type="text" :name="'items['+index+'][inventaris_status]'" x-model="item.inventaris_status" placeholder="+, -, Inventaris" class="w-full px-2 py-1.5 text-xs border border-slate-300 rounded-lg">
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <input type="text" :name="'items['+index+'][keterangan_kondisi]'" x-model="item.keterangan_kondisi" placeholder="Kondisi barang..." class="w-full px-2 py-1.5 text-xs border border-slate-300 rounded-lg">
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="p-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <a href="{{ route('transaksi.keluar.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow">Simpan & Cetak Surat Jalan</button>
                </div>
            </div>

        </form>

    </div>

    <script>
        function barangKeluarForm() {
            return {
                items: [
                    { barang_id: '', qty: 3, bekas_tukar: '', inventaris_status: 'Inventaris', keterangan_kondisi: 'Baik', stok_tersedia: '-' }
                ],
                addItem() {
                    this.items.push({ barang_id: '', qty: 1, bekas_tukar: '', inventaris_status: '', keterangan_kondisi: '', stok_tersedia: '-' });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                updateBarangInfo(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    if (option.value) {
                        this.items[index].stok_tersedia = option.getAttribute('data-stok') || 0;
                    }
                }
            }
        }
    </script>
</x-app-layout>
