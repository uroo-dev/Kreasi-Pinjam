<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $alats = Alat::query()
            ->with('kategori')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('nama_alat', 'like', '%'.$request->cari.'%')
                        ->orWhere('kode_alat', 'like', '%'.$request->cari.'%');
                });
            })
            ->when($request->filled('kategori_id'), function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('Admin.alat', compact('alats', 'kategoris'));
    }

    public function store(Request $request)
    {
        $data = $this->validasiData($request);

        $data['stok_tersedia'] = $data['stok_total'];
        $data['status'] = 'tersedia';

        $alat = Alat::create($data);

        LogAktivitas::log(
            modul: 'alat',
            aksi: 'create',
            deskripsi: 'Menambah alat baru: '.$alat->nama_alat,
            userId: $request->user()->id,
            dataBaru: $alat->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->route('alat/get')->with('success', 'Alat berhasil ditambahkan');
    }

    public function show(Alat $alat)
    {
        //
    }

    public function update(Request $request, Alat $alat)
    {
        $data = $this->validasiData($request, $alat->id);

        $dataLama = $alat->getOriginal();

        $alat->update([
            'kategori_id' => $data['kategori_id'],
            'kode_alat' => $data['kode_alat'],
            'nama_alat' => $data['nama_alat'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'stok_total' => $data['stok_total'],
            'stok_tersedia' => $alat->stok_tersedia + ($data['stok_total'] - $alat->stok_total),
            'harga_sewa' => $data['harga_sewa'],
        ]);

        $alat->refresh()->updateStokTersedia();

        LogAktivitas::log(
            modul: 'alat',
            aksi: 'update',
            deskripsi: 'Memperbarui alat: '.$alat->nama_alat,
            userId: $request->user()->id,
            dataLama: $dataLama,
            dataBaru: $alat->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->route('alat/get')->with('success', 'Alat berhasil diupdate');
    }

    public function destroy(Request $request, Alat $alat)
    {
        $nama = $alat->nama_alat;

        $alat->delete();

        LogAktivitas::log(
            modul: 'alat',
            aksi: 'delete',
            deskripsi: 'Menghapus alat: '.$nama,
            userId: $request->user()->id,
            dataLama: $alat->getOriginal(),
            ipAddress: $request->ip()
        );

        return redirect()->route('alat/get')->with('success', 'Alat berhasil dihapus');
    }

    private function validasiData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'kode_alat' => ['required', 'string', 'max:30', Rule::unique('alat', 'kode_alat')->ignore($id)],
            'nama_alat' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:1000',
            'stok_total' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
        ]);
    }
}
