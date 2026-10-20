@extends('Layout.admin')
@section('title', 'Proses Pengembalian')
@section('content')
    <div class="mb-6">
        <a href="{{ route('petugas/pengembalian/get') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 mb-2"><i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>Kembali</a>
        <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Proses Pengembalian</h1>
        <p class="text-sm text-slate-500 mt-0.5">Pilih kondisi setiap unit - denda dihitung otomatis</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl p-4 text-sm font-semibold text-red-600">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('petugas/pengembalian/simpan', $peminjaman) }}" method="POST" id="form-kembali">
        @csrf
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden mb-4">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap gap-x-6 gap-y-1 items-center justify-between">
                <div>
                    <p class="font-mono font-bold text-slate-800 text-sm">{{ $peminjaman->kode_peminjaman }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $peminjaman->user->nama }} - pinjam {{ $peminjaman->tanggal_pinjam->translatedFormat('d M Y') }}</p>
                </div>
                <p class="text-xs text-slate-500">Jatuh tempo <span class="font-bold {{ now()->gt($peminjaman->tanggal_jatuh_tempo) ? 'text-red-600' : 'text-emerald-700' }}">{{ $peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y') }}</span> - denda keterlambatan Rp{{ number_format(config('peminjaman.denda_keterlambatan_per_hari'), 0, ',', '.') }}/hari/unit</p>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach ($peminjaman->details as $index => $detail)
                    <div class="p-5 flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">{{ $detail->alat->nama_alat }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $detail->jumlah }} unit - {{ $detail->alat->kode_alat }} - Rp{{ number_format($detail->alat->harga_sewa, 0, ',', '.') }}/unit/hari</p>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3 md:w-auto w-full">
                            <div>
                                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Kondisi</label>
                                <select name="items[{{ $index }}][kondisi_alat]" data-harga="{{ $detail->alat->harga_sewa }}" data-jumlah="{{ $detail->jumlah }}" onchange="aktualisasiDenda()" class="kondisi select w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                                <input type="hidden" name="items[{{ $index }}][alat_id]" value="{{ $detail->alat->id }}">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Catatan</label>
                                <input type="text" name="items[{{ $index }}][catatan]" placeholder="Detail kerusakan, dll" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-5 border-t border-slate-100 grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Tanggal kembali</label>
                    <input type="date" name="tanggal_kembali" required value="{{ now()->toDateString() }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Catatan umum (opsional)</label>
                    <input type="text" name="catatan_umum" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="p-5 border-t border-slate-100 bg-emerald-50/50 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-800">Estimasi denda</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-0.5" id="total-denda">Rp0</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Terlambat: Rp0 - Rusak: Rp0 - Hilang: Rp0</p>
                </div>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-bold px-5 py-3 rounded-xl transition">Simpan Pengembalian</button>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        function selisihHari() {
            const input = document.querySelector('input[name="tanggal_kembali"]');
            const jatuhTempo = new Date('{{ $peminjaman->tanggal_jatuh_tempo->toDateString() }}T00:00:00');
            const kembali = new Date((input.value || '{{ now()->toDateString() }}') + 'T00:00:00');
            const selisih = Math.floor((kembali - jatuhTempo) / 86400000);
            return selisih > 0 ? selisih : 0;
        }
        function aktualisasiDenda() {
            const hari = selisihHari();
            const perHari = {{ (int) config('peminjaman.denda_keterlambatan_per_hari') }};
            const persenRusak = {{ (int) config('peminjaman.denda_kerusakan_persen') }};
            const kelipatanHilang = {{ (int) config('peminjaman.denda_kehilangan_kelipatan') }};
            let dendaTerlambat = 0, dendaRusak = 0, dendaHilang = 0;
            document.querySelectorAll('.kondisi').forEach(function(select) {
                const harga = Number(select.dataset.harga);
                const jumlah = Number(select.dataset.jumlah);
                if (hari > 0) dendaTerlambat += perHari * hari * jumlah;
                if (select.value === 'rusak') dendaRusak += Math.round(harga * persenRusak / 100) * jumlah;
                if (select.value === 'hilang') dendaHilang += harga * kelipatanHilang * jumlah;
            });
            const total = dendaTerlambat + dendaRusak + dendaHilang;
            const fmt = function(nilai) { return nilai.toLocaleString('id-ID'); };
            document.getElementById('total-denda').textContent = 'Rp' + fmt(total);
            document.querySelector('#total-denda').nextElementSibling.textContent =
                'Terlambat: Rp' + fmt(dendaTerlambat) + ' - Rusak: Rp' + fmt(dendaRusak) + ' - Hilang: Rp' + fmt(dendaHilang);
        }
        document.querySelector('input[name="tanggal_kembali"]').addEventListener('change', aktualisasiDenda);
        document.querySelectorAll('.kondisi').forEach(function(el) { el.addEventListener('change', aktualisasiDenda); });
        aktualisasiDenda();
    </script>
@endpush
