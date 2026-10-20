# KreasiPinjam — Aplikasi Peminjaman Alat

Aplikasi web peminjaman alat (gear / peralatan) berbasis Laravel 12 dengan tiga peran pengguna:
**admin**, **petugas**, dan **peminjam**. Pengguna bahasa Indonesia.

## Fitur Utama

| Modul | Deskripsi |
| --- | --- |
| Autentikasi | Login per-role, registrasi peminjam, middleware proteksi halaman |
| Dashboard | Ringkasan statistik per role (total unit, transaksi aktif, denda) |
| Kategori & Alat | CRUD kategori/alat oleh admin, stok total & stok tersedia otomatis |
| Pengelolaan Pengguna | CRUD pengguna (admin), profil peminjam (NIM/NIP, alamat, institusi) |
| Peminjaman | Pengajuan peminjam → verifikasi jaminan → persetujuan petugas → serahkan |
| Pengembalian | Proses kondisi unit (baik/rusak/hilang), penghitungan denda otomatis |
| Denda | Keterlambatan, kerusakan (% harga), kehilangan (kelipatan harga) dan pencatatan pembayaran |
| Tanggungan | Pemblokiran pengajuan baru jika denda belum lunas atau terlambat kembali |
| Laporan | Rekap peminjaman, pengembalian, dan denda per periode tanggal |
| Log Aktivitas | Jejak audit setiap aksi penting (login, CRUD, pengembalian, denda) |

## Alur Utama Peminjaman

1. **Peminjam** mengajukan peminjaman dari katalog (tanggal pinjam, jatuh tempo, jumlah unit, catatan).
2. **Petugas** mencatat jaminan / bukti pembayaran lalu menerima jaminan.
3. **Petugas** menyetujui pengajuan — stok unit otomatis berkurang.
4. **Petugas** menyerahkan alat (status menjadi *dipinjam*).
5. **Petugas** memproses pengembalian dengan kondisi tiap unit:
   - `baik` → stok kembali penuh
   - `rusak` → denda 50% harga sewa × jumlah unit, stok total berkurang
   - `hilang` → denda 10 × harga sewa × jumlah unit, stok total berkurang
   - terlambat → denda `Rp5.000/hari/unit` (dapat di-env, see `config/peminjaman.php`)
6. **Peminjam** melihat riwayat dan mencatat pembayaran denda via petugas.

## Instalasi

### Prasyarat

- PHP >= 8.2 dengan ekstensi sqlite, pdo-mysql, intl, mbstring, gd
- Composer 2
- MariaDB/MySQL 10.x
- Node.js (opsional, hanya untuk aset Tailwind via CDN — tidak perlu build)

### Langkah

```bash
# 1. Clone & masuk direktori
git clone <repo> KreasiPinjam && cd KreasiPinjam

# 2. Install dependency
composer install

# 3. Salin env dan ubah kredensial database
cp .env.example .env
php artisan key:generate

# 4. Siapkan database (nama bebas; default .env = kreasi_db)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS kreasi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'uroodev'@'localhost' IDENTIFIED BY 'uroodev';"
mysql -u root -e "GRANT ALL PRIVILEGES ON kreasi_db.* TO 'uroodev'@'localhost'; FLUSH PRIVILEGES;"

# 5. Migrasi + seeder (akun demo, kategori, alat contoh)
php artisan migrate:fresh --seed

# 6. Jalankan server
php artisan serve
```

### Akun Demo

| Nama | Email | Password | Role |
| --- | --- | --- | --- |
| uroo | uroo@gmail.com | password | admin |
| petugas | petugas@gmail.com | password | petugas |
| peminjam | peminjam@gmail.com | password | peminjam |

Login memakai kolom `nama` (bukan email): gunakan nama di atas (`uroo`, `petugas`, atau `peminjam`).

### Konfigurasi Denda & Durasi

Nilai bawaan di `config/peminjaman.php` (bisa dioverride via `.env`):

```
DENDA_KETERLAMBATAN_PER_HARI=5000
```

```php
durasi_minimal             // 1 hari
durasi_maksimal            // 14 hari
denda_keterlambatan_per_hari
denda_kerusakan_persen     // 50
denda_kehilangan_kelipatan // 10
```

## Menjalankan Test

```bash
php artisan test
```

19 kasus uji fitur mengcovering autentikasi, CRUD alat, pengajuan, persetujuan, pengembalian denda, dan laporan.

## Struktur Direktori

```
app/
├── Http/Controllers/{Auth,Admin,Petugas,Peminjam}
├── Models/          # Alat, Kategori, Peminjam, Peminjaman, DetailPeminjaman, ...
├── Services/        # PeminjamanService (logika bisnis transaksional)
└── Support/         # Notifikasi, Tanggungan, Menu, Status, LogAktivitas
database/
├── factories/       # UserFactory, AlatFactory, KategoriFactory
├── migrations/      # 10 tabel inti
└── seeders/         # DatabaseSeeder, KategoriAlatSeeder
resources/views/
├── Admin/           # dashboard, kategori, alat, user, laporan-*
├── Petugas/         # dashboard, persetujuan, pengembalian, denda
├── Peminjam/        # dashboard, katalog, riwayat, denda
└── Layout/, Auth/
```

## Import Database Siap Pakai

File `peminjaman_alat.sql` berisi struktur + data siap mutakhir. Eksekusi:

```bash
mysql -u uroodev -proodev kreasi_db < peminjaman_alat.sql
```

Dokumentasi lengkap tersedia di `DOCUMENTATION.md`.
