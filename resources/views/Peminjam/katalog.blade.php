@extends('Layout.admin')
@section('title', 'Katalog Alat')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Katalog Alat</h1>
        <p class="text-sm text-slate-500 mt-0.5">Pilih alat yang tersedia untuk dijadwalkan peminjaman</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl p-4 text-sm">
            <p class="font-bold text-red-600 flex items-center gap-2 mb-1"><i data-lucide="alert-triangle" class="w-4 h-4"></i>Pengajuan tidak dapat diproses</p>
            <ul class="list-disc list-inside text-red-600/90 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($tanggungan['ada'])
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i></span>
            <div>
                <p class="font-bold text-amber-800">Anda masih memiliki tanggungan</p>
                <p class="text-sm text-amber-700 mt-0.5">
                    {{ $tanggungan['melewati_jatuh_tempo'] }} peminjaman melewati jatuh tempo dan denda belum lunas sebesar Rp{{ number_format($tanggungan['denda_belum_lunas'], 0, ',', '.') }}.
                    Pengajuan baru ditolak sampai tanggungan selesai.
                </p>
                <a href="{{ route('peminjam/riwayat/get') }}" class="inline-block mt-2 text-sm font-bold text-amber-800 hover:text-amber-900">Lihat riwayat peminjaman</a>
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4">
        <form action="{{ route('peminjam/katalog/get') }}" method="GET" class="flex flex-wrap gap-2">
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
            <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
        </form>
    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($alats as $alat)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col">
                <div class="h-36 bg-slate-100 relative">
                    <img src="https://picsum.photos/seed/alat-{{ $alat->kode_alat }}/600/300" alt="Foto {{ $alat->nama_alat }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://picsum.photos/seed/fallback-{{ $alat->id }}/600/300'">
                    <span class="absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full bg-white/90 text-slate-700">{{ $alat->kategori->nama_kategori ?? '-' }}</span>
                    <span class="absolute top-3 right-3 text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-600 text-white">{{ $alat->stok_tersedia }} unit</span>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <p class="text-[11px] font-bold font-mono text-slate-400">{{ $alat->kode_alat }}</p>
                    <h3 class="font-bold text-slate-900 mt-0.5">{{ $alat->nama_alat }}</h3>
                    @if ($alat->deskripsi)
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $alat->deskripsi }}</p>
                    @endif
                    <div class="flex items-end justify-between mt-3">
                        <span>
                            <span class="block text-lg font-extrabold text-slate-900">Rp{{ number_format($alat->harga_sewa, 0, ',', '.') }}</span>
                            <span class="block text-[11px] text-slate-400">per hari / unit</span>
                        </span>
                        <button onclick="openAjukan(this)" data-id="{{ $alat->id }}" data-nama="{{ $alat->nama_alat }}"
                            data-stok="{{ $alat->stok_tersedia }}" data-harga="{{ $alat->harga_sewa }}"
                            class="flex items-center gap-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs px-3.5 py-2.5 rounded-xl transition">
                            <i data-lucide="hand-coins" class="w-4 h-4"></i>Pinjam
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200 rounded-2xl p-10 text-center">
                <p class="font-bold text-slate-500">Tidak ada alat tersedia</p>
                <p class="text-xs text-slate-400 mt-0.5">Coba ubah kata kunci pencarian atau kategori</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 bg-white">
        {{ $alats->links() }}
    </div>

    <!-- Modal Pengajuan -->
    <div id="modal-ajukan" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-ajukan')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-lg text-slate-900">Ajukan Peminjaman</h3>
                <button onclick="closeModal('modal-ajukan')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <p class="text-xs text-slate-400 mb-4" id="ajukan-info"></p>
            <form action="" id="form-ajukan" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal pinjam</label>
                            <input type="date" name="tanggal_pinjam" required min="{{ now()->toDateString() }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jatuh tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" required min="{{ now()->addDay()->toDateString() }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah unit</label>
                        <input type="number" name="jumlah" required min="1" value="1"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Tujuan penggunaan alat" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <p class="text-xs text-slate-400">Pengajuan akan diverifikasi jaminan dan persetujuannya oleh petugas.</p>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modal-ajukan')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition">Kirim Pengajuan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function openAjukan(btn) {
            document.getElementById('ajukan-info').textContent =
                'Alat: ' + btn.dataset.nama + ' (' + btn.dataset.stok + ' unit tersedia, Rp' + btn.dataset.harga + '/hari)';
            document.getElementById('form-ajukan').action = '/peminjam/katalog/' + btn.dataset.id + '/ajukan';
            openModal('modal-ajukan');
        }
    </script>
@endpush
