<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $kategoris = Kategori::latest()->paginate();
        // return redirect()->route('');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama',
            'deskripsi',
        ]);

        Kategori::create($data);

        return redirect()->back()->with('success', 'Kategori Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {

        $data = $request->validate([
            'nama',
            'deskripsi',
        ]);

        $kategori->update($data);

        return redirect()->back()->with('success', 'Kategori Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        Kategori::deleted($kategori);

        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus');
    }
}
