<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Sisi Kiri: Spacer/Penyelaras Sidebar (Opsional) atau Pencarian -->
            <div class="flex items-center">
                <!-- Jika ingin meletakkan logo kecil saat mobile di sini, silakan.
                     Untuk desktop, area ini sengaja dikosongkan agar konten Anda sejajar di sebelah kanan sidebar. -->
                <div class="sm:hidden shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-bold text-gray-800 tracking-tight text-sm flex items-center gap-2">
                        <span class="bg-white p-0.5 rounded-lg"><img src="/logo-kisuci.png" alt="SIMPERSA" class="h-6 w-6 object-cover rounded-md"></span>
                        SIMPERSA
                    </a>
                </div>
            </div>

            <!-- Sisi Kanan: Profile & Logout -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">

<!-- Badge Penanda Role User yang Sedang Login -->
@php
    // Ambil nama role dengan aman, baik formatnya Object Eloquent, Array, ataupun String
    $roleName = is_object(auth()->user()->role) 
        ? (auth()->user()->role->name ?? 'User')
        : (is_array(auth()->user()->role) 
            ? (auth()->user()->role['name'] ?? 'User') 
            : (auth()->user()->role ?? 'User'));
@endphp

<span class="px-2.5 py-1 text-[11px] font-semibold rounded-full uppercase tracking-wider
    @if(in_array(strtolower($roleName), ['admin', 'administrator'])) bg-red-50 text-red-700 border border-red-200
    @elseif(in_array(strtolower($roleName), ['owner', 'pimpinan'])) bg-indigo-50 text-indigo-700 border border-indigo-200
    @else bg-gray-50 text-gray-700 border border-gray-200 @endif">
    {{ $roleName }}
</span>

                <!-- Dropdown Menu Utama -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 gap-1 hover:bg-gray-50">
                            <!-- Avatar inisial nama -->
                            <div class="w-7 h-7 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-xs uppercase mr-1">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>

                            <div class="font-semibold text-gray-700">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profil -->
                        <div class="block px-4 py-2 text-xs text-gray-400 border-b border-gray-100">
                            Kelola Akun
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            {{ __('Profile Saya') }}
                        </x-dropdown-link>

                        <hr class="border-gray-100">

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    class="text-red-600 hover:text-red-700 hover:bg-red-50 flex items-center gap-2"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button (Mobile Only) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <!-- RUTE SIFAT MOBILE  -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('*.dashboard')">
                {{ __('Dashboard Utama') }}
            </x-responsive-nav-link>

            <!-- Navigasi Cepat Tambahan untuk Mobile -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('*.laporan.*')">
                {{ __('Buka Dashboard Saya') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold uppercase">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Saya') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="text-red-600"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
