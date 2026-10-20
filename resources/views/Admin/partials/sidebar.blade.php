<aside id="admin-sidebar"
    class="fixed lg:sticky top-0 left-0 h-screen z-50 lg:z-30 w-64 shrink-0 bg-emerald-950 text-white flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200">
    <div class="h-16 flex items-center justify-between px-4 border-b border-emerald-900 shrink-0">
        <span class="flex items-center gap-2 font-extrabold tracking-tight">
            <span class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0"><i
                    data-lucide="wrench" class="w-4 h-4 text-emerald-950"></i></span>
            <span class="side-label whitespace-nowrap leading-tight">Peminjaman Alat<span
                    class="text-emerald-400 text-[10px] font-bold ml-1 align-middle">{{ \App\Support\Menu::labelRole($user->role ?? null) }}</span></span>
        </span>
        <button onclick="toggleAdminDrawer()" class="lg:hidden p-1.5 rounded-lg hover:bg-emerald-900 transition"
            aria-label="Tutup sidebar"><i data-lucide="x" class="w-5 h-5"></i></button>
    </div>
    <nav class="flex-1 overflow-y-auto p-3 space-y-0.5 text-sm">
        @foreach (\App\Support\Menu::untuk($user->role ?? null) as $grup)
            <p class="side-label text-[10px] font-bold uppercase tracking-wider text-emerald-100/40 px-3 {{ $loop->first ? 'pt-2' : 'pt-4' }} pb-2">
                {{ $grup['grup'] }}</p>
            @foreach ($grup['items'] as $item)
                <button onclick="location.href='{{ route($item['route']) }}'"
                    class="anav w-full flex {{ request()->routeIs($item['aktif']) ? 'bg-emerald-500 text-emerald-950 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900 hover:text-white' }} items-center gap-3 px-3 py-2.5 rounded-xl transition">
                    <i data-lucide="{{ $item['ikon'] }}" class="w-4 h-4 shrink-0"></i>
                    <span class="side-label whitespace-nowrap">{{ $item['label'] }}</span>
                </button>
            @endforeach
        @endforeach
    </nav>
    <div class="p-3 border-t border-emerald-900 shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-emerald-100/80 hover:bg-emerald-900 hover:text-white transition text-sm">
                <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                <span class="side-label whitespace-nowrap">Keluar</span>
            </button>
        </form>
    </div>
</aside>
