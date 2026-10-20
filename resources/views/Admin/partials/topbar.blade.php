<header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-20 flex items-center gap-3 px-4 lg:px-6">
    <button onclick="toggleAdminDrawer()" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition"
        aria-label="Buka sidebar"><i data-lucide="menu" class="w-5 h-5"></i></button>
    <button onclick="toggleAdminCollapse()"
        class="hidden lg:block p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition"
        aria-label="Ciutkan sidebar"><i data-lucide="panel-left-close" class="w-5 h-5"></i></button>
    <div class="relative flex-1 max-w-sm">
        <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" placeholder="Cari menu..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
    </div>
    <div class="ml-auto flex items-center gap-2">
        <div class="relative">
            <button onclick="toggleNotifDropdown()" class="relative p-2.5 rounded-xl hover:bg-slate-100 text-slate-600 transition" aria-label="Notifikasi">
                <i data-lucide="bell" class="w-5 h-5"></i>
                @if ($notifikasi['jumlah'] > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-orange-500 rounded-full border-2 border-white"></span>
                @endif
            </button>
            <div id="notif-dropdown" class="hidden absolute right-0 top-full mt-2 w-96 bg-white rounded-2xl shadow-xl border border-slate-100 p-2">
                <p class="text-xs font-bold text-slate-400 px-3 py-2">Notifikasi</p>
                @forelse ($notifikasi['items'] as $item)
                    <button type="button"
                        onclick="location.href='{{ $item['route'] }}'; toggleNotifDropdown()"
                        class="w-full flex items-start gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-left transition">
                        <span class="w-8 h-8 rounded-xl {{ $item['warna'] }} flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $item['ikon'] }}" class="w-4 h-4 {{ $item['warna_ikon'] }}"></i>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-slate-800">{{ $item['judul'] }}</span>
                            <span class="block text-xs text-slate-400 mt-0.5">{{ $item['ket'] }}</span>
                        </span>
                    </button>
                @empty
                    <p class="px-3 py-2.5 text-sm text-slate-500">Belum ada notifikasi.</p>
                @endforelse
            </div>
        </div>
        <button class="flex items-center gap-2.5 pl-2 border-l border-slate-200 hover:opacity-80 transition" tabindex="-1">
            <img src="https://picsum.photos/seed/{{ $user->id }}-{{ $user->nama }}/80/80" alt="Foto profil {{ $user->nama }}" class="w-9 h-9 rounded-xl object-cover">
            <span class="hidden md:block text-left">
                <span class="block text-sm font-bold text-slate-800 leading-tight">{{ $user->nama }}</span>
                <span class="block text-xs text-slate-400 capitalize">{{ $user->role }}</span>
            </span>
        </button>
    </div>
</header>
