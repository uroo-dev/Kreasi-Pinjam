<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\JaminanPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $list = Peminjaman::query()
            ->with('user', 'details.alat', 'jaminan')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status), fn ($query) => $query->where('status', 'menunggu'))
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('kode_peminjaman', 'like', '%'.$request->cari.'%')
                        ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', '%'.$request->cari.'%'));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Petugas.persetujuan', compact('list'));
    }

    public function verifikasiJaminan(Request $request, Peminjaman $peminjaman)
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:jaminan,pembayaran'],
            'keterangan' => 'required|string|max:255',
            'nominal' => 'nullable|numeric|min:0',
            'bukti' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ]);

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('jaminan', 'public');
        }

        $jaminan = JaminanPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'jenis' => $data['jenis'],
            'keterangan' => $data['keterangan'],
            'nominal' => $data['nominal'] ?? null,
            'bukti' => $data['bukti'] ?? null,
            'status' => 'menunggu',
        ]);

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'verifikasi_jaminan',
            deskripsi: 'Jaminan '.$peminjaman->kode_peminjaman.' dicatat oleh petugas',
            userId: $request->user()->id,
            dataBaru: $jaminan->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->back()->with('success', 'Jaminan berhasil dicatat');
    }

    public function terimaJaminan(Request $request, Peminjaman $peminjaman)
    {
        $jaminan = $peminjaman->jaminan;

        abort_unless($jaminan, 404, 'Jaminan belum tersedia');

        $jaminan->update(['status' => 'diterima']);

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'terima_jaminan',
            deskripsi: 'Jaminan '.$peminjaman->kode_peminjaman.' diterima petugas',
            userId: $request->user()->id,
            dataBaru: $jaminan->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->back()->with('success', 'Jaminan diterima, peminjaman siap disetujui');
    }

    public function tolakJaminan(Request $request, Peminjaman $peminjaman)
    {
        $jaminan = $peminjaman->jaminan;

        abort_unless($jaminan, 404, 'Jaminan belum tersedia');

        $jaminan->update(['status' => 'ditolak']);

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'tolak_jaminan',
            deskripsi: 'Jaminan '.$peminjaman->kode_peminjaman.' ditolak petugas',
            userId: $request->user()->id,
            dataBaru: $jaminan->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->back()->with('success', 'Jaminan ditolak');
    }

    public function setujui(Request $request, Peminjaman $peminjaman)
    {
        try {
            PeminjamanService::setujui($peminjaman, $request->user(), $request->ip());
        } catch (ValidationException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->back()->with('success', 'Peminjaman '.$peminjaman->kode_peminjaman.' disetujui, stok berkurang otomatis');
    }

    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan' => 'required|string|max:255']);

        PeminjamanService::tolak($peminjaman, $request->user(), $request->catatan, $request->ip());

        return redirect()->back()->with('success', 'Peminjaman '.$peminjaman->kode_peminjaman.' ditolak');
    }

    public function serahkan(Request $request, Peminjaman $peminjaman)
    {
        try {
            PeminjamanService::serahkan($peminjaman, $request->user(), $request->ip());
        } catch (ValidationException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->back()->with('success', 'Alat '.$peminjaman->kode_peminjaman.' ditandai sudah diambil');
    }

    public function hapusBukti(Request $request, Peminjaman $peminjaman)
    {
        $jaminan = $peminjaman->jaminan;

        abort_unless($jaminan && $jaminan->bukti, 404);

        Storage::disk('public')->delete($jaminan->bukti);

        $jaminan->update(['bukti' => null]);

        return redirect()->back()->with('success', 'Bukti jaminan dihapus');
    }
}
