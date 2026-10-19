    <!-- Public Footer -->
    <footer class="bg-emerald-950 text-emerald-100/80">
      <div class="max-w-7xl mx-auto px-4 py-12 lg:py-16">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="col-span-2 lg:col-span-1">
            <span class="flex items-center gap-2 text-white font-extrabold text-lg">
              <span class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center"><i data-lucide="leaf" class="w-5 h-5 text-emerald-950"></i></span>
              NusantaraMart
            </span>
            <p class="text-sm mt-4 leading-relaxed max-w-xs">Marketplace produk UMKM lokal Indonesia. Belanja berkualitas sambil mendukung pengrajin dan petani Nusantara.</p>
            <div class="flex gap-2 mt-5">
              <button onclick="toast('Membuka Instagram (demo)','success')" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-emerald-800 flex items-center justify-center transition" aria-label="Instagram"><i data-lucide="instagram" class="w-4 h-4"></i></button>
              <button onclick="toast('Membuka Facebook (demo)','success')" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-emerald-800 flex items-center justify-center transition" aria-label="Facebook"><i data-lucide="facebook" class="w-4 h-4"></i></button>
              <button onclick="toast('Membuka TikTok (demo)','success')" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-emerald-800 flex items-center justify-center transition" aria-label="TikTok"><i data-lucide="music-2" class="w-4 h-4"></i></button>
              <button onclick="toast('Membuka YouTube (demo)','success')" class="w-9 h-9 rounded-xl bg-emerald-900 hover:bg-emerald-800 flex items-center justify-center transition" aria-label="YouTube"><i data-lucide="youtube" class="w-4 h-4"></i></button>
            </div>
          </div>
          <div>
            <h3 class="text-white font-bold text-sm mb-4">Belanja</h3>
            <ul class="space-y-2.5 text-sm">
              <li><a href="#" onclick="go('products'); return false;" class="hover:text-white transition">Semua Produk</a></li>
              <li><a href="#" onclick="go('category'); return false;" class="hover:text-white transition">Kategori</a></li>
              <li><a href="#" onclick="go('products'); return false;" class="hover:text-white transition">Promo Minggu Ini</a></li>
              <li><a href="#" onclick="go('orders'); return false;" class="hover:text-white transition">Lacak Pesanan</a></li>
            </ul>
          </div>
          <div>
            <h3 class="text-white font-bold text-sm mb-4">Akun</h3>
            <ul class="space-y-2.5 text-sm">
              <li><a href="#" onclick="go('login'); return false;" class="hover:text-white transition">Masuk</a></li>
              <li><a href="#" onclick="go('register'); return false;" class="hover:text-white transition">Daftar</a></li>
              <li><a href="#" onclick="go('profile'); return false;" class="hover:text-white transition">Profil Saya</a></li>
              <li><a href="#" onclick="go('wishlist'); return false;" class="hover:text-white transition">Wishlist</a></li>
            </ul>
          </div>
          <div>
            <h3 class="text-white font-bold text-sm mb-4">Bantuan</h3>
            <ul class="space-y-2.5 text-sm">
              <li><a href="#" onclick="go('about'); return false;" class="hover:text-white transition">Tentang Kami</a></li>
              <li><a href="#" onclick="go('contact'); return false;" class="hover:text-white transition">Hubungi Kami</a></li>
              <li><a href="#" onclick="go('faq'); return false;" class="hover:text-white transition">FAQ</a></li>
              <li><a href="#" onclick="go('404'); return false;" class="hover:text-white transition">Contoh 404</a></li>
            </ul>
          </div>
        </div>
        <div class="border-t border-emerald-900 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p class="text-xs text-emerald-100/50">© 2026 NusantaraMart. Template demo untuk portofolio, bukan toko sungguhan.</p>
          <button onclick="openAdmin()" class="text-xs font-bold text-emerald-300 hover:text-white flex items-center gap-1.5 transition"><i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Buka Panel Admin (Demo)</button>
        </div>
      </div>
    </footer>
