<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek - Aplikasi</title>
    <script src="../../assets/js/tailwind.js"></script>
    <script src="../../assets/js/lucide.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        glass: {
                            light: 'rgba(255,255,255,0.65)',
                            'light-border': 'rgba(255,255,255,0.8)',
                            dark: 'rgba(18,18,20,0.65)',
                            'dark-border': 'rgba(255,255,255,0.1)'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="../../assets/css/uroo.css">
    <style>
        .u-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            transition: opacity .35s ease
        }

        .u-bg-light {
            background: linear-gradient(135deg, #dfe3ea, #f4f4f5)
        }

        .u-bg-dark {
            background: linear-gradient(135deg, #0a0a0b, #1a1a1e);
            opacity: 0
        }

        body.dark .u-bg-light {
            opacity: 0
        }

        body.dark .u-bg-dark {
            opacity: 1
        }

        .u-glow {
            position: fixed;
            border-radius: 9999px;
            filter: blur(90px);
            z-index: -9;
            pointer-events: none;
            background: rgba(0, 0, 0, 0.06);
            transition: background-color .35s ease
        }

        body.dark .u-glow {
            background: rgba(255, 255, 255, 0.05)
        }

        .u-glow-1 {
            width: 420px;
            height: 420px;
            top: -140px;
            left: -140px
        }

        .u-glow-2 {
            width: 520px;
            height: 520px;
            bottom: -200px;
            right: -140px
        }

        .u-glow-3 {
            width: 300px;
            height: 300px;
            top: 42%;
            left: 58%
        }

        #sidebar {
            width: 16rem;
            transition: transform .28s cubic-bezier(.16, 1, .3, 1), width .28s cubic-bezier(.16, 1, .3, 1)
        }

        #sidebar.collapsed {
            width: 4.75rem
        }

        #sidebar .nav-label {
            transition: opacity .15s ease, width .15s ease
        }

        #sidebar.collapsed .nav-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap
        }

        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0
        }

        #sidebar.collapsed .group-title {
            justify-content: center;
            padding-left: 0;
            padding-right: 0
        }

        #sidebar.collapsed .nav-text {
            display: none
        }

        #sidebar.collapsed .logo-text {
            display: none
        }

        #sidebar .badge-dot {
            transition: opacity .15s
        }

        #sidebar.collapsed .badge-dot {
            opacity: 0
        }

        @media (max-width:767px) {
            #sidebar {
                position: fixed;
                top: 1rem;
                bottom: 1rem;
                left: 1rem;
                z-index: 50;
                transform: translateX(-130%);
            }

            #sidebar.open {
                transform: translateX(0);
            }
        }

        .avatar-ring {
            border: 2px solid var(--u-chart);
            border-radius: 9999px
        }

        :root {
            --u-chart: #18181b;
            --u-chart-mid: #71717a
        }

        .dark {
            --u-chart: #fafafa;
            --u-chart-mid: #a1a1aa
        }
    </style>
</head>

<body class="min-h-screen font-sans antialiased text-zinc-800 dark:text-zinc-100">
    <script>
        (function initTheme() {
            var saved = null;
            try {
                saved = localStorage.getItem('uroo-theme')
            } catch (e) {};
            var dark = (saved === 'dark') || (!saved && window.matchMedia && window.matchMedia(
                '(prefers-color-scheme: dark)').matches);
            var de = document.documentElement;
            if (dark) {
                de.classList.add('dark');
                de.classList.remove('light');
                if (document.body) {
                    document.body.classList.add('dark');
                    document.body.classList.remove('light');
                }
            } else {
                de.classList.remove('dark');
                de.classList.add('light');
                if (document.body) {
                    document.body.classList.remove('dark');
                    document.body.classList.add('light');
                }
            }
        })();
    </script>
    <div class="u-bg u-bg-light" aria-hidden="true"></div>
    <div class="u-bg u-bg-dark" aria-hidden="true"></div>
    <div class="u-glow u-glow-1" aria-hidden="true"></div>
    <div class="u-glow u-glow-2" aria-hidden="true"></div>
    <div class="u-glow u-glow-3" aria-hidden="true"></div>

    <div id="overlay"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"
        onclick="closeDrawer()"></div>

    <div class="md:flex md:gap-4 md:p-4 min-h-screen">
        @include('Layout.sidebar')

        @yield('content')
    </div>
    </div>

    <button onclick="toggleTheme()"
        class="fixed top-5 right-5 z-[80] p-2.5 rounded-full glass-panel shadow-2xl text-zinc-600 dark:text-zinc-300 md:top-[1.6rem] md:right-[1.6rem]">
        <i data-lucide="moon" class="w-4 h-4 hidden dark:block"></i>
        <i data-lucide="sun" class="w-4 h-4 block dark:hidden"></i>
    </button>

    <script>
        lucide.createIcons();

        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            document.body.classList.toggle('dark');
            try {
                localStorage.setItem('uroo-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')
            } catch (e) {};
            lucide.createIcons();
        }
        const sidebar = document.getElementById('sidebar');

        function toggleSidebar() {
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('collapsed');
            } else {
                sidebar.classList.toggle('open');
                document.getElementById('overlay').classList.toggle('opacity-0', !sidebar.classList.contains('open'));
                document.getElementById('overlay').classList.toggle('pointer-events-none', !sidebar.classList.contains(
                    'open'));
            }
        }

        function openDrawer() {
            sidebar.classList.add('open');
            document.getElementById('overlay').classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeDrawer() {
            sidebar.classList.remove('open');
            document.getElementById('overlay').classList.add('opacity-0', 'pointer-events-none');
        }

        function toast(msg, type) {
            const t = document.createElement('div');
            t.className =
                'fixed bottom-5 right-5 z-[85] glass-panel rounded-xl px-4 py-3 text-sm font-semibold shadow-2xl anim-toast-in flex items-center gap-2.5 pointer-events-none max-w-sm';
            t.innerHTML = '<i data-lucide="' + (type || 'check-circle') + '" class="w-4 h-4 shrink-0"></i><span>' + msg +
                '</span>';
            document.body.appendChild(t);
            lucide.createIcons();
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transition = 'opacity .3s';
                setTimeout(() => t.remove(), 320)
            }, 2600);
        }
    </script>
    <script src="../../assets/js/uroo-nav.js"></script>
</body>

</html>
