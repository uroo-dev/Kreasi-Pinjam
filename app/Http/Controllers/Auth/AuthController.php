<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }

    public function register()
    {
        return view('Auth.register');
    }

    public function login_proses(Request $request)
    {
        $credential = $request->validate([
            'nama' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credential)) {
            $request->session()->regenerate();

            LogAktivitas::log(
                modul: 'auth',
                aksi: 'login',
                deskripsi: 'Pengguna berhasil masuk ke aplikasi',
                userId: $request->user()->id,
                ipAddress: $request->ip()
            );

            return redirect()->intended(route('dashboard'));
        }

        return redirect()->back()
            ->withInput($request->only('nama'))
            ->with('error', 'Periksa kembali Nama dan Password anda');
    }

    public function register_proses(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:users,nama',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', Password::min(8), 'confirmed'],
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        try {
            $user = DB::transaction(function () use ($request, $data) {
                $user = User::create([
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => 'peminjam',
                    'no_hp' => $data['no_hp'],
                ]);

                Peminjam::create([
                    'user_id' => $user->id,
                    'alamat' => $data['alamat'],
                ]);

                LogAktivitas::log(
                    modul: 'auth',
                    aksi: 'register',
                    deskripsi: 'Registrasi peminjam baru: '.$user->nama,
                    userId: $user->id,
                    dataBaru: $user->only('nama', 'email', 'role'),
                    ipAddress: $request->ip()
                );

                return $user;
            });

            return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan masuk');
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'Registrasi gagal, silakan coba lagi');
        }
    }

    public function logout(Request $request)
    {
        LogAktivitas::log(
            modul: 'auth',
            aksi: 'logout',
            deskripsi: 'Pengguna keluar dari aplikasi',
            userId: $request->user()->id,
            ipAddress: $request->ip()
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
