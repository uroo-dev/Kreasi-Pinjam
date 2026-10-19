<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required',
            'kode_alat' => 'required',
            'nama_alat' => 'required',
            'deskripsi' => 'nullable',
            'stok_total' => 'required',
            'stok_tersedia' => 'required',
            'harga_sewa' => 'required',
            'status' => 'nullable',
        ]);

        Alat::create($data);
        return redirect()->back()->with('success', 'Alat Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {

        $data = $request->validate([
            'kategori_id' => 'required',
            'kode_alat' => 'required',
            'nama_alat' => 'required',
            'deskripsi' => 'nullable',
            'stok_total' => 'required',
            'stok_tersedia' => 'required',
            'harga_sewa' => 'required',
            'status' => 'nullable',
        ]);

        $alat->update($data);
        return redirect()->back()->with('success', 'Alat  Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        $alat->delete();
        return redirect()->back()->with('success', 'Alat Berhaisl Dihapus');
    }
}
