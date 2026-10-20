@extends('Layout.admin')
@section('title', 'Pengembalian Alat')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Pengembalian Alat</h1>
        <p class="text-sm text-slate-500 mt-0.5">Peminjaman aktif - urut dari jatuh tempo terdekat</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4 flex flex-wrap gap-2 items-center">
        <form action="{{ route('petugas/pengembalian/get') }}" method="GET" class="relative">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Kode atau nama peminjam..."
                class="w-64 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </form>
        <form action="{{ route('petugas/pengembalian/get') }}" method="GET">
            <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2 rounded-xl transition">Reset</button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Kode</th>
                        <th class="px-4 py-3 font-bold">Peminjam</th>
                        <th class="px-4 py-3 font-bold">Alat</th>
                        <th class="px-4 py-3 font-bold">Jatuh tempo</th>
                        <th class="px-4 py-3 font-bold text-center">Status</th>
                        <th class="px-4 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $peminjaman)
                        @php
                            $selisih = now()->startOfDay()->gt($peminjaman->tanggal_jatuh_tempo->copy())
                                ? ($peminjaman->tanggal_jatuh_tempo->diffInDays(now()->startOfDay()) + 1)
                                : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3"><span class="font-mono text-[13px] font-bold text-slate-700">{{ $peminjaman->kode_peminjaman }}</span></td>
                            <td class="px-4 py-3">
                                <p class="text-[13px] font-semibold text-slate-700">{{ $peminjaman->user->nama }}</p>
                                <p class="text-[11px] text-slate-400">{{ $peminjaman->user->telepon ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @foreach ($peminjaman->details as $detail)
                                    <p class="text-[13px] text-slate-700">{{ $detail->alat->nama_alat }} <span class="text-slate-400">x{{ $detail->jumlah }}</span></p>
                                @endforeach
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-[13px] font-semibold {{ $peminjaman->sudahJatuhTempo() ? 'text-red-600' : 'text-slate-700' }}">{{ $peminjaman->sudahJatuhTempo() ? 'Hari ini' : $peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</p>
                                @if ($peminjaman->sudahJatuhTempo())<p class="text-[11px] text-red-500 font-bold">Terlambat 1 hari</p>
                                @else<p class="text-[11px] text-slate-400">{{ $peminjaman->tanggal_jatuh_tempo->diffInDays(now()->startOfDay()->copy()) }} hari lagi</p>@endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ $peminjaman->sudahJatuhTempo() ? 'bg-red-50 text-red-700' : 'bg-sky-50 text-sky-700' }}">
                                    {{ $peminjaman->sudahJatuhTempo() ? 'Terlambat' : 'Dipinjam' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('petugas/pengembalian/buat', $peminjaman) }}"
                                    class="text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 px-3.5 py-2 rounded-xl transition">Proses</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400">Tidak ada peminjaman aktif</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $list->links() }}
        </div>
    </div>
@endsection
