<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login_proses(Request $request)
    {
        $credential = $request->validate([
            'nama' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credential)) {
            $request->session()->regenerate();
            $request->user()->foreceFill(['last_login' => now()])->save();
            return redirect()->intended();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register_proses(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'no_hp' => 'required',
        ]);

        try {
            User::create($data);
            return redirect()->back()->with('success', 'Registrasi Berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('');
    }
}
