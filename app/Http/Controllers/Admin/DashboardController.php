<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalPengguna' => User::count(),
            'totalAlat' => Alat::count(),
            'totalKategori' => Kategori::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'peminjamanMenunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'stokTotal' => (int) Alat::sum('stok_total'),
            'stokTersedia' => (int) Alat::sum('stok_tersedia'),
        ];

        $transaksiTerbaru = Peminjaman::query()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        $alatPerKategori = Kategori::query()
            ->withCount('alat')
            ->orderByDesc('alat_count')
            ->limit(4)
            ->get();

        return view('Admin.dashboard', compact('stats', 'transaksiTerbaru', 'alatPerKategori'));
    }
}
