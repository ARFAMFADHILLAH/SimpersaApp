<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIMPERSA - Sistem Informasi Manajemen Persampahan</title>

        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" href="/favicon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                body { font-family: 'Instrument Sans', sans-serif; }
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] antialiased text-[#1b1b18] min-h-screen flex flex-col justify-between">

        <!-- Navbar Top -->
        <header class="w-full max-w-6xl mx-auto px-6 py-4 flex items-center justify-between border-b border-[#e3e3e0]">
            <div class="flex items-center gap-2">
                <!-- Icon Logo Sampah -->
                <img src="/logo-kisuci.png" alt="SIMPERSA" class="h-8 w-8 object-cover rounded-lg">
                <span class="font-bold text-xl tracking-wider text-gray-900">SIMPERSA</span>
            </div>

            <nav class="flex gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                            Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-[#1b1b18] text-white text-sm font-medium rounded-md hover:bg-black transition">
                            Login
                        </a>
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Hero Section Main Content -->
        <main class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="max-w-xl text-center">
                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold uppercase rounded-full tracking-wider mb-4">
                    Sistem Informasi Manajemen Persampahan
                </span>

                <h1 class="text-4xl lg:text-5xl font-bold tracking-tight text-gray-950 leading-tight mb-4">
                    Mewujudkan Lingkungan Bersih & Terkelola Digital
                </h1>

                <p class="text-lg text-[#706f6c] leading-relaxed mb-8">
                    Platform terpadu pencatatan timbangan sampah harian, pengelolaan tabungan nasabah, penjualan ke pengepul, dan pemantauan stok &amp; keuangan bank sampah secara real-time.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 shadow transition duration-150">
                            Buka Layanan Anda
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 shadow transition duration-150">
                            Mulai Akses Akun
                        </a>
                    @endauth
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-[#e3e3e0] py-6 text-center text-sm text-[#706f6c]">
            <p>&copy; {{ date('Y') }} SIMPERSA. Hak Cipta Dilindungi.</p>
        </footer>

        <x-chatbot />

        @stack('scripts')
    </body>
</html>
