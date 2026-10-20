<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    /**
     * Ambil rentang tanggal dari request (default: bulan ini).
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function rentangTanggal(Request $request): array
    {
        $awal = $request->filled('awal')
            ? now()->parse($request->awal)->startOfDay()
            : now()->startOfMonth();

        $akhir = $request->filled('akhir')
            ? now()->parse($request->akhir)->endOfDay()
            : now()->endOfDay();

        return [$awal, $akhir];
    }

    public function peminjaman(Request $request)
    {
        [$awal, $akhir] = $this->rentangTanggal($request);

        $basis = Peminjaman::query()
            ->whereBetween('tanggal_pinjam', [$awal, $akhir])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status));

        $list = (clone $basis)
            ->with('user', 'details.alat')
            ->latest('tanggal_pinjam')
            ->paginate(15)
            ->withQueryString();

        $ringkasan = collect([
            'total' => (clone $basis)->count(),
            'diproses' => (clone $basis)->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])->count(),
            'selesai' => (clone $basis)->where('status', 'dikembalikan')->count(),
            'ditolak' => (clone $basis)->whereIn('status', ['ditolak', 'dibatalkan'])->count(),
            'nilai_total' => (clone $basis)->with('details')->get()->flatMap->details->sum('subtotal'),
        ]);

        return view('Admin.laporan-peminjaman', compact('list', 'awal', 'akhir', 'ringkasan'));
    }

    public function pengembalian(Request $request)
    {
        [$awal, $akhir] = $this->rentangTanggal($request);

        $basis = Pengembalian::query()
            ->whereBetween('tanggal_kembali', [$awal, $akhir])
            ->when($request->filled('kondisi'), fn ($query) => $query->where('kondisi_alat', $request->kondisi));

        $list = (clone $basis)
            ->with('peminjaman.user', 'alat')
            ->latest('tanggal_kembali')
            ->paginate(15)
            ->withQueryString();

        $ringkasan = collect([
            'total' => (clone $basis)->count(),
            'tepat_waktu' => (clone $basis)->where('hari_terlambat', 0)->where('kondisi_alat', 'baik')->count(),
            'terlambat' => (clone $basis)->where('hari_terlambat', '>', 0)->count(),
            'rusak' => (clone $basis)->where('kondisi_alat', 'rusak')->count(),
            'hilang' => (clone $basis)->where('kondisi_alat', 'hilang')->count(),
            'total_denda' => (float) (clone $basis)->sum('total_denda'),
        ]);

        return view('Admin.laporan-pengembalian', compact('list', 'awal', 'akhir', 'ringkasan'));
    }

    public function denda(Request $request)
    {
        [$awal, $akhir] = $this->rentangTanggal($request);

        $basis = Denda::query()
            ->whereBetween('created_at', [$awal, $akhir])
            ->when($request->filled('jenis'), fn ($query) => $query->where('jenis_denda', $request->jenis))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status));

        $list = (clone $basis)
            ->with('user', 'pengembalian.peminjaman')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $ringkasan = collect([
            'total_denda' => (float) (clone $basis)->sum('nominal'),
            'terbayar' => (float) (clone $basis)->sum('dibayar'),
            'belum_lunas' => (float) (clone $basis)->where('status', 'belum_lunas')->get()->sum(fn ($d) => $d->nominal - $d->dibayar),
            'jumlah_denda' => (clone $basis)->count(),
        ]);

        $perJenis = collect([
            'terlambat' => (float) (clone $basis)->where('jenis_denda', 'terlambat')->sum('nominal'),
            'rusak' => (float) (clone $basis)->where('jenis_denda', 'rusak')->sum('nominal'),
            'hilang' => (float) (clone $basis)->where('jenis_denda', 'hilang')->sum('nominal'),
        ]);

        return view('Admin.laporan-denda', compact('list', 'awal', 'akhir', 'ringkasan', 'perJenis'));
    }
}
