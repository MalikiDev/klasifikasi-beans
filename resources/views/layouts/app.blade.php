<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('coffee.index') }}" class="text-xl font-bold text-gray-800">
                        Sistem Informasi Biji Kopi
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('coffee.index') }}" class="text-gray-700 hover:text-blue-500 {{ request()->routeIs('coffee.index') ? 'font-bold text-blue-500' : '' }}">Beranda</a>
                    <a href="{{ route('coffee.roasting-info') }}" class="text-gray-700 hover:text-blue-500 {{ request()->routeIs('coffee.roasting-info') ? 'font-bold text-blue-500' : '' }}">Info Roasting</a>
                    <a href="{{ route('coffee.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Tambah Data</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen">
        @yield('content')
    </div>

    <footer class="bg-white shadow-lg mt-12">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} Sistem Informasi Biji Kopi. Powered by Laravel & Flask AI.</p>
        </div>
    </footer>
</body>
</html>
