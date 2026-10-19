<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
    <select id="{{ $prefix }}-kategori_id" name="kategori_id"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <option value="">Pilih kategori</option>
        @foreach ($kategoris as $kategori)
            <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Alat</label>
    <input type="text" id="{{ $prefix }}-kode_alat" name="kode_alat" placeholder="Contoh: PJK-001" maxlength="30"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500">
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Alat</label>
    <input type="text" id="{{ $prefix }}-nama_alat" name="nama_alat" placeholder="Contoh: Proyektor LCD" maxlength="150"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
    <textarea id="{{ $prefix }}-deskripsi" name="deskripsi" rows="3" placeholder="Spesifikasi singkat alat"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stok Total</label>
        <input type="number" id="{{ $prefix }}-stok_total" name="stok_total" min="0" placeholder="0"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Sewa (per hari)</label>
        <input type="number" id="{{ $prefix }}-harga_sewa" name="harga_sewa" min="0" step="any" placeholder="0"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
    </div>
</div>
<p class="text-xs text-slate-400">Stok tersedia akan dihitung otomatis oleh sistem saat alat dipinjam atau dikembalikan.</p>
