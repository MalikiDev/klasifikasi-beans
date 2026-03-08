<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BeanRoast ML') }} — Sistem Klasifikasi Biji Kopi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Infant:ital,wght@0,300..700;1,300..700&family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" style="font-family: 'Nunito', sans-serif;">

    <!-- ===================== NAVBAR ===================== -->
    <header class="w-full border-b border-gray-200 bg-white sticky top-0 z-50">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

            <!-- Left: Logo & Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded bg-gray-900 text-white flex-shrink-0">
                    {{-- Coffee bean icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="12" rx="9" ry="6" transform="rotate(-30 12 12)"/>
                        <path d="M12 6.5q1 2 0 5"/>
                    </svg>
                </div>
                <span class="uppercase text-base font-semibold text-gray-900 tracking-tight">
                    BeanRoast ML
                </span>
            </a>

            <!-- Center: Navigation Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}"
                   class="text-base font-medium transition-colors
                          {{ request()->routeIs('home') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }}">
                    Beranda
                </a>
                <a href="{{ route('coffee.create') }}"
                   class="text-base font-medium transition-colors
                          {{ request()->routeIs('coffee.create') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }}">
                    Klasifikasi
                </a>
                <a href="{{ route('coffee.roasting-info') }}"
                   class="text-base font-medium transition-colors
                          {{ request()->routeIs('coffee.roasting-info') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }}">
                    Info Roasting
                </a>
            </nav>

            <!-- Right: CTA Button (Desktop) -->
            <div class="hidden md:flex items-center">
                <a href="{{ route('coffee.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white text-base font-medium rounded-md px-4 py-2 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                    History Beans
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button type="button" id="mobileToggle"
                    class="flex items-center justify-center md:hidden text-gray-500 hover:text-gray-900 focus:outline-none"
                    aria-label="Toggle menu">
                {{-- Hamburger --}}
                <svg id="iconMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
                </svg>
                {{-- Close --}}
                <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 bg-white">
            <nav class="flex flex-col gap-1 px-4 pt-3 pb-2">
                <a href="{{ route('home') }}"
                   class="text-base font-medium px-3 py-2.5 rounded-md transition-colors
                          {{ request()->routeIs('home') ? 'text-gray-900 bg-gray-100 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Beranda
                </a>
                <a href="{{ route('coffee.create') }}"
                   class="text-base font-medium px-3 py-2.5 rounded-md transition-colors
                          {{ request()->routeIs('coffee.create') ? 'text-gray-900 bg-gray-100 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Klasifikasi
                </a>
                <a href="{{ route('coffee.roasting-info') }}"
                   class="text-base font-medium px-3 py-2.5 rounded-md transition-colors
                          {{ request()->routeIs('coffee.roasting-info') ? 'text-gray-900 bg-gray-100 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Info Roasting
                </a>
            </nav>
            <div class="px-4 pb-4">
                <a href="{{ route('coffee.create') }}"
                   class="flex items-center justify-center gap-2 w-full bg-gray-900 hover:bg-gray-800 text-white text-base font-medium rounded-md px-4 py-2.5 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Klasifikasi Baru
                </a>
            </div>
        </div>
    </header>
    <!-- ================= END NAVBAR ================= -->

    <!-- MAIN CONTENT -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="mt-auto bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                
                <!-- Brand Section -->
                <div class="flex flex-col space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded bg-white text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="12" rx="9" ry="6" transform="rotate(-30 12 12)"/>
                                <path d="M12 6.5q1 2 0 5"/>
                            </svg>
                        </div>
                        <span class="uppercase text-base font-semibold text-white tracking-tight">BeanRoast ML</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Sistem klasifikasi biji kopi berbasis AI untuk mengidentifikasi tingkat roasting dengan akurasi tinggi menggunakan teknologi deep learning.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-col space-y-3">
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-2">Navigasi</h3>
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Beranda</a>
                    <a href="{{ route('coffee.create') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Klasifikasi</a>
                    <a href="{{ route('coffee.index') }}" class="text-gray-400 hover:text-white text-sm transition-colors">History</a>
                    <a href="{{ route('coffee.roasting-info') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Info Roasting</a>
                </div>

                <!-- Technology Info -->
                <div class="flex flex-col space-y-3">
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-2">Teknologi</h3>
                    <div class="space-y-2 text-sm text-gray-400">
                        <p>Laravel Framework</p>
                        <p>Flask AI API</p>
                        <p>MobileNetV3 Models</p>
                        <p>TensorFlow & PyTorch</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} BeanRoast ML. All rights reserved.
                </p>
                <p class="text-gray-500 text-sm">
                    Powered by AI & Machine Learning
                </p>
            </div>
        </div>
    </footer>

    <script>
        const toggle    = document.getElementById('mobileToggle');
        const menu      = document.getElementById('mobileMenu');
        const iconMenu  = document.getElementById('iconMenu');
        const iconClose = document.getElementById('iconClose');

        toggle?.addEventListener('click', () => {
            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden', isOpen);
            iconMenu.classList.toggle('hidden', !isOpen);
            iconClose.classList.toggle('hidden', isOpen);
        });

        // Close menu when a link inside is clicked
        menu?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                iconMenu.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });
    </script>

{{-- Page-specific scripts --}}
    @stack('scripts')
    @livewireScripts
</body>
</html>