<aside id="sidebar"
            class="glass-panel rounded-2xl flex flex-col overflow-hidden shrink-0 md:sticky md:top-4 md:h-[calc(100vh-2rem)]">
            <div class="flex items-center gap-3 px-5 h-16 border-b border-black/5 dark:border-white/10 shrink-0">
                <img src="../../assets/img/uroo_ui.png" alt="Uroo UI"
                    class="w-9 h-9 rounded-xl object-cover shadow-lg shrink-0">
                <div class="logo-text min-w-0">
                    <p class="font-extrabold text-[15px] leading-none tracking-tight">Uroo UI</p>
                    <p
                        class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                        Aplikasi</p>
                </div>
                <button onclick="closeDrawer()"
                    class="ml-auto md:hidden p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10"><i
                        data-lucide="x" class="w-4 h-4"></i></button>
                <button onclick="toggleSidebar()"
                    class="hidden md:block ml-auto p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400"><i
                        data-lucide="panel-left-close" class="w-4 h-4"></i></button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
                <div>
                    <p
                        class="group-title flex items-center nav-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                        Menu Utama</p>
                    <div class="space-y-1">
                        <a href="beranda.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Beranda</span></a>
                        <a href="app.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold bg-zinc-900 text-zinc-50 dark:bg-zinc-50 dark:text-zinc-900 shadow-lg"><i
                                data-lucide="folder" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Proyek</span></a>
                        <a href="inbox.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="inbox" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Inbox</span></a>
                        <a href="kalender.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="calendar" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Kalender</span></a>
                    </div>
                </div>
                <div>
                    <p
                        class="group-title flex items-center nav-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                        Ruang Kerja</p>
                    <div class="space-y-1">
                        <a href="tim.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="users" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Tim</span></a>
                        <a href="diskusi.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="message-square" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Diskusi</span></a>
                        <a href="analitik.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="bar-chart-3" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Analitik</span></a>
                    </div>
                </div>
                <div>
                    <p
                        class="group-title flex items-center nav-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                        Source</p>
                    <div class="space-y-1">
                        <a href="dokumen.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="file-text" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Dokumen</span></a>
                        <a href="aset-media.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="image" class="w-[18px] h-[18px] shrink-0"></i><span class="nav-label">Aset
                                Media</span></a>
                        <a href="pengaturan.html"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10"><i
                                data-lucide="settings" class="w-[18px] h-[18px] shrink-0"></i><span
                                class="nav-label">Pengaturan</span></a>
                    </div>
                </div>
            </nav>

            <div class="p-3 border-t border-black/5 dark:border-white/10 shrink-0">
                <button
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10">
                    <span
                        class="grid place-items-center w-8 h-8 rounded-full bg-gradient-to-br from-zinc-500 to-zinc-800 dark:from-zinc-300 dark:to-zinc-600 text-white dark:text-zinc-900 text-xs font-bold shrink-0">DB</span>
                    <span class="nav-label text-left min-w-0">
                        <span class="block font-bold text-[13px] leading-tight">Dita Baskoro</span>
                        <span class="block text-[11px] font-normal text-zinc-400 leading-tight">Administrator</span>
                    </span>
                </button>
            </div>
        </aside>
