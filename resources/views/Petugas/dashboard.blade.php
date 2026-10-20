@extends('Layout.admin')
@section('title', 'Dashboard Petugas')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Petugas</h1>
        <p class="text-sm text-slate-500 mt-0.5">Pantau verifikasi jaminan, penyewaan aktif, dan denda</p>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-3 gap-3 mb-4">
        <a href="{{ route('petugas/persetujuan/get') }}" class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-emerald-400 transition">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="stamp" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Menunggu jaminan</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $stats['menungguJaminan'] }} <span class="text-xs font-semibold text-slate-400">pengajuan</span></p>
        </a>
        <a href="{{ route('petugas/persetujuan/get') }}?status=disetujui" class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-emerald-400 transition">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="package-open" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Siap diserahkan</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $stats['siapDiserahkan'] }} <span class="text-xs font-semibold text-slate-400">transaksi</span></p>
        </a>
        <a href="{{ route('petugas/pengembalian/get') }}" class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-emerald-400 transition">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="repeat" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Sedang dipinjam</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $stats['dipinjam'] }} <span class="text-xs font-semibold text-slate-400">unit transaksi</span></p>
        </a>
        <a href="{{ route('petugas/pengembalian/get') }}" class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-emerald-400 transition">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="calendar-clock" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Jatuh tempo hari ini</span></div>
            <p class="text-lg font-extrabold {{ $stats['jatuhTempoHariIni'] > 0 ? 'text-red-600' : 'text-slate-900' }} mt-2">{{ $stats['jatuhTempoHariIni'] }} <span class="text-xs font-semibold text-slate-400">transaksi</span></p>
        </a>
        <a href="{{ route('petugas/denda/get') }}" class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-emerald-400 transition">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="wallet" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Denda belum lunas</span></div>
            <p class="text-lg font-extrabold {{ $stats['dendaBelumLunas'] > 0 ? 'text-red-600' : 'text-slate-900' }} mt-2">Rp{{ number_format($stats['dendaBelumLunas'], 0, ',', '.') }}</p>
        </a>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="receipt" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Denda lunas hari ini</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">Rp{{ number_format($stats['dendaLunasHariIni'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid xl:grid-cols-2 gap-4 mb-4">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <p class="p-5 pb-3 font-bold text-slate-900">Pengajuan terbaru</p>
            <div class="px-5 pb-5">
                @forelse ($pengajuanTerbaru as $peminjaman)
                    <div class="flex items-center justify-between gap-3 py-3 border-t border-slate-100 first:border-0">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800">{{ $peminjaman->user->nama }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $peminjaman->kode_peminjaman }} - {{ $peminjaman->details->map(fn ($detail) => $detail->alat->nama_alat)->implode(', ') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::peminjaman($peminjaman->status) }}">{{ \App\Support\Status::label($peminjaman->status) }}</span>
                            <p class="text-[11px] text-slate-400 mt-1">{{ $peminjaman->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-6 text-center">Tidak ada pengajuan</p>
                @endforelse
            </div>
            <a href="{{ route('petugas/persetujuan/get') }}" class="block px-5 py-3 border-t border-slate-100 text-xs font-bold text-emerald-700 hover:bg-slate-50">Kelola semua persetujuan</a>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <p class="p-5 pb-3 font-bold text-slate-900">Alat paling sering dipinjam</p>
            <div class="px-5 pb-5">
                @forelse ($alatSeringDipinjam as $item)
                    <div class="flex items-center justify-between py-3 border-t border-slate-100 first:border-0">
                        <p class="text-sm font-semibold text-slate-700">{{ $item->nama_alat }}</p>
                        <span class="text-xs font-bold text-emerald-700">{{ $item->total_pinjam }} unit</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-6 text-center">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <p class="p-5 pb-3 font-bold text-slate-900">Peminjam paling aktif</p>
        <div class="px-5 pb-5">
            @forelse ($peminjamAktif as $item)
                <div class="flex items-center justify-between py-3 border-t border-slate-100 first:border-0">
                    <p class="text-sm font-semibold text-slate-700">{{ $item->nama }}</p>
                    <span class="text-xs font-bold text-emerald-700">{{ $item->jumlah_pinjaman }} peminjaman</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 py-6 text-center">Belum ada data</p>
            @endforelse
        </div>
    </div>
@endsection
