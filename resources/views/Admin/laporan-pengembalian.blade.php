@extends('Layout.admin')
@section('title', 'Laporan Pengembalian')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Pengembalian</h1>
        <p class="text-sm text-slate-500 mt-0.5">Rekap pengembalian, kondisi unit, dan denda terkait</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4">
        <form action="{{ route('laporan/pengembalian') }}" method="GET" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Dari</label>
                <input type="date" name="awal" value="{{ request('awal', $awal->toDateString()) }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Sampai</label>
                <input type="date" name="akhir" value="{{ request('akhir', $akhir->toDateString()) }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Kondisi</label>
                <select name="kondisi" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    @foreach (['baik', 'rusak', 'hilang'] as $kondisi)
                        <option value="{{ $kondisi }}" @selected(request('kondisi') == $kondisi)>{{ \App\Support\Status::label($kondisi) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
        </form>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-6 gap-3 mb-4">
        @foreach ([
            'total' => 'Total Pengembalian',
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
        ] as $key => $label)
            <div class="bg-white border border-slate-200 rounded-2xl p-4">
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                <p class="text-lg font-extrabold text-slate-900 mt-2">{{ number_format($ringkasan[$key], 0, ',', '.') }}</p>
            </div>
        @endforeach
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Total Denda</p>
            <p class="text-lg font-extrabold text-slate-900 mt-2">Rp{{ number_format($ringkasan['total_denda'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Tanggal</th>
                        <th class="px-4 py-3 font-bold">Kode</th>
                        <th class="px-4 py-3 font-bold">Peminjam</th>
                        <th class="px-4 py-3 font-bold">Alat</th>
                        <th class="px-4 py-3 font-bold text-center">Kondisi</th>
                        <th class="px-4 py-3 font-bold text-center">Terlambat</th>
                        <th class="px-4 py-3 font-bold text-right">Denda</th>
                        <th class="px-4 py-3 font-bold">Diproses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $pengembalian)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 text-[13px] font-semibold text-slate-700">{{ $pengembalian->tanggal_kembali->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3"><span class="font-mono text-[13px] text-slate-600">{{ $pengembalian->peminjaman?->kode_peminjaman }}</span></td>
                            <td class="px-4 py-3 text-[13px] text-slate-700">{{ $pengembalian->peminjaman?->user?->nama }}</td>
                            <td class="px-4 py-3 text-[13px] text-slate-700">{{ $pengembalian->alat?->nama_alat }} <span class="text-slate-400">x{{ $pengembalian->jumlah_kembali }}</span></td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ match ($pengembalian->kondisi_alat) { 'baik' => 'bg-emerald-50 text-emerald-700', 'rusak' => 'bg-amber-50 text-amber-700', default => 'bg-red-50 text-red-700' } }}">{{ \App\Support\Status::label($pengembalian->kondisi_alat) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-[13px] {{ $pengembalian->hari_terlambat > 0 ? 'text-red-600 font-bold' : 'text-slate-500' }}">{{ $pengembalian->hari_terlambat }} hari</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp{{ number_format($pengembalian->total_denda, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-[12px] text-slate-500">{{ $pengembalian->diprosesOleh?->nama ?? '-' }}</td>
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
