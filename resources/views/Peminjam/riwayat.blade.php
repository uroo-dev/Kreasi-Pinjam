@extends('Layout.admin')
@section('title', 'Riwayat Peminjaman')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-0.5">Semua pengajuan dan transaksi peminjaman Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl p-4 text-sm font-semibold text-red-600">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4 flex flex-wrap items-center gap-2">
        <form action="{{ route('peminjam/riwayat/get') }}" method="GET" class="flex flex-wrap gap-2">
            <select name="status"
                class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua status</option>
                @foreach (['menunggu', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak', 'dibatalkan'] as $status)
                    <option value="{{ $status }}" @selected(request('status') == $status)>{{ \App\Support\Status::label($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
        </form>
        <a href="{{ route('peminjam/katalog/get') }}" class="ml-auto flex items-center gap-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs px-3.5 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>Pengajuan Baru
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Kode</th>
                        <th class="px-4 py-3 font-bold">Alat</th>
                        <th class="px-4 py-3 font-bold">Periode</th>
                        <th class="px-4 py-3 font-bold">Jaminan</th>
                        <th class="px-4 py-3 font-bold">Status</th>
                        <th class="px-4 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $peminjaman)
                        @php $jaminan = $peminjaman->jaminan; @endphp
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3">
                                <p class="font-mono font-bold text-slate-800">{{ $peminjaman->kode_peminjaman }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @foreach ($peminjaman->details as $detail)
                                    <p class="text-[13px] font-semibold text-slate-700">{{ $detail->alat->nama_alat }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $detail->jumlah }} x Rp{{ number_format($detail->alat->harga_sewa, 0, ',', '.') }}/hari</p>
                                @endforeach
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-[13px] font-semibold text-slate-700">{{ $peminjaman->tanggal_pinjam->translatedFormat('d M Y') }}</p>
                                <p class="text-[11px] text-slate-400">
                                    s/d {{ $peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y') }}
                                    @if ($peminjaman->isDipinjam() && now()->gt($peminjaman->tanggal_jatuh_tempo))
                                        <span class="text-red-600 font-bold">terlambat</span>
                                    @endif
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                @if ($jaminan)
                                    <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::jaminan($jaminan->status) }}">{{ \App\Support\Status::label($jaminan->status) }}</span>
                                    <p class="text-[11px] text-slate-400 mt-1 capitalize">{{ $jaminan->jenis }}: {{ Str::limit($jaminan->keterangan, 22) }}</p>
                                @else
                                    <span class="text-[11px] text-slate-400">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::peminjaman($peminjaman->status) }}">{{ \App\Support\Status::label($peminjaman->status) }}</span>
                                @if ($peminjaman->status === 'ditolak' && $peminjaman->catatan)
                                    <p class="text-[11px] text-red-500 mt-1">Alasan: {{ $peminjaman->catatan }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($peminjaman->isMenunggu())
                                    <form action="{{ route('peminjam/riwayat/batalkan', $peminjaman) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan ini?')">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-red-600 hover:bg-red-50 px-3 py-2 rounded-xl transition">Batalkan</button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400">Belum ada peminjaman</td>
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
