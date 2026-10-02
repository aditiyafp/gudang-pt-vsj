<x-app-layout>
    <div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: '', kode_supplier: '', nama_supplier: '', telepon: '', email: '', alamat: '' } }">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">DATA SUPPLIER</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola daftar pemasok barang kebersihan untuk PT Gudang Kebersihan VSJ</p>
            </div>
            <button @click="editMode = false; form = { id: '', kode_supplier: 'SUP-00' + ({{ $suppliers->total() + 1 }}), nama_supplier: '', telepon: '', email: '', alamat: '' }; showModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center space-x-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Supplier</span>
            </button>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Supplier</th>
                        <th class="py-3 px-4">Telepon</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Alamat</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suppliers as $sup)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $sup->kode_supplier }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $sup->nama_supplier }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $sup->telepon ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $sup->email ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $sup->alamat ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button @click="editMode = true; form = { id: '{{ $sup->id }}', kode_supplier: '{{ $sup->kode_supplier }}', nama_supplier: '{{ addslashes($sup->nama_supplier) }}', telepon: '{{ addslashes($sup->telepon) }}', email: '{{ addslashes($sup->email) }}', alamat: '{{ addslashes($sup->alamat) }}' }; showModal = true" class="p-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-md transition">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form method="POST" action="{{ route('supplier.destroy', $sup->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-md transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Belum ada data supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-slate-100">
                {{ $suppliers->links() }}
            </div>
        </div>

        <!-- Modal Form -->
        <div x-show="showModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-transition>
            <div @click.away="showModal = false" class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
                <div class="p-4 bg-indigo-900 text-white flex justify-between items-center">
                    <h3 class="font-bold text-sm" x-text="editMode ? 'Edit Supplier' : 'Tambah Supplier Baru'"></h3>
                    <button @click="showModal = false" class="text-indigo-200 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="editMode ? '{{ url('supplier') }}/' + form.id : '{{ route('supplier.store') }}'" method="POST" class="p-5 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Supplier</label>
                            <input type="text" name="kode_supplier" x-model="form.kode_supplier" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-mono font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Supplier</label>
                            <input type="text" name="nama_supplier" x-model="form.nama_supplier" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telepon</label>
                            <input type="text" name="telepon" x-model="form.telepon" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email</label>
                            <input type="email" name="email" x-model="form.email" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat</label>
                        <textarea name="alamat" x-model="form.alamat" rows="3" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow">Simpan Supplier</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
