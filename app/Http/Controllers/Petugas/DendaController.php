<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $list = Denda::query()
            ->with('user', 'pengembalian.peminjaman')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->whereHas('user', fn ($u) => $u->where('nama', 'like', '%'.$request->cari.'%'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Petugas.denda', compact('list'));
    }

    public function bayar(Request $request, Denda $denda)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        $aksinya = (float) $request->jumlah;
        $sisa = (float) ($denda->nominal - $denda->dibayar);

        if ($aksinya > $sisa) {
            return redirect()->back()->with('error', 'Pembayaran melebihi sisa denda (Rp'.number_format($sisa, 0, ',', '.').')');
        }

        $denda->bayar($aksinya);

        LogAktivitas::log(
            modul: 'denda',
            aksi: 'bayar',
            deskripsi: 'Pembayaran denda Rp'.number_format($aksinya, 0, ',', '.').' ('.($denda->user->nama ?? '-').')',
            userId: $request->user()->id,
            dataBaru: $denda->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->back()->with('success', 'Pembayaran denda dicatat');
    }
}
