@extends('Layout.admin')
@section('title', 'Dashboard Peminjam')
@section('content')
    <div class="flex flex-wrap items-end justify-between gap-2 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ringkasan peminjaman dan tanggungan Anda</p>
        </div>
        <a href="{{ route('peminjam/katalog/get') }}" class="flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>Pinjam Alat
        </a>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="hourglass" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Menunggu</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $stats['menunggu'] }} <span class="text-xs font-semibold text-slate-400">pengajuan</span></p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="package-check" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Aktif dipinjam</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $stats['dipinjam'] }} <span class="text-xs font-semibold text-slate-400">transaksi</span></p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="check-circle-2" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Selesai</span></div>
            <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $stats['selesai'] }} <span class="text-xs font-semibold text-slate-400">dikembalikan</span></p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 text-slate-400"><i data-lucide="wallet" class="w-4 h-4"></i><span class="text-xs font-semibold uppercase tracking-wide">Denda belum lunas</span></div>
            <p class="text-lg font-extrabold {{ $stats['denda'] > 0 ? 'text-red-600' : 'text-slate-900' }} mt-2">Rp{{ number_format($stats['denda'], 0, ',', '.') }}</p>
        </div>
    </div>

    @if ($tanggungan['ada'])
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i></span>
            <div>
                <p class="font-bold text-amber-800">Anda sedang memiliki tanggungan</p>
                <p class="text-sm text-amber-700 mt-0.5">{{ $tanggungan['melewati_jatuh_tempo'] }} peminjaman melewati jatuh tempo, dan denda belum lunas Rp{{ number_format($tanggungan['denda_belum_lunas'], 0, ',', '.') }}. Pengajuan baru diblokir sampai tanggungan diselesaikan.</p>
            </div>
        </div>
    @endif

    <div class="grid xl:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <p class="p-5 pb-3 font-bold text-slate-900">Riwayat peminjaman terbaru</p>
            <div class="px-5 pb-5">
                @forelse ($riwayatTerbaru as $peminjaman)
                    <div class="flex items-center justify-between gap-3 py-3 border-t border-slate-100 first:border-0">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800">{{ $peminjaman->kode_peminjaman }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $peminjaman->details->map(fn ($detail) => $detail->alat->nama_alat . ' (' . $detail->jumlah . ')')->implode(', ') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::peminjaman($peminjaman->status) }}">{{ \App\Support\Status::label($peminjaman->status) }}</span>
                            <p class="text-[11px] text-slate-400 mt-1">{{ $peminjaman->tanggal_pinjam->format('d M Y') }} - {{ $peminjaman->tanggal_jatuh_tempo->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-6 text-center">Belum ada peminjaman</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <p class="font-bold text-slate-900 mb-3">Cara kerja peminjaman</p>
            <ol class="space-y-4 text-sm">
                @foreach ([
                    'Ajukan peminjaman melalui katalog' => 'Pilih alat, isi tanggal dan jumlah unit.',
                    'Verifikasi jaminan oleh petugas' => 'Serahkan jaminan atau bukti pembayaran ke petugas.',
                    'Persetujuan pengajuan' => 'Petugas menyetujui bila jaminan sah dan stok tersedia.',
                    'Pengambilan alat' => 'Alat diserahkan di kantor; status menjadi Dipinjam.',
                    'Pengembalian' => 'Kembalikan sebelum jatuh tempo untuk menghindari denda.',
                ] as $judul => $detail)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-700 font-bold grid place-items-center text-xs">{{ $loop->iteration }}</span>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $judul }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $detail }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endsection
