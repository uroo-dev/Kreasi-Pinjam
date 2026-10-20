@extends('Layout.admin')
@section('title', 'Persetujuan Peminjaman')
@section('content')
    <div class="mb-6">
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Persetujuan Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-0.5">Verifikasi jaminan, setujui, tolak, dan tandai pengambilan alat</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4 flex flex-wrap gap-2 items-center">
        <form action="{{ route('petugas/persetujuan/get') }}" method="GET" class="flex gap-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Kode atau nama peminjam..."
                    class="w-56 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select name="status"
                class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                <option value="disetujui" @selected(request('status') == 'disetujui')>Disetujui</option>
                <option value="dipinjam" @selected(request('status') == 'dipinjam')>Dipinjam</option>
                <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                <option value="dikembalikan" @selected(request('status') == 'dikembalikan')>Dikembalikan</option>
            </select>
            <button type="submit" class="bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition">Terapkan</button>
        </form>
    </div>

    @forelse ($list as $peminjaman)
        @php $jaminan = $peminjaman->jaminan; @endphp
        <div class="bg-white border border-slate-200 rounded-2xl mb-3 overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-2 p-5 border-b border-slate-100 bg-slate-50/50">
                <div>
                    <p class="font-mono font-bold text-slate-800 text-sm">{{ $peminjaman->kode_peminjaman }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $peminjaman->user->nama }} - diajukan {{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::peminjaman($peminjaman->status) }}">{{ \App\Support\Status::label($peminjaman->status) }}</span>
                    @if ($peminjaman->status === 'dipinjam')
                        <span class="text-[11px] text-slate-500">Jatuh tempo {{ $peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="p-5 grid md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Detail peminjaman</p>
                    <ul class="space-y-2">
                        @foreach ($peminjaman->details as $detail)
                            <li class="flex items-center justify-between text-sm">
                                <span><i data-lucide="wrench" class="w-3.5 h-3.5 inline-block mr-1 text-slate-400"></i>{{ $detail->alat->nama_alat }}
                                    <span class="text-[11px] text-slate-400">({{ $detail->alat->kode_alat }})</span></span>
                                <span class="font-semibold text-slate-700">{{ $detail->jumlah }} x Rp{{ number_format($detail->alat->harga_sewa, 0, ',', '.') }}/hari</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500 space-y-0.5">
                        <p>Pinjam: <span class="font-semibold text-slate-700">{{ $peminjaman->tanggal_pinjam->translatedFormat('d M Y') }}</span></p>
                        <p>Jatuh tempo: <span class="font-semibold text-slate-700">{{ $peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</span></p>
                        @if ($peminjaman->catatan)
                            <p>Catatan: <span class="italic">{{ $peminjaman->catatan }}</span></p>
                        @endif
                        <p>Estimasi nilai: <span class="font-bold text-slate-700">Rp{{ number_format($peminjaman->details->sum('subtotal'), 0, ',', '.') }}</span></p>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Jaminan</p>
                    @if ($jaminan)
                        <div class="text-sm space-y-2">
                            <p class="capitalize">{{ $jaminan->jenis }}: <span class="font-semibold text-slate-700">{{ $jaminan->keterangan }}</span></p>
                            @if ($jaminan->nominal)
                                <p>Nominal: <span class="font-semibold text-slate-700">Rp{{ number_format($jaminan->nominal, 0, ',', '.') }}</span></p>
                            @endif
                            @if ($jaminan->bukti)
                                <p><a href="{{ Storage::url($jaminan->bukti) }}" target="_blank" class="text-emerald-700 font-bold text-xs">Lihat bukti</a></p>
                            @endif
                            <p>
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ \App\Support\Status::jaminan($jaminan->status) }}">{{ \App\Support\Status::label($jaminan->status) }}</span>
                            </p>
                        </div>
                    @else
                        <p class="text-sm text-slate-400">Belum ada data jaminan</p>
                    @endif

                    @if ($peminjaman->status === 'menunggu')
                        <form action="{{ route('petugas/jaminan/store', $peminjaman) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis</label>
                                    <select name="jenis" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="jaminan">Jaminan (barang/identitas)</option>
                                        <option value="pembayaran">Pembayaran (deposit/dp)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nominal (jika pembayaran)</label>
                                    <input type="number" name="nominal" step="0.01" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan</label>
                                <input type="text" name="keterangan" required placeholder="cth: 1 unit motor + KTP" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Bukti (opsional)</label>
                                <input type="file" name="bukti" accept="image/png,image/jpeg,application/pdf" class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-bold">
                            </div>
                            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Catat Jaminan</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-wrap gap-2">
                @if ($peminjaman->status === 'menunggu')
                    @if ($jaminan && $jaminan->status === 'menunggu')
                        <form action="{{ route('petugas/jaminan/terima', $peminjaman) }}" method="POST">
                            @csrf
                            <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Terima Jaminan</button>
                        </form>
                        <form action="{{ route('petugas/jaminan/tolak', $peminjaman) }}" method="POST">
                            @csrf
                            <button class="bg-white border border-slate-200 hover:border-red-300 text-red-600 text-xs font-bold px-4 py-2.5 rounded-xl transition">Tolak Jaminan</button>
                        </form>
                    @elseif ($jaminan && $jaminan->status === 'diterima')
                        <form action="{{ route('petugas/peminjaman/setujui', $peminjaman) }}" method="POST">
                            @csrf
                            <button class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Setujui &amp; Kurangi Stok</button>
                        </form>
                    @else
                        <p class="text-xs text-slate-400 self-center">Catat dan terima jaminan terlebih dahulu sebelum menyetujui</p>
                    @endif
                    <form action="{{ route('petugas/peminjaman/tolak', $peminjaman) }}" method="POST" class="flex-1 flex flex-wrap items-center gap-2 justify-end">
                        @csrf
                        <input type="hidden" name="catatan" value="Ditolak petugas pada {{ now()->translatedFormat('d M Y') }}">
                        <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Tolak</button>
                    </form>
                @elseif ($peminjaman->status === 'disetujui')
                    <form action="{{ route('petugas/peminjaman/serahkan', $peminjaman) }}" method="POST" class="ml-auto">
                        @csrf
                        <button class="bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Serahkan Alat</button>
                    </form>
                @elseif ($peminjaman->status === 'dipinjam')
                    <p class="text-xs text-slate-400">Unit sudah dipinjam - proses pengembalian di menu Pengembalian</p>
                @else
                    <p class="text-xs text-slate-400">Selesai diproses</p>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center">
            <p class="font-bold text-slate-500">Tidak ada data persetujuan</p>
            <p class="text-xs text-slate-400 mt-0.5">Coba ubah filter status atau kata kunci</p>
        </div>
    @endforelse

    <div class="bg-white mt-4">{{ $list->links() }}</div>
@endsection
