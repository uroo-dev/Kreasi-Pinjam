<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $list = Peminjaman::query()
            ->with('user', 'details.alat', 'pengembalian')
            ->where('status', 'dipinjam')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('kode_peminjaman', 'like', '%'.$request->cari.'%')
                        ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', '%'.$request->cari.'%'));
                });
            })
            ->orderBy('tanggal_jatuh_tempo')
            ->paginate(10)
            ->withQueryString();

        return view('Petugas.pengembalian', compact('list'));
    }

    public function create(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->isDipinjam(), 404, 'Peminjaman ini tidak sedang dipinjam');

        $peminjaman->load('user', 'details.alat');

        return view('Petugas.pengembalian-form', compact('peminjaman'));
    }

    public function store(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->isDipinjam(), 404, 'Peminjaman ini sudah diproses');

        $items = collect($request->input('items', []))->filter(function ($item) {
            return in_array($item['kondisi_alat'] ?? '', ['baik', 'rusak', 'hilang'], true);
        })->map(fn ($item) => [
            'alat_id' => (int) $item['alat_id'],
            'kondisi_alat' => $item['kondisi_alat'],
            'catatan' => $item['catatan'] ?? null,
        ])->values()->all();

        if (count($items) !== $peminjaman->details->count()) {
            return redirect()->back()->with('error', 'Kondisi setiap alat wajib dipilih');
        }

        $totalDenda = PeminjamanService::prosesPengembalian(
            $peminjaman,
            $request->user(),
            $items,
            $request->input('tanggal_kembali'),
            $request->input('catatan_umum'),
            $request->ip()
        );

        $pesan = 'Pengembalian '.$peminjaman->kode_peminjaman.' berhasil diproses';

        if ((float) $totalDenda > 0) {
            $pesan .= ', denda sebesar Rp'.number_format($totalDenda, 0, ',', '.').' dicatat pada denda';
        }

        return redirect()->route('petugas/pengembalian/get')->with('success', $pesan);
    }
}
