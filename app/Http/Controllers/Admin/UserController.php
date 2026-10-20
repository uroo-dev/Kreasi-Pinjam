<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('nama', 'like', '%'.$request->cari.'%')
                        ->orWhere('email', 'like', '%'.$request->cari.'%');
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:users,nama',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role' => ['required', Rule::in(['admin', 'petugas', 'peminjam'])],
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        LogAktivitas::log(
            modul: 'user',
            aksi: 'create',
            deskripsi: 'Menambah pengguna baru: '.$user->nama,
            userId: $request->user()->id,
            dataBaru: $user->only('nama', 'email', 'role', 'no_hp'),
            ipAddress: $request->ip()
        );

        return redirect()->route('user/get')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:users,nama,'.$user->id,
            'email' => 'required|email|max:100|unique:users,email,'.$user->id,
            'password' => ['nullable', Password::min(8)],
            'role' => ['required', Rule::in(['admin', 'petugas', 'peminjam'])],
            'no_hp' => 'nullable|string|max:20',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $dataLama = $user->getOriginal();

        $user->update($data);

        LogAktivitas::log(
            modul: 'user',
            aksi: 'update',
            deskripsi: 'Memperbarui pengguna: '.$user->nama,
            userId: $request->user()->id,
            dataLama: collect($dataLama)->only('nama', 'email', 'role', 'no_hp')->all(),
            dataBaru: $user->only('nama', 'email', 'role', 'no_hp'),
            ipAddress: $request->ip()
        );

        return redirect()->route('user/get')->with('success', 'Pengguna berhasil diupdate');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('user/get')->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('user/get')->with('error', 'Minimal harus ada satu admin aktif');
        }

        $nama = $user->nama;

        $user->delete();

        LogAktivitas::log(
            modul: 'user',
            aksi: 'delete',
            deskripsi: 'Menghapus pengguna: '.$nama,
            userId: $request->user()->id,
            dataLama: $user->only('nama', 'email', 'role', 'no_hp'),
            ipAddress: $request->ip()
        );

        return redirect()->route('user/get')->with('success', 'Pengguna berhasil dihapus');
    }
}
