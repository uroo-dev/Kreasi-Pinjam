<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use App\Support\Tanggungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $alats = Alat::query()
            ->with('kategori')
            ->where('status', 'tersedia')
            ->where('stok_tersedia', '>', 0)
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('nama_alat', 'like', '%'.$request->cari.'%')
                        ->orWhere('kode_alat', 'like', '%'.$request->cari.'%');
                });
            })
            ->when($request->filled('kategori_id'), fn ($query) => $query->where('kategori_id', $request->kategori_id))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $tanggungan = Tanggungan::cek(Auth::user());

        return view('Peminjam.katalog', compact('alats', 'kategoris', 'tanggungan'));
    }

    public function ajukan(Request $request, Alat $alat)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string|max:255',
        ]);

        $peminjam = Auth::user();

        $peminjaman = PeminjamanService::buatPengajuan(
            $peminjam,
            $request->only('tanggal_pinjam', 'tanggal_jatuh_tempo', 'catatan'),
            [
                [
                    'alat_id' => $alat->id,
                    'jumlah' => (int) $request->jumlah,
                ],
            ],
            $request->ip()
        );

        return redirect()->route('peminjam/riwayat/get')
            ->with('success', 'Pengajuan '.$peminjaman->kode_peminjaman.' berhasil dibuat, menunggu verifikasi petugas');
    }

    public function riwayat(Request $request)
    {
        $list = Peminjaman::query()
            ->where('user_id', Auth::id())
            ->with('details.alat.kategori', 'jaminan')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tanggungan = Tanggungan::cek(Auth::user());

        return view('Peminjam.riwayat', compact('list', 'tanggungan'));
    }

    public function batalkan(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === Auth::id(), 403);

        if (! $peminjaman->isMenunggu()) {
            return redirect()->route('peminjam/riwayat/get')
                ->with('error', 'Pengajuan sudah diproses dan tidak dapat dibatalkan');
        }

        $peminjaman->update(['status' => 'dibatalkan']);

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'batalkan',
            deskripsi: 'Peminjaman '.$peminjaman->kode_peminjaman.' dibatalkan peminjam',
            userId: Auth::id(),
            dataBaru: $peminjaman->toArray(),
            ipAddress: request()->ip()
        );

        return redirect()->route('peminjam/riwayat/get')->with('success', 'Pengajuan berhasil dibatalkan');
    }
}
