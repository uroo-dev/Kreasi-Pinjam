@extends('Layout.admin')
@section('title', 'Kategori')
@section('role_label', 'ADMIN')
@section('sidebar_menu')
    <button onclick="location.href='{{ route('kategori/get') }}'"
        class="anav w-full flex {{ request()->routeIs('kategori/*') ? 'bg-emerald-500 text-emerald-950 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900 hover:text-white' }} items-center gap-3 px-3 py-2.5 rounded-xl transition"
        data-anav="a-categories"><i data-lucide="layout-grid" class="w-4 h-4 shrink-0"></i><span
            class="side-label whitespace-nowrap">Kategori</span></button>
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Kategori Alat</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelompokkan alat agar mudah ditemukan dan dikelola</p>
        </div>
        <button onclick="openModal('modal-kategori-tambah')"
            class="flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>Tambah Kategori</button>
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
        <div class="flex flex-wrap items-center justify-between gap-3 p-5">
            <div>
                <h2 class="font-bold text-slate-900">Daftar Kategori</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $kategoris->total() }} kategori terdaftar</p>
            </div>
            <form action="{{ route('kategori/get') }}" method="GET" class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kategori..."
                    class="w-56 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]">
                <thead>
                    <tr class="bg-slate-50 text-left text-xs text-slate-500 font-semibold">
                        <th class="px-5 py-3">Nama Kategori</th>
                        <th class="px-5 py-3">Deskripsi</th>
                        <th class="px-5 py-3">Jumlah Alat</th>
                        <th class="px-5 py-3">Dibuat</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($kategoris as $kategori)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-800">{{ $kategori->nama_kategori }}</td>
                            <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">{{ $kategori->deskripsi ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700">{{ $kategori->alat_count }} alat</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $kategori->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditKategori(this)" data-id="{{ $kategori->id }}"
                                        data-nama="{{ $kategori->nama_kategori }}"
                                        data-deskripsi="{{ $kategori->deskripsi }}"
                                        class="p-2 rounded-xl hover:bg-slate-100 text-sky-600 transition" aria-label="Edit kategori">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="hapusKategori({{ $kategori->id }})" class="p-2 rounded-xl hover:bg-red-50 text-red-600 transition" aria-label="Hapus kategori">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center">
                                <p class="font-bold text-slate-500">Belum ada kategori</p>
                                <p class="text-xs text-slate-400 mt-0.5">Tambahkan kategori pertama agar alat bisa dikelompokkan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($kategoris->hasPages())
            <div class="px-5 pb-5">
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="modal-kategori-tambah" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-kategori-tambah')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-slate-900">Tambah Kategori</h3>
                <button onclick="closeModal('modal-kategori-tambah')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('kategori/store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama kategori</label>
                        <input type="text" name="nama_kategori" placeholder="Contoh: Peralatan Elektronik" value="{{ old('nama_kategori') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Jelaskan singkat isi kategori" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-kategori-tambah')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition">Simpan Kategori</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="modal-kategori-edit" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-kategori-edit')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-slate-900">Edit Kategori</h3>
                <button onclick="closeModal('modal-kategori-edit')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form id="form-kategori-edit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama kategori</label>
                        <input type="text" name="nama_kategori" id="edit-nama_kategori" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-kategori-edit')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
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
                    <h3 class="font-bold text-lg text-slate-900">Hapus kategori ini?</h3>
                    <p class="text-sm text-slate-500 mt-1">Kategori akan dinonaktifkan (soft delete) dan tidak tampil pada daftar. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <form id="form-kategori-delete" action="" method="POST">
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
        function openEditKategori(btn) {
            const id = btn.dataset.id;
            document.getElementById('edit-nama_kategori').value = btn.dataset.nama || '';
            document.getElementById('edit-deskripsi').value = btn.dataset.deskripsi || '';
            document.getElementById('form-kategori-edit').action = '/admin/kategori/' + id;
            openModal('modal-kategori-edit');
        }

        function hapusKategori(id) {
            document.getElementById('form-kategori-delete').action = '/admin/kategori/' + id;
            openModal('modal-delete');
        }
    </script>
@endpush
