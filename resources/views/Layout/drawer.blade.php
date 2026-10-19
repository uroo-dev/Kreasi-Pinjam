    <!-- Filter Drawer (mobile) -->
    <div id="filter-drawer" class="hidden fixed inset-0 z-50">
      <div class="absolute inset-0 bg-slate-900/60" onclick="closeFilterDrawer()"></div>
      <div class="absolute left-0 top-0 bottom-0 w-80 max-w-[85%] bg-white shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-4 h-14 border-b border-slate-100">
          <h3 class="font-bold text-slate-900 flex items-center gap-2"><i data-lucide="sliders-horizontal" class="w-4 h-4 text-emerald-700"></i> Filter Produk</h3>
          <button onclick="closeFilterDrawer()" class="p-2 rounded-xl hover:bg-slate-100 transition" aria-label="Tutup filter"><i data-lucide="x" class="w-5 h-5 text-slate-500"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-6">
          <div>
            <h3 class="font-bold text-sm text-slate-900 mb-3">Kategori</h3>
            <div class="space-y-2 text-sm text-slate-600">
              <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600"> Semua Produk</label>
              <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-600"> Kopi & Minuman</label>
              <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-600"> Camilan</label>
              <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-600"> Fashion</label>
            </div>
          </div>
          <div>
            <h3 class="font-bold text-sm text-slate-900 mb-3">Harga</h3>
            <div class="flex items-center gap-2">
              <input type="text" placeholder="Min" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
              <span class="text-slate-400">-</span>
              <input type="text" placeholder="Maks" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
          </div>
          <div>
            <h3 class="font-bold text-sm text-slate-900 mb-3">Rating</h3>
            <div class="flex gap-2">
              <button onclick="selectChip(this,'chip-rating')" class="chip-rating px-3 py-1.5 rounded-xl border border-emerald-700 bg-emerald-50 text-emerald-800 text-xs font-semibold">Semua</button>
              <button onclick="selectChip(this,'chip-rating')" class="chip-rating px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold">4,0+</button>
              <button onclick="selectChip(this,'chip-rating')" class="chip-rating px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold">4,5+</button>
            </div>
          </div>
          <div>
            <h3 class="font-bold text-sm text-slate-900 mb-3">Stok</h3>
            <label class="flex items-center gap-2.5 text-sm text-slate-600 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-600"> Hanya yang tersedia</label>
          </div>
        </div>
        <div class="p-4 border-t border-slate-100 flex gap-3">
          <button onclick="toast('Filter direset','success')" class="flex-1 border border-slate-200 rounded-xl py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Reset</button>
          <button onclick="closeFilterDrawer(); toast('Filter diterapkan','success')" class="flex-1 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl py-2.5 text-sm font-semibold transition">Terapkan</button>
        </div>
      </div>
    </div>
