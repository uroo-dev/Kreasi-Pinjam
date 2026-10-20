@extends('Layout.admin')
@section('title', 'Denda Saya')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Denda Saya</h1>
        <p class="text-sm text-slate-500 mt-0.5">Rincian denda keterlambatan, kerusakan, atau kehilangan alat</p>
    </div>

    @if ($tanggungan['ada'])
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i></span>
            <div>
                <p class="font-bold text-red-800">Denda belum lunas</p>
                <p class="text-sm text-red-700 mt-0.5">Lunasi denda sebesar Rp{{ number_format($tanggungan['denda_belum_lunas'], 0, ',', '.') }} kepada petugas agar dapat kembali mengajukan peminjaman.</p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/70 text-[11px] uppercase tracking-wider">
                        <th class="px-4 py-3 font-bold">Tanggal</th>
                        <th class="px-4 py-3 font-bold">Jenis</th>
                        <th class="px-4 py-3 font-bold">Keterangan</th>
                        <th class="px-4 py-3 font-bold text-right">Nominal</th>
                        <th class="px-4 py-3 font-bold text-right">Terbayar</th>
                        <th class="px-4 py-3 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dendas as $denda)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 text-[13px] font-semibold text-slate-700">{{ $denda->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-600">{{ \App\Support\Status::label($denda->jenis_denda) }}</span>
                            </td>
                            <td class="px-4 py-3 text-[13px] text-slate-600 max-w-[280px]">{{ $denda->keterangan }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp{{ number_format($denda->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-600">Rp{{ number_format($denda->dibayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::denda($denda->status) }}">{{ \App\Support\Status::label($denda->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400">Tidak ada denda, terima kasih atas kerapian Anda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $dendas->links() }}
        </div>
    </div>
@endsection
