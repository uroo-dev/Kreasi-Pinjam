@extends('Layout.admin')
@section('title', 'Laporan Peminjaman')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-0.5">Rekap pengajuan dan transaksi peminjaman dalam periode terpilih</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4">
        <form action="{{ route('laporan/peminjaman') }}" method="GET" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Dari</label>
                <input type="date" name="awal" value="{{ request('awal', $awal->toDateString()) }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Sampai</label>
                <input type="date" name="akhir" value="{{ request('akhir', $akhir->toDateString()) }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Status</label>
                <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    @foreach (['menunggu', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak', 'dibatalkan'] as $status)
                        <option value="{{ $status }}" @selected(request('status') == $status)>{{ \App\Support\Status::label($status) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
        </form>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-5 gap-3 mb-4">
        @foreach ([
            'total' => ['Total Transaksi', $ringkasan['total'] ?? 0],
            'diproses' => ['Sedang Diproses', $ringkasan['diproses'] ?? 0],
            'selesai' => ['Selesai', $ringkasan['selesai'] ?? 0],
            'ditolak' => ['Ditolak/Batal', $ringkasan['ditolak'] ?? 0],
            'nilai_total' => ['Nilai Transaksi', $ringkasan['nilai_total'] ?? 0, 'rupiah'],
        ] as $key => $card)
            <div class="bg-white border border-slate-200 rounded-2xl p-4">
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">{{ $card[0] }}</p>
                <p class="text-lg font-extrabold text-slate-900 mt-2">
                    {{ ($card[2] ?? null) === 'rupiah' ? 'Rp' . number_format($card[1], 0, ',', '.') : number_format($card[1], 0, ',', '.') }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Kode</th>
                        <th class="px-4 py-3 font-bold">Peminjam</th>
                        <th class="px-4 py-3 font-bold">Alat</th>
                        <th class="px-4 py-3 font-bold">Periode</th>
                        <th class="px-4 py-3 font-bold text-right">Nilai</th>
                        <th class="px-4 py-3 font-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $peminjaman)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3"><span class="font-mono text-[13px] font-bold text-slate-700">{{ $peminjaman->kode_peminjaman }}</span>
                                <p class="text-[11px] text-slate-400">{{ $peminjaman->created_at->format('d M Y') }}</p></td>
                            <td class="px-4 py-3 text-[13px] font-semibold text-slate-700">{{ $peminjaman->user?->nama }}</td>
                            <td class="px-4 py-3">
                                @foreach ($peminjaman->details as $detail)
                                    <p class="text-[12px] text-slate-600">{{ $detail->alat->nama_alat }} <span class="text-slate-400">x{{ $detail->jumlah }}</span></p>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-[12px] text-slate-600">{{ $peminjaman->tanggal_pinjam->translatedFormat('d M Y') }} - {{ $peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp{{ number_format($peminjaman->details->sum('subtotal'), 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center"><span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::peminjaman($peminjaman->status) }}">{{ \App\Support\Status::label($peminjaman->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400">Tidak ada data pada periode ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100">{{ $list->links() }}</div>
    </div>
@endsection
