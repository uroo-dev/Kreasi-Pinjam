@extends('Layout.admin')
@section('title', 'Alat')
@section('role_label', 'ADMIN')
@section('sidebar_menu')
    <button onclick="location.href='{{ route('kategori/get') }}'"
        class="anav w-full flex text-emerald-100/80 hover:bg-emerald-900 hover:text-white items-center gap-3 px-3 py-2.5 rounded-xl transition"
        data-anav="a-categories"><i data-lucide="layout-grid" class="w-4 h-4 shrink-0"></i><span
            class="side-label whitespace-nowrap">Kategori</span></button>
    <button onclick="location.href='{{ route('alat/get') }}'"
        class="anav w-full flex {{ request()->routeIs('alat/*') ? 'bg-emerald-500 text-emerald-950 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900 hover:text-white' }} items-center gap-3 px-3 py-2.5 rounded-xl transition"
        data-anav="a-alat"><i data-lucide="package" class="w-4 h-4 shrink-0"></i><span
            class="side-label whitespace-nowrap">Alat</span></button>
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Data Alat</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola katalog alat, stok, dan harga sewa</p>
        </div>
        <button onclick="openModal('modal-alat-tambah')"
            class="flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>Tambah Alat</button>
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
            <h2 class="font-bold text-slate-900">Daftar Alat</h2>
            <p class="text-xs text-slate-400 mt-0.5 mb-4">{{ $alats->total() }} alat terdaftar</p>
            <form action="{{ route('alat/get') }}" method="GET" class="flex flex-wrap gap-2">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau kode alat..."
                        class="w-56 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <select name="kategori_id"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                <select name="status"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua status</option>
                    <option value="tersedia" @selected(request('status') === 'tersedia')>Tersedia</option>
                    <option value="tidak_tersedia" @selected(request('status') === 'tidak_tersedia')>Tidak Tersedia</option>
                </select>
                <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50 text-left text-xs text-slate-500 font-semibold">
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Nama Alat</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Stok</th>
                        <th class="px-5 py-3">Harga Sewa</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($alats as $alat)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-700">{{ $alat->kode_alat }}</td>
                            <td class="px-5 py-3.5">
                                <span class="block font-bold text-slate-800">{{ $alat->nama_alat }}</span>
                                @if ($alat->deskripsi)
                                    <span class="block text-xs text-slate-400 mt-0.5 max-w-xs truncate">{{ $alat->deskripsi }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-slate-600">
                                <span class="font-bold text-slate-800">{{ $alat->stok_tersedia }}</span> / {{ $alat->stok_total }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">Rp{{ number_format($alat->harga_sewa, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $alat->status === 'tersedia' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $alat->status === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditAlat(this)" data-alat_id="{{ $alat->id }}"
                                        data-kategori_id="{{ $alat->kategori_id }}"
                                        data-kode_alat="{{ $alat->kode_alat }}" data-nama_alat="{{ $alat->nama_alat }}"
                                        data-deskripsi="{{ $alat->deskripsi }}" data-stok_total="{{ $alat->stok_total }}"
                                        data-harga_sewa="{{ $alat->harga_sewa }}"
                                        class="p-2 rounded-xl hover:bg-slate-100 text-sky-600 transition" aria-label="Edit alat">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="hapusAlat({{ $alat->id }}, '{{ $alat->nama_alat }}')"
                                        class="p-2 rounded-xl hover:bg-red-50 text-red-600 transition" aria-label="Hapus alat">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <p class="font-bold text-slate-500">Belum ada alat</p>
                                <p class="text-xs text-slate-400 mt-0.5">Tambahkan alat pertama ke katalog</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($alats->hasPages())
            <div class="px-5 pb-5">
                {{ $alats->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah Alat -->
    <div id="modal-alat-tambah" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-alat-tambah')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-slate-900">Tambah Alat</h3>
                <button onclick="closeModal('modal-alat-tambah')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('alat/store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    @include('Admin.partials.form-alat', ['prefix' => 'tambah'])
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-alat-tambah')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition">Simpan Alat</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Alat -->
    <div id="modal-alat-edit" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-alat-edit')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-slate-900">Edit Alat</h3>
                <button onclick="closeModal('modal-alat-edit')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form id="form-alat-edit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    @include('Admin.partials.form-alat', ['prefix' => 'edit'])
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-alat-edit')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
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
                    <h3 class="font-bold text-lg text-slate-900">Hapus alat ini?</h3>
                    <p class="text-sm text-slate-500 mt-1">Alat "<span id="delete-nama-alat"></span>" akan dihapus dari daftar (soft delete). Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <form id="form-alat-delete" action="" method="POST">
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
        function openEditAlat(btn) {
            document.getElementById('edit-kategori_id').value = btn.dataset.kategori_id;
            document.getElementById('edit-kode_alat').value = btn.dataset.kode_alat;
            document.getElementById('edit-nama_alat').value = btn.dataset.nama_alat;
            document.getElementById('edit-deskripsi').value = btn.dataset.deskripsi || '';
            document.getElementById('edit-stok_total').value = btn.dataset.stok_total;
            document.getElementById('edit-harga_sewa').value = btn.dataset.harga_sewa;
            document.getElementById('form-alat-edit').action = '/admin/alat/' + btn.dataset.alat_id;
            openModal('modal-alat-edit');
        }

        function hapusAlat(id, nama) {
            document.getElementById('delete-nama-alat').textContent = nama;
            document.getElementById('form-alat-delete').action = '/admin/alat/' + id;
            openModal('modal-delete');
        }
    </script>
@endpush
