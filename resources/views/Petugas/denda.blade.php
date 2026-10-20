@extends('Layout.admin')
@section('title', 'Kelola Denda')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Denda</h1>
        <p class="text-sm text-slate-500 mt-0.5">Catat pembayaran denda peminjam</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4 flex flex-wrap gap-2 items-center">
        <form action="{{ route('petugas/denda/get') }}" method="GET" class="flex flex-wrap gap-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama peminjam..."
                    class="w-56 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua status</option>
                <option value="belum_lunas" @selected(request('status') == 'belum_lunas')>Belum Lunas</option>
                <option value="lunas" @selected(request('status') == 'lunas')>Lunas</option>
            </select>
            <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
            @if (request()->filled('status') || request()->filled('cari'))
                <a href="{{ route('petugas/denda/get') }}" class="text-xs font-bold text-slate-500 self-center ms-2">Hapus filter</a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Tanggal</th>
                        <th class="px-4 py-3 font-bold">Peminjam</th>
                        <th class="px-4 py-3 font-bold">Jenis</th>
                        <th class="px-4 py-3 font-bold">Keterangan</th>
                        <th class="px-4 py-3 font-bold text-right">Nominal</th>
                        <th class="px-4 py-3 font-bold text-right">Terbayar</th>
                        <th class="px-4 py-3 font-bold">Status</th>
                        <th class="px-4 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $denda)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 text-[13px] text-slate-600">{{ $denda->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <p class="text-[13px] font-bold text-slate-800">{{ $denda->user?->nama }}</p>
                                <p class="text-[11px] text-slate-400">{{ $denda->pengembalian?->peminjaman?->kode_peminjaman ?? ($denda->pengembalian?->peminjaman_id ? '#' . $denda->pengembalian->peminjaman_id : '-') }}</p>
                            </td>
                            <td class="px-4 py-3"><span class="text-[11px] font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-600">{{ \App\Support\Status::label($denda->jenis_denda) }}</span></td>
                            <td class="px-4 py-3 text-[13px] text-slate-600 max-w-[240px]">{{ $denda->keterangan }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp{{ number_format($denda->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-600">Rp{{ number_format($denda->dibayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-3"><span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::denda($denda->status) }}">{{ \App\Support\Status::label($denda->status) }}</span></td>
                            <td class="px-4 py-3 text-right">
                                @if ($denda->status === 'belum_lunas')
                                    <button onclick="bukaBayar(this)" data-id="{{ $denda->id }}" data-nama="{{ $denda->user?->nama }}" data-sisa="{{ $denda->nominal - $denda->dibayar }}"
                                        class="text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 px-3.5 py-2 rounded-xl transition">Bayar</button>
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-400">Tidak ada data denda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $list->links() }}
        </div>
    </div>

    <div id="modal-bayar" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeModal('modal-bayar')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-lg text-slate-900">Catat Pembayaran</h3>
                <button onclick="closeModal('modal-bayar')" class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400" aria-label="Tutup"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <p class="text-xs text-slate-400 mb-4" id="bayar-info"></p>
            <form action="" id="form-bayar" method="POST">
                @csrf
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah dibayar</label>
                <input type="number" name="jumlah" id="input-jumlah" step="0.01" min="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('modal-bayar')" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function bukaBayar(btn) {
            document.getElementById('bayar-info').textContent = 'Denda ' + btn.dataset.nama + ' - sisa Rp' + Number(btn.dataset.sisa).toLocaleString('id-ID');
            document.getElementById('form-bayar').action = '/petugas/denda/' + btn.dataset.id + '/bayar';
            document.getElementById('input-jumlah').value = btn.dataset.sisa;
            openModal('modal-bayar');
        }
    </script>
@endpush
