@extends('Layout.admin')
@section('title', 'Dashboard')
@section('role_label', 'ADMIN')
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ringkasan data aplikasi peminjaman alat, {{ now()->format('j F Y') }}</p>
        </div>
    </div>
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center"><i data-lucide="users" class="w-5 h-5 text-emerald-700"></i></span>
            </div>
            <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">{{ number_format($stats['totalPengguna'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Total pengguna terdaftar</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center"><i data-lucide="package" class="w-5 h-5 text-sky-600"></i></span>
            </div>
            <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">{{ number_format($stats['totalAlat'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Total alat terdaftar</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center"><i data-lucide="layout-grid" class="w-5 h-5 text-orange-600"></i></span>
            </div>
            <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">{{ number_format($stats['totalKategori'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Total kategori</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center"><i data-lucide="shopping-bag" class="w-5 h-5 text-emerald-700"></i></span>
            </div>
            <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">{{ number_format($stats['totalPeminjaman'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Total transaksi peminjaman</p>
        </div>
    </div>

    <div class="grid xl:grid-cols-3 gap-4 mt-4">
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between p-5 pb-4">
                <div>
                    <h2 class="font-bold text-slate-900">Transaksi Terbaru</h2>
                    <p class="text-xs text-slate-400 mt-0.5">5 peminjaman terakhir yang tercatat</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[640px]">
                    <thead>
                        <tr class="bg-slate-50 text-left text-xs text-slate-500 font-semibold">
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Peminjam</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transaksiTerbaru as $transaksi)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $transaksi->kode_peminjaman }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $transaksi->user->nama ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $transaksi->tanggal_pinjam?->format('j M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $warna = match ($transaksi->status) {
                                            'menunggu' => 'bg-amber-50 text-amber-700',
                                            'disetujui' => 'bg-blue-50 text-blue-700',
                                            'ditolak', 'dibatalkan' => 'bg-red-50 text-red-700',
                                            'dipinjam' => 'bg-sky-50 text-sky-700',
                                            'dikembalikan' => 'bg-emerald-50 text-emerald-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $warna }}">{{ ucfirst($transaksi->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center">
                                    <p class="font-bold text-slate-500">Belum ada transaksi</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Peminjaman yang masuk akan tampil di sini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <h2 class="font-bold text-slate-900">Ringkasan Stok</h2>
                <p class="text-xs text-slate-400 mt-0.5 mb-4">Perbandingan stok tersedia terhadap stok total</p>
                <div class="flex items-end justify-between mb-2">
                    <p class="text-2xl font-extrabold text-slate-900">{{ number_format($stats['stokTersedia'], 0, ',', '.') }}</p>
                    <p class="text-sm font-semibold text-slate-400">/ {{ number_format($stats['stokTotal'], 0, ',', '.') }} unit</p>
                </div>
                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $stats['stokTotal'] > 0 && $stats['stokTersedia'] / max($stats['stokTotal'], 1) >= 0.5 ? 'bg-emerald-600' : 'bg-orange-500' }}"
                        style="width: {{ $stats['stokTotal'] > 0 ? min(100, round($stats['stokTersedia'] / $stats['stokTotal'] * 100)) : 0 }}%"></div>
                </div>
                <p class="text-xs text-slate-400 mt-3">{{ $stats['peminjamanMenunggu'] }} pengajuan menunggu persetujuan</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <h2 class="font-bold text-slate-900">Alat per Kategori</h2>
                <p class="text-xs text-slate-400 mt-0.5 mb-4">4 kategori dengan alat terbanyak</p>
                <div class="space-y-3 text-xs font-semibold text-slate-600">
                    @forelse ($alatPerKategori as $kategori)
                        @php
                            $maksimal = $alatPerKategori->first()->alat_count ?? 1;
                            $persen = $maksimal > 0 ? round($kategori->alat_count / $maksimal * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-700"></span>{{ $kategori->nama_kategori }}</p>
                                <span class="text-slate-400">{{ $kategori->alat_count }} alat</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400">Belum ada kategori</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
