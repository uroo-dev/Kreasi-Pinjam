@extends('Layout.app')
@section('title', 'Login')
@section('content')
    <!-- ==================== Public Login Page ==================== -->
    <section data-page="login" class="page">
        <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <span class="w-12 h-12 mx-auto rounded-2xl bg-emerald-700 flex items-center justify-center"><i
                            data-lucide="leaf" class="w-6 h-6 text-white"></i></span>
                    <h1 class="text-2xl font-extrabold text-slate-900 mt-4 tracking-tight">Masuk ke akun Anda</h1>
                    <p class="text-sm text-slate-500 mt-1.5">Selamat datang kembali di NusantaraMart</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    @if (session('error'))
                        <div class="mb-4 flex items-start gap-2 bg-red-50 border border-red-200 rounded-xl p-3 text-xs font-semibold text-red-600">
                            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>{{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('login_proses') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama</label>
                                <input type="text" name="nama" placeholder="Masukkan Nama" value="{{ old('nama') }}"
                                    class="w-full bg-slade-50/50 border border-slate300 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('nama')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kata sandi</label>
                                <div class="relative">
                                    <input id="pw-login" type="password" name="password" placeholder="Masukkan kata sandi"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 pr-11 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <button type="button" onclick="togglePassword('pw-login',this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                        aria-label="Tampilkan kata sandi"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                </div>
                                @error('password')
                                    <p class="text-xs text-red-600 font-medium mt-1.5 flex items-center gap-1"><i
                                            data-lucide="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-md shadow-emerald-700/20">Masuk</button>
                        </div>
                    </form>
                    {{-- <div class="flex items-center gap-3 my-5"><div class="flex-1 h-px bg-slate-200"></div><span class="text-xs text-slate-400 font-medium">atau</span><div class="flex-1 h-px bg-slate-200"></div></div>
            <button onclick="toast('Masuk dengan Google (demo UI)','success')" class="w-full border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm py-3 rounded-xl transition flex items-center justify-center gap-2"><i data-lucide="chrome" class="w-4 h-4"></i> Lanjutkan dengan Google</button> --}}
                </div>
                <p class="text-center text-sm text-slate-500 mt-6">Belum punya akun? <a href="{{ route('register') }}"
                        class="font-bold text-emerald-700 hover:text-emerald-800">Daftar sekarang</a></p>
            </div>
        </div>
    </section>

@endsection
