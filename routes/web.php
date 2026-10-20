<?php

use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Peminjam\DashboardController as PeminjamDashboardController;
use App\Http\Controllers\Peminjam\DendaController as PeminjamDendaController;
use App\Http\Controllers\Peminjam\KatalogController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\DendaController as PetugasDendaController;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\Petugas\PersetujuanController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login_proses');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_proses'])->name('register_proses');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori/get');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori/store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori/update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori/destroy');

    Route::get('/alat', [AlatController::class, 'index'])->name('alat/get');
    Route::post('/alat', [AlatController::class, 'store'])->name('alat/store');
    Route::put('/alat/{alat}', [AlatController::class, 'update'])->name('alat/update');
    Route::delete('/alat/{alat}', [AlatController::class, 'destroy'])->name('alat/destroy');

    Route::get('/user', [UserController::class, 'index'])->name('user/get');
    Route::post('/user', [UserController::class, 'store'])->name('user/store');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user/update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user/destroy');

    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan/peminjaman');
    Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan/pengembalian');
    Route::get('/laporan/denda', [LaporanController::class, 'denda'])->name('laporan/denda');
});

// Petugas: persetujuan, pengembalian, denda
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('petugas/dashboard');

    Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('petugas/persetujuan/get');
    Route::post('/persetujuan/{peminjaman}/jaminan', [PersetujuanController::class, 'verifikasiJaminan'])->name('petugas/jaminan/store');
    Route::post('/persetujuan/{peminjaman}/jaminan/terima', [PersetujuanController::class, 'terimaJaminan'])->name('petugas/jaminan/terima');
    Route::post('/persetujuan/{peminjaman}/jaminan/tolak', [PersetujuanController::class, 'tolakJaminan'])->name('petugas/jaminan/tolak');
    Route::post('/persetujuan/{peminjaman}/setujui', [PersetujuanController::class, 'setujui'])->name('petugas/peminjaman/setujui');
    Route::post('/persetujuan/{peminjaman}/tolak', [PersetujuanController::class, 'tolak'])->name('petugas/peminjaman/tolak');
    Route::post('/persetujuan/{peminjaman}/serahkan', [PersetujuanController::class, 'serahkan'])->name('petugas/peminjaman/serahkan');

    Route::get('/pengembalian', [PetugasPengembalianController::class, 'index'])->name('petugas/pengembalian/get');
    Route::get('/pengembalian/{peminjaman}/proses', [PetugasPengembalianController::class, 'create'])->name('petugas/pengembalian/buat');
    Route::post('/pengembalian/{peminjaman}', [PetugasPengembalianController::class, 'store'])->name('petugas/pengembalian/simpan');

    Route::get('/denda', [PetugasDendaController::class, 'index'])->name('petugas/denda/get');
    Route::post('/denda/{denda}/bayar', [PetugasDendaController::class, 'bayar'])->name('petugas/denda/bayar');
});

// Peminjam: katalog, pengajuan, riwayat, denda
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->group(function () {
    Route::get('/dashboard', [PeminjamDashboardController::class, 'index'])->name('peminjam/dashboard');

    Route::get('/katalog', [KatalogController::class, 'index'])->name('peminjam/katalog/get');
    Route::post('/katalog/{alat}/ajukan', [KatalogController::class, 'ajukan'])->name('peminjam/katalog/ajukan');

    Route::get('/riwayat', [KatalogController::class, 'riwayat'])->name('peminjam/riwayat/get');
    Route::post('/riwayat/{peminjaman}/batalkan', [KatalogController::class, 'batalkan'])->name('peminjam/riwayat/batalkan');

    Route::get('/denda', [PeminjamDendaController::class, 'index'])->name('peminjam/denda/get');
});

// Logout dipakai semua role
Route::middleware(['auth', 'role:admin,petugas,peminjam'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
