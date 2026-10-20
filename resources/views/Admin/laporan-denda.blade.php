@extends('Layout.admin')
@section('title', 'Laporan Denda')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Denda</h1>
        <p class="text-sm text-slate-500 mt-0.5">Rekap denda keterlambatan, kerusakan, dan kehilangan</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4">
        <form action="{{ route('laporan/denda') }}" method="GET" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Dari</label>
                <input type="date" name="awal" value="{{ request('awal', $awal->toDateString()) }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Sampai</label>
                <input type="date" name="akhir" value="{{ request('akhir', $akhir->toDateString()) }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Jenis</label>
                <select name="jenis" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    @foreach (['terlambat', 'rusak', 'hilang'] as $jenis)
                        <option value="{{ $jenis }}" @selected(request('jenis') == $jenis)>{{ \App\Support\Status::label($jenis) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Status</label>
                <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    <option value="belum_lunas" @selected(request('status') == 'belum_lunas')>Belum Lunas</option>
                    <option value="lunas" @selected(request('status') == 'lunas')>Lunas</option>
                </select>
            </div>
            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
        </form>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Total Denda</p>
            <p class="text-lg font-extrabold text-slate-900 mt-2">Rp{{ number_format($ringkasan['total_denda'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Terbayar</p>
            <p class="text-lg font-extrabold text-emerald-700 mt-2">Rp{{ number_format($ringkasan['terbayar'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Belum Lunas</p>
            <p class="text-lg font-extrabold text-red-600 mt-2">Rp{{ number_format($ringkasan['belum_lunas'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Jumlah Denda</p>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ number_format($ringkasan['jumlah_denda'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-4">
        @foreach (['terlambat', 'rusak', 'hilang'] as $jenis)
            <div class="bg-white border border-slate-200 rounded-2xl p-4">
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">{{ \App\Support\Status::label($jenis) }}</p>
                <p class="text-lg font-extrabold text-slate-900 mt-2">Rp{{ number_format($perJenis[$jenis], 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Tanggal</th>
                        <th class="px-4 py-3 font-bold">Peminjam</th>
                        <th class="px-4 py-3 font-bold">Kode</th>
                        <th class="px-4 py-3 font-bold">Jenis</th>
                        <th class="px-4 py-3 font-bold">Keterangan</th>
                        <th class="px-4 py-3 font-bold text-right">Nominal</th>
                        <th class="px-4 py-3 font-bold text-right">Terbayar</th>
                        <th class="px-4 py-3 font-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $denda)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 text-[13px] text-slate-600">{{ $denda->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-[13px] font-semibold text-slate-700">{{ $denda->user?->nama }}</td>
                            <td class="px-4 py-3"><span class="font-mono text-[12px] text-slate-500">{{ $denda->pengembalian?->peminjaman?->kode_peminjaman ?? '-' }}</span></td>
                            <td class="px-4 py-3"><span class="text-[11px] font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-600">{{ \App\Support\Status::label($denda->jenis_denda) }}</span></td>
                            <td class="px-4 py-3 text-[12px] text-slate-600 max-w-[240px]">{{ $denda->keterangan }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp{{ number_format($denda->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-600">Rp{{ number_format($denda->dibayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center"><span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::denda($denda->status) }}">{{ \App\Support\Status::label($denda->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-sm text-slate-400">Tidak ada data pada periode ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100">{{ $list->links() }}</div>
    </div>
@endsection
