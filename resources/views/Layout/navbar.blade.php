    <!-- Promo Bar -->
    <div class="bg-orange-500 text-white text-xs sm:text-sm">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-center gap-2 text-center">
            <i data-lucide="truck" class="w-4 h-4 shrink-0"></i>
            <p class="font-medium">Gratis ongkir ke seluruh Indonesia untuk pembelian di atas Rp150.000</p>
        </div>
    </div>

    <!-- Public Navbar -->
    <header class="bg-emerald-900 text-white sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-3 h-16">
                <button onclick="toggleMobileMenu()"
                    class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-emerald-800 transition" aria-label="Buka menu">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <a href="#" onclick="go('home'); return false;" class="flex items-center gap-2 shrink-0">
                    <span class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center">
                        <i data-lucide="leaf" class="w-5 h-5 text-emerald-950"></i>
                    </span>
                    <span class="font-extrabold text-lg tracking-tight hidden sm:block">Nusantara<span
                            class="text-emerald-400">Mart</span></span>
                </a>

                <nav class="hidden lg:flex items-center gap-1 ml-4 text-sm font-medium">
                    <a href="#" onclick="go('home'); return false;"
                        class="px-3 py-2 rounded-xl hover:bg-emerald-800 transition">Beranda</a>
                    <a href="#" onclick="go('products'); return false;"
                        class="px-3 py-2 rounded-xl hover:bg-emerald-800 transition">Semua Produk</a>
                    <a href="#" onclick="go('category'); return false;"
                        class="px-3 py-2 rounded-xl hover:bg-emerald-800 transition">Kategori</a>
                    <a href="#" onclick="go('about'); return false;"
                        class="px-3 py-2 rounded-xl hover:bg-emerald-800 transition">Tentang</a>
                    <a href="#" onclick="go('contact'); return false;"
                        class="px-3 py-2 rounded-xl hover:bg-emerald-800 transition">Kontak</a>
                </nav>

                <div class="flex-1 relative hidden md:block max-w-md ml-auto lg:ml-6">
                    <i data-lucide="search"
                        class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" value="" placeholder="Cari kopi, keripik, batik..."
                        onfocus="showSearchDropdown(true)"
                        onblur="setTimeout(function(){showSearchDropdown(false)},200)"
                        class="w-full bg-white text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <div id="search-dropdown"
                        class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 text-slate-800">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2">Pencarian populer</p>
                        <button onclick="go('search')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-left transition">
                            <i data-lucide="coffee" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-sm font-medium">Kopi Gayo Arabika</span>
                            <span class="ml-auto text-xs text-slate-400">Produk</span>
                        </button>
                        <button onclick="go('search')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-left transition">
                            <i data-lucide="beef" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-sm font-medium">Keripik Singkong Balado</span>
                            <span class="ml-auto text-xs text-slate-400">Produk</span>
                        </button>
                        <button onclick="go('search')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-left transition">
                            <i data-lucide="shirt" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-sm font-medium">Batik Tulis Premium</span>
                            <span class="ml-auto text-xs text-slate-400">Produk</span>
                        </button>
                        <button onclick="go('search')"
                            class="w-full flex items-center justify-center gap-2 px-3 py-2.5 mt-1 rounded-xl bg-slate-50 text-emerald-700 text-sm font-semibold hover:bg-emerald-50 transition">
                            Lihat semua hasil <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-1 ml-auto md:ml-3">
                    <a href="#" onclick="go('wishlist'); return false;"
                        class="relative p-2.5 rounded-xl hover:bg-emerald-800 transition" aria-label="Wishlist">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-400 rounded-full"></span>
                    </a>
                    <a href="#" onclick="go('cart'); return false;"
                        class="relative p-2.5 rounded-xl hover:bg-emerald-800 transition" aria-label="Keranjang">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        <span id="cart-count"
                            class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-orange-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">2</span>
                    </a>
                    <a href="#" onclick="go('login'); return false;"
                        class="hidden sm:flex items-center gap-2 ml-2 pl-3 border-l border-emerald-700 text-sm font-medium hover:text-emerald-300 transition">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span class="hidden lg:block">Masuk</span>
                    </a>
                    <button onclick="openAdmin()"
                        class="hidden xl:flex items-center gap-2 ml-2 bg-emerald-500 hover:bg-emerald-400 text-emerald-950 text-sm font-bold px-4 py-2.5 rounded-xl transition">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Panel Admin
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Drawer -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-50 lg:hidden">
        <div class="absolute inset-0 bg-slate-900/60" onclick="toggleMobileMenu()"></div>
        <div class="absolute left-0 top-0 bottom-0 w-80 max-w-[85%] bg-white shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-4 h-16 bg-emerald-900 text-white">
                <span class="flex items-center gap-2 font-extrabold">
                    <span class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center"><i
                            data-lucide="leaf" class="w-4 h-4 text-emerald-950"></i></span>
                    NusantaraMart
                </span>
                <button onclick="toggleMobileMenu()" class="p-2 rounded-xl hover:bg-emerald-800 transition"
                    aria-label="Tutup menu"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-4 border-b border-slate-100">
                <div class="relative">
                    <i data-lucide="search"
                        class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari produk..." onfocus="toggleMobileMenu(); go('search')"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto p-3 space-y-1 text-sm font-medium text-slate-700">
                <button onclick="toggleMobileMenu(); go('home')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="home" class="w-4 h-4 text-slate-400"></i>Beranda</button>
                <button onclick="toggleMobileMenu(); go('products')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="package" class="w-4 h-4 text-slate-400"></i>Semua Produk</button>
                <button onclick="toggleMobileMenu(); go('category')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="layout-grid" class="w-4 h-4 text-slate-400"></i>Kategori</button>
                <button onclick="toggleMobileMenu(); go('cart')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="shopping-cart" class="w-4 h-4 text-slate-400"></i>Keranjang</button>
                <button onclick="toggleMobileMenu(); go('wishlist')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="heart" class="w-4 h-4 text-slate-400"></i>Wishlist</button>
                <button onclick="toggleMobileMenu(); go('orders')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="receipt" class="w-4 h-4 text-slate-400"></i>Pesanan Saya</button>
                <button onclick="toggleMobileMenu(); go('profile')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                        data-lucide="user" class="w-4 h-4 text-slate-400"></i>Profil Saya</button>
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <button onclick="toggleMobileMenu(); go('about')"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                            data-lucide="store" class="w-4 h-4 text-slate-400"></i>Tentang Toko</button>
                    <button onclick="toggleMobileMenu(); go('contact')"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                            data-lucide="mail" class="w-4 h-4 text-slate-400"></i>Kontak</button>
                    <button onclick="toggleMobileMenu(); go('faq')"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                            data-lucide="help-circle" class="w-4 h-4 text-slate-400"></i>FAQ</button>
                    <button onclick="toggleMobileMenu(); go('login')"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-800 transition"><i
                            data-lucide="log-in" class="w-4 h-4 text-slate-400"></i>Masuk / Daftar</button>
                    <button onclick="toggleMobileMenu(); openAdmin()"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-emerald-900 text-white hover:bg-emerald-800 transition mt-2"><i
                            data-lucide="layout-dashboard" class="w-4 h-4 text-emerald-400"></i>Panel Admin
                        (Demo)</button>
                </div>
            </nav>
        </div>
    </div>
