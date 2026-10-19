<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::query()
            ->withCount('alat')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('nama_kategori', 'like', '%'.$request->cari.'%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Admin.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kategori = Kategori::create($data);

        LogAktivitas::log(
            modul: 'kategori',
            aksi: 'create',
            deskripsi: 'Menambah kategori baru: '.$kategori->nama_kategori,
            userId: $request->user()->id,
            dataBaru: $kategori->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->route('kategori/get')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show(Kategori $kategori)
    {
        //
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,'.$kategori->id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kategori->update($data);

        LogAktivitas::log(
            modul: 'kategori',
            aksi: 'update',
            deskripsi: 'Memperbarui kategori: '.$kategori->nama_kategori,
            userId: $request->user()->id,
            dataLama: $kategori->getOriginal(),
            dataBaru: $kategori->toArray(),
            ipAddress: $request->ip()
        );

        return redirect()->route('kategori/get')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Request $request, Kategori $kategori)
    {
        $nama = $kategori->nama_kategori;

        $kategori->delete();

        LogAktivitas::log(
            modul: 'kategori',
            aksi: 'delete',
            deskripsi: 'Menghapus kategori: '.$nama,
            userId: $request->user()->id,
            dataLama: $kategori->getOriginal(),
            ipAddress: $request->ip()
        );

        return redirect()->route('kategori/get')->with('success', 'Kategori berhasil dihapus');
    }
}
