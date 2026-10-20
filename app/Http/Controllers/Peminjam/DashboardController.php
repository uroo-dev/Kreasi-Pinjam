<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Support\Tanggungan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'menunggu' => Peminjaman::where('user_id', $userId)->where('status', 'menunggu')->count(),
            'disetujui' => Peminjaman::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'dipinjam' => Peminjaman::where('user_id', $userId)->where('status', 'dipinjam')->count(),
            'selesai' => Peminjaman::where('user_id', $userId)->where('status', 'dikembalikan')->count(),
            'denda' => (float) Denda::where('user_id', $userId)
                ->where('status', 'belum_lunas')
                ->get()
                ->sum(fn ($d) => $d->nominal - $d->dibayar),
        ];

        $tanggungan = Tanggungan::cek(Auth::user());
        $riwayatTerbaru = Peminjaman::where('user_id', $userId)->with('details.alat')->latest()->limit(5)->get();

        return view('Peminjam.dashboard', compact('stats', 'tanggungan', 'riwayatTerbaru'));
    }
}
