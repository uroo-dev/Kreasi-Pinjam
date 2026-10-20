<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Support\Tanggungan;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    public function index()
    {
        $dendas = Denda::query()
            ->where('user_id', Auth::id())
            ->with('pengembalian')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tanggungan = Tanggungan::cek(Auth::user());

        return view('Peminjam.denda', compact('dendas', 'tanggungan'));
    }
}
