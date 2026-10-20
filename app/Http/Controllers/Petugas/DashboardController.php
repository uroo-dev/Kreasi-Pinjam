<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'menungguJaminan' => Peminjaman::where('status', 'menunggu')->count(),
            'siapDiserahkan' => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'jatuhTempoHariIni' => Peminjaman::where('status', 'dipinjam')
                ->whereDate('tanggal_jatuh_tempo', '<=', now())
                ->count(),
            'dendaBelumLunas' => (float) Denda::where('status', 'belum_lunas')
                ->get()
                ->sum(fn ($d) => $d->nominal - $d->dibayar),
            'dendaLunasHariIni' => (float) Denda::where('status', 'lunas')
                ->whereDate('updated_at', now())
                ->get()
                ->sum('dibayar'),
        ];

        $pengajuanTerbaru = Peminjaman::where('status', 'menunggu')->with('user', 'details.alat')->latest()->limit(5)->get();

        $alatSeringDipinjam = DetailPeminjaman::query()
            ->join('alat', 'detail_peminjaman.alat_id', '=', 'alat.id')
            ->join('peminjaman', 'detail_peminjaman.peminjaman_id', '=', 'peminjaman.id')
            ->whereIn('peminjaman.status', ['disetujui', 'dipinjam', 'dikembalikan'])
            ->groupBy('alat.id', 'alat.nama_alat')
            ->selectRaw('alat.nama_alat, SUM(detail_peminjaman.jumlah) as total_pinjam')
            ->orderByDesc('total_pinjam')
            ->limit(5)
            ->get();

        $peminjamAktif = Peminjaman::query()
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.nama')
            ->selectRaw('users.nama, COUNT(*) as jumlah_pinjaman')
            ->orderByDesc('jumlah_pinjaman')
            ->limit(5)
            ->get();

        return view('Petugas.dashboard', compact('stats', 'pengajuanTerbaru', 'alatSeringDipinjam', 'peminjamAktif'));
    }
}
