<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama lengkap</label>
    <input type="text" id="{{ $prefix }}-nama" name="nama" placeholder="Masukkan nama lengkap" maxlength="100"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
    <input type="email" id="{{ $prefix }}-email" name="email" placeholder="nama@email.com" maxlength="100"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. HP</label>
    <input type="text" id="{{ $prefix }}-no_hp" name="no_hp" placeholder="Contoh: 081234567890" maxlength="20"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Role</label>
    <select id="{{ $prefix }}-role" name="role"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <option value="">Pilih role</option>
        <option value="admin">Admin</option>
        <option value="petugas">Petugas</option>
        <option value="peminjam">Peminjam</option>
    </select>
</div>
<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kata sandi</label>
    <input type="password" id="{{ $prefix }}-password" name="password" placeholder="Minimal 8 karakter"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
</div>
