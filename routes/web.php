<?php

use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login_proses');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function(){
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori/get');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori/store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori/update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori/destroy');

});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function(){
    //
});

Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->group(function(){
    //
});

Route::middleware(['auth', 'role:admin, petgas, pelanggan'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
