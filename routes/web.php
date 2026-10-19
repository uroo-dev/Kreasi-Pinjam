<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login_proses');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_proses'])->name('register_proses');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori/get');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori/store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori/update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori/destroy');
});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    //
});

Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->group(function () {
    //
});

Route::middleware(['auth', 'role:admin,petugas,peminjam'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
