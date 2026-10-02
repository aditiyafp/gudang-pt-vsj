<x-app-layout>
    <div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: '', nama_satuan: '', keterangan: '' } }">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">SATUAN BARANG</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola satuan kemasan/unit barang (misal: Pcs, Botol, Roll, Pack, Set)</p>
            </div>
            <button @click="editMode = false; form = { id: '', nama_satuan: '', keterangan: '' }; showModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center space-x-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Satuan</span>
            </button>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Nama Satuan</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($satuans as $index => $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-semibold text-slate-400">{{ $satuans->firstItem() + $index }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $s->nama_satuan }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $s->keterangan ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button @click="editMode = true; form = { id: '{{ $s->id }}', nama_satuan: '{{ addslashes($s->nama_satuan) }}', keterangan: '{{ addslashes($s->keterangan) }}' }; showModal = true" class="p-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-md transition">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form method="POST" action="{{ route('satuan.destroy', $s->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus satuan ini?')">
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
                            <td colspan="4" class="py-6 text-center text-slate-400">Belum ada data satuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-slate-100">
                {{ $satuans->links() }}
            </div>
        </div>

        <!-- Modal Form -->
        <div x-show="showModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-transition>
            <div @click.away="showModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
                <div class="p-4 bg-indigo-900 text-white flex justify-between items-center">
                    <h3 class="font-bold text-sm" x-text="editMode ? 'Edit Satuan' : 'Tambah Satuan Baru'"></h3>
                    <button @click="showModal = false" class="text-indigo-200 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="editMode ? '{{ url('satuan') }}/' + form.id : '{{ route('satuan.store') }}'" method="POST" class="p-5 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Satuan</label>
                        <input type="text" name="nama_satuan" x-model="form.nama_satuan" required placeholder="Contoh: Pcs / Botol" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan</label>
                        <textarea name="keterangan" x-model="form.keterangan" rows="3" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
