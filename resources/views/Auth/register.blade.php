@extends('Layout.app')
@section('title', 'Daftar Akun Peminjam')
@section('content')
    <section data-page="register" class="page">
        <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <span class="w-12 h-12 mx-auto rounded-2xl bg-emerald-700 flex items-center justify-center"><i
                            data-lucide="leaf" class="w-6 h-6 text-white"></i></span>
                    <h1 class="text-2xl font-extrabold text-slate-900 mt-4 tracking-tight">Daftar akun peminjam</h1>
                    <p class="text-sm text-slate-500 mt-1.5">Lengkapi data berikut untuk mulai meminjam alat</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    @if (session('error'))
                        <div class="mb-4 flex items-start gap-2 bg-red-50 border border-red-200 rounded-xl p-3 text-xs font-semibold text-red-600">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>{{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('register_proses') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama lengkap</label>
                                <input type="text" name="nama" placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('nama')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                                <input type="email" name="email" placeholder="nama@email.com"
                                    value="{{ old('email') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('email')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. HP</label>
                                <input type="text" name="no_hp" placeholder="Contoh: 081234567890"
                                    value="{{ old('no_hp') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('no_hp')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat</label>
                                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kata sandi</label>
                                <div class="relative">
                                    <input id="pw-register" type="password" name="password" placeholder="Minimal 8 karakter"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 pr-11 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <button type="button" onclick="togglePassword('pw-register',this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                        aria-label="Tampilkan kata sandi"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                </div>
                                @error('password')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi kata sandi</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <button type="submit"
                                class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-md shadow-emerald-700/20">Daftar</button>
                        </div>
                    </form>
                </div>
                <p class="text-center text-sm text-slate-500 mt-6">Sudah punya akun? <a href="{{ route('login') }}"
                        class="font-bold text-emerald-700 hover:text-emerald-800">Masuk sekarang</a></p>
            </div>
        </div>
    </section>
@endsection
