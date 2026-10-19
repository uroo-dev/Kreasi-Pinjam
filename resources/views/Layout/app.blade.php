<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NusantaraMart - Template E-Commerce UMKM Lokal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="font-['Plus_Jakarta_Sans'] bg-slate-50 text-slate-800 antialiased">

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-[90] flex flex-col gap-2 items-end"></div>

    <!-- ==================== PUBLIC AREA ==================== -->
    <div id="public-area">

        <!-- Promo bar + navbar + mobile menu -->
        <div id="navbar">
            @include('Layout.navbar')
        </div>

        <!-- ==================== Halaman toko (satu file per halaman di folder pages/) ==================== -->
        @yield('content')
        {{-- <div data-include="pages/home.html"></div>
        <div data-include="pages/products.html"></div>
        <div data-include="pages/product.html"></div>
        <div data-include="pages/category.html"></div>
        <div data-include="pages/search.html"></div>
        <div data-include="pages/cart.html"></div>
        <div data-include="pages/checkout.html"></div>
        <div data-include="pages/payment.html"></div>
        <div data-include="pages/success.html"></div>
        <div data-include="pages/orders.html"></div>
        <div data-include="pages/order-detail.html"></div>
        <div data-include="pages/wishlist.html"></div>
        <div data-include="pages/profile.html"></div>
        <div data-include="pages/login.html"></div>
        <div data-include="pages/register.html"></div>
        <div data-include="pages/forgot.html"></div>
        <div data-include="pages/about.html"></div>
        <div data-include="pages/contact.html"></div>
        <div data-include="pages/faq.html"></div>
        <div data-include="pages/404.html"></div> --}}

        <!-- Filter drawer (mobile) -->
        <div id="drawer">
            @include('Layout.drawer')
        </div>

        <!-- Footer -->
        <div id="footer">
            @include('Layout.footer')
        </div>
    </div>

    <script src="{{ asset('template/assets/js/shared.js') }}"></script>
    <script src="{{ asset('template/assets/js/app.js') }}"></script>
</body>

</html>
