@php
    $user = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Peminjaman Alat - @yield('title', 'Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('scripts_head')
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
</head>

<body class="font-['Plus_Jakarta_Sans'] bg-slate-100 text-slate-800 antialiased">

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-[90] flex flex-col gap-2 items-end"></div>

    <div id="admin-area" class="min-h-screen flex bg-slate-100">

        <div id="admin-overlay" class="hidden fixed inset-0 bg-slate-900/60 z-40 lg:hidden"
            onclick="toggleAdminDrawer()"></div>

        @include('Admin.partials.sidebar')

        <div class="flex-1 min-w-0 flex flex-col">
            @include('Admin.partials.topbar')

            <main class="flex-1 p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('template/assets/js/shared.js') }}"></script>
    <script src="{{ asset('template/assets/js/admin.js') }}"></script>
    @stack('scripts')
    @if (session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast({{ json_encode(session('success')) }}, 'success'));</script>
    @endif
    @if (session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => toast({{ json_encode(session('error')) }}, 'error'));</script>
    @endif
</body>

</html>
