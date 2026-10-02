<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6" x-data="barangMasukForm()">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">INPUT TRANSAKSI BARANG MASUK</h2>
                <p class="text-xs text-slate-500 mt-0.5">Penerimaan barang masuk akan secara otomatis menambahkan stok barang yang bersangkutan</p>
            </div>
            <a href="{{ route('transaksi.masuk.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <form method="POST" action="{{ route('transaksi.masuk.store') }}" class="space-y-6">
            @csrf

            <!-- Form Header -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-2 flex items-center">
                    <i class="fa-solid fa-file-invoice text-emerald-600 mr-2"></i> Informasi Transaksi
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No Transaksi <span class="text-red-500">*</span></label>
                        <input type="text" name="no_transaksi" value="{{ old('no_transaksi', $noTransaksi) }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-mono font-bold text-emerald-700 bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal & Waktu <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tanggal" value="{{ old('tanggal', date('Y-m-d\TH:i')) }}" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Supplier</label>
                        <select name="supplier_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="">-- Pilih Supplier (Opsional) --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->kode_supplier }} - {{ $sup->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan</label>
                    <input type="text" name="catatan" placeholder="Catatan transaksi barang masuk..." class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                </div>
            </div>

            <!-- Detail Items Table -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center">
                        <i class="fa-solid fa-boxes-stacked text-emerald-600 mr-2"></i> Daftar Barang Masuk
                    </h3>
                    <button type="button" @click="addItem()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center space-x-1">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Item Barang</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 font-bold uppercase">
                                <th class="py-2.5 px-3 w-12">No</th>
                                <th class="py-2.5 px-3">Pilih Barang Kebersihan</th>
                                <th class="py-2.5 px-3 w-32">Stok Saat Ini</th>
                                <th class="py-2.5 px-3 w-32">Jumlah Masuk (Qty)</th>
                                <th class="py-2.5 px-3 w-40">Harga Beli Satuan</th>
                                <th class="py-2.5 px-3 w-12 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b border-slate-100">
                                    <td class="py-2.5 px-3 font-bold text-slate-400" x-text="index + 1"></td>
                                    <td class="py-2.5 px-3">
                                        <select :name="'items['+index+'][barang_id]'" x-model="item.barang_id" @change="updateBarangInfo(index)" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($barangs as $b)
                                                <option value="{{ $b->id }}" data-stok="{{ $b->stok }}" data-harga="{{ $b->harga_masuk }}">
                                                    [{{ $b->kode_barang }}] {{ $b->nama_barang }} (Stok: {{ number_format($b->stok) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2.5 px-3 font-bold text-slate-700" x-text="item.stok_saat_ini + ' Pcs'"></td>
                                    <td class="py-2.5 px-3">
                                        <input type="number" :name="'items['+index+'][qty]'" x-model="item.qty" min="0.01" step="0.01" required placeholder="10" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-bold text-emerald-700">
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <input type="number" :name="'items['+index+'][harga_satuan]'" x-model="item.harga_satuan" min="0" step="100" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
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
                    <a href="{{ route('transaksi.masuk.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow">Simpan & Tambah Stok</button>
                </div>
            </div>

        </form>

    </div>

    <script>
        function barangMasukForm() {
            return {
                items: [
                    { barang_id: '', qty: 10, harga_satuan: 0, stok_saat_ini: 0 }
                ],
                addItem() {
                    this.items.push({ barang_id: '', qty: 1, harga_satuan: 0, stok_saat_ini: 0 });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                updateBarangInfo(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    if (option.value) {
                        this.items[index].stok_saat_ini = option.getAttribute('data-stok') || 0;
                        this.items[index].harga_satuan = option.getAttribute('data-harga') || 0;
                    }
                }
            }
        }
    </script>
</x-app-layout>
