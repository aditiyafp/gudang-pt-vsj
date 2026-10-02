<x-app-layout>
    <div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: '', name: '', email: '', role: 'staf_gudang', phone: '' } }">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">PENGELOLAAN PENGGUNA (USERS)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola hak akses pengguna sistem gudang PT Kebersihan VSJ</p>
            </div>
            <button @click="editMode = false; form = { id: '', name: '', email: '', role: 'staf_gudang', phone: '' }; showModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm flex items-center space-x-2 transition">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Pengguna Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold uppercase border-b border-slate-200">
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Telepon</th>
                        <th class="py-3 px-4 text-center">Role / Akses</th>
                        <th class="py-3 px-4 text-center">Terdaftar</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-bold text-slate-800 flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs uppercase">
                                    {{ substr($u->name, 0, 2) }}
                                </div>
                                <span>{{ $u->name }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium">{{ $u->email }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $u->phone ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($u->role == 'admin')
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-full font-extrabold uppercase text-[10px]">ADMIN</span>
                                @elseif($u->role == 'manajer')
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full font-extrabold uppercase text-[10px]">MANAJER</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-full font-extrabold uppercase text-[10px]">STAF GUDANG</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center text-slate-400 font-medium">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button @click="editMode = true; form = { id: '{{ $u->id }}', name: '{{ addslashes($u->name) }}', email: '{{ addslashes($u->email) }}', role: '{{ $u->role }}', phone: '{{ addslashes($u->phone) }}' }; showModal = true" class="p-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-md transition">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    @if(auth()->id() != $u->id)
                                        <form method="POST" action="{{ route('pengguna.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-md transition">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Modal Form -->
        <div x-show="showModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-transition>
            <div @click.away="showModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
                <div class="p-4 bg-indigo-900 text-white flex justify-between items-center">
                    <h3 class="font-bold text-sm" x-text="editMode ? 'Edit Pengguna' : 'Tambah Pengguna Baru'"></h3>
                    <button @click="showModal = false" class="text-indigo-200 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="editMode ? '{{ url('pengguna') }}/' + form.id : '{{ route('pengguna.store') }}'" method="POST" class="p-5 space-y-3">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" x-model="form.email" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Role / Akses</label>
                            <select name="role" x-model="form.role" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                                <option value="admin">Admin</option>
                                <option value="staf_gudang">Staf Gudang</option>
                                <option value="manajer">Manajer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telepon</label>
                            <input type="text" name="phone" x-model="form.phone" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" x-text="editMode ? 'Password (Kosongkan jika tidak diubah)' : 'Password'"></label>
                        <input type="password" name="password" :required="!editMode" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg">
                    </div>

                    <div class="flex justify-end space-x-2 pt-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow">Simpan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
