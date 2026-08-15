<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMPERSA') }} - Sistem Informasi Manajemen Persampahan</title>

        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" href="/favicon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>[x-cloak]{display:none !important;}</style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Left Brand Panel (Desktop) -->
            <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-600 items-center justify-center p-12 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="80" fill="white"/>
                        <circle cx="350" cy="100" r="100" fill="white"/>
                        <circle cx="100" cy="350" r="60" fill="white"/>
                        <circle cx="300" cy="300" r="70" fill="white"/>
                        <circle cx="200" cy="200" r="40" fill="white"/>
                    </svg>
                </div>
                <div class="relative z-10 text-center max-w-md">
                    <div class="bg-white/20 backdrop-blur-sm p-6 rounded-3xl inline-block mb-8 shadow-xl">
                        <img src="/logo-kisuci.png" alt="SIMPERSA" class="h-25 w-25 object-cover rounded-2xl shadow-lg">
                    </div>
                    <h1 class="text-4xl font-extrabold text-white mb-3 tracking-tight">SIMPERSA</h1>
                    <p class="text-emerald-100 text-lg font-medium">Sistem Informasi Manajemen<br>Persampahan Terintegrasi</p>
                    <div class="mt-8 border-t border-emerald-500/30 pt-6">
                        <p class="text-emerald-200 text-sm">Kelola bank sampah, pantau kegiatan, dan wujudkan<br>lingkungan bersih bersama SIMPERSA.</p>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="flex-1 flex items-center justify-center bg-gray-50 p-6">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="md:hidden text-center mb-8">
                        <div class="bg-white p-2 rounded-2xl inline-flex shadow-lg mb-3">
                            <img src="/logo-kisuci.png" alt="SIMPERSA" class="h-10 w-10 object-cover rounded-xl">
                        </div>
                        <h2 class="text-2xl font-extrabold text-gray-800">SIMPERSA</h2>
                        <p class="text-sm text-gray-500 mt-1">Sistem Informasi Manajemen Persampahan</p>
                    </div>

                    <!-- Form Card -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-8">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} SIMPERSA. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>