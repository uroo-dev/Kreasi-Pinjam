@extends('Layout.admin')
@section('title', 'Pengguna')
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Data Pengguna</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola akun admin, petugas, dan peminjam</p>
        </div>
        <button onclick="openModal('modal-user-tambah')"
            class="flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>Tambah Pengguna</button>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl p-4 text-sm">
            <p class="font-bold text-red-600 flex items-center gap-2 mb-1"><i data-lucide="alert-triangle" class="w-4 h-4"></i>Periksa kembali form Anda</p>
            <ul class="list-disc list-inside text-red-600/90 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="p-5">
            <h2 class="font-bold text-slate-900">Daftar Pengguna</h2>
            <p class="text-xs text-slate-400 mt-0.5 mb-4">{{ $users->total() }} pengguna terdaftar</p>
            <form action="{{ route('user/get') }}" method="GET" class="flex flex-wrap gap-2">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email..."
                        class="w-56 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <select name="role"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua role</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="petugas" @selected(request('role') === 'petugas')>Petugas</option>
                    <option value="peminjam" @selected(request('role') === 'peminjam')>Peminjam</option>
                </select>
                <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]">
                <thead>
                    <tr class="bg-slate-50 text-left text-xs text-slate-500 font-semibold">
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">No. HP</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <img src="https://picsum.photos/seed/{{ $user->id }}-{{ $user->nama }}/64/64" alt="Foto {{ $user->nama }}" class="w-8 h-8 rounded-xl object-cover">
                                    <span class="font-bold text-slate-800">{{ $user->nama }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $user->email }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $user->no_hp ?: '-' }}</td>
                            <td class="px-5 py-3.5">
                                @php
                                    $warnaRole = match ($user->role) {
                                        'admin' => 'bg-emerald-50 text-emerald-700',
                                        'petugas' => 'bg-sky-50 text-sky-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $warnaRole }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditUser(this)" data-id="{{ $user->id }}"
                                        data-nama="{{ $user->nama }}" data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}" data-no_hp="{{ $user->no_hp }}"
                                        class="p-2 rounded-xl hover:bg-slate-100 text-sky-600 transition" aria-label="Edit pengguna">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="hapusUser({{ $user->id }}, '{{ $user->nama }}')"
                                        class="p-2 rounded-xl hover:bg-red-50 text-red-600 transition" aria-label="Hapus pengguna">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center">
                                <p class="font-bold text-slate-500">Belum ada pengguna</p>
                                <p class="text-xs text-slate-400 mt-0.5">Tambahkan akun agar sistem dapat digunakan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-5 pb-5">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah Pengguna -->
    <div id="modal-user-tambah" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-user-tambah')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-slate-900">Tambah Pengguna</h3>
                <button onclick="closeModal('modal-user-tambah')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('user/store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    @include('Admin.partials.form-user', ['prefix' => 'tambah'])
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-user-tambah')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition">Simpan Pengguna</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pengguna -->
    <div id="modal-user-edit" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-user-edit')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-slate-900">Edit Pengguna</h3>
                <button onclick="closeModal('modal-user-edit')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form id="form-user-edit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    @include('Admin.partials.form-user', ['prefix' => 'edit'])
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-user-edit')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="modal-delete" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-delete')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                    <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Hapus pengguna ini?</h3>
                    <p class="text-sm text-slate-500 mt-1">Pengguna "<span id="delete-nama-user"></span>" akan dihapus dari data (soft delete). Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <form id="form-user-delete" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('modal-delete')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function openEditUser(btn) {
            document.getElementById('edit-nama').value = btn.dataset.nama || '';
            document.getElementById('edit-email').value = btn.dataset.email || '';
            document.getElementById('edit-role').value = btn.dataset.role || '';
            document.getElementById('edit-no_hp').value = btn.dataset.no_hp || '';
            document.getElementById('edit-password').value = '';
            document.getElementById('edit-password').placeholder = 'Biarkan kosong jika tidak diubah';
            document.getElementById('form-user-edit').action = '/admin/user/' + btn.dataset.id;
            openModal('modal-user-edit');
        }

        function hapusUser(id, nama) {
            document.getElementById('delete-nama-user').textContent = nama;
            document.getElementById('form-user-delete').action = '/admin/user/' + id;
            openModal('modal-delete');
        }
    </script>
@endpush
