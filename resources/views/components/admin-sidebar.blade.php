<div x-data="{ open: false }">
    <button @click="open = !open" class="fixed top-4 left-4 z-50 md:hidden bg-[#1b1b18] text-white p-2.5 rounded-lg shadow-lg hover:bg-zinc-800 transition">
        <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div x-show="open" @click="open = false" class="fixed inset-0 z-30 bg-black/50 md:hidden" x-transition.opacity></div>

    <aside class="w-64 bg-[#1b1b18] text-white flex flex-col shrink-0 md:min-h-screen shadow-xl
                  fixed inset-y-0 left-0 z-40 -translate-x-full transition-transform duration-300 ease-in-out
                  md:relative md:translate-x-0"
           :class="{ 'translate-x-0': open }"
           @keydown.escape.window="open = false">
        <div class="px-6 py-5 border-b border-zinc-800 flex items-center gap-3">
            <div class="bg-green-600 p-2 rounded-lg">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <span class="block font-bold text-sm tracking-tight leading-none text-white">SIMPERSA</span>
                <span class="text-[10px] text-green-500 font-semibold uppercase tracking-widest mt-0.5 block">Administrator</span>
            </div>
        </div>

        <div class="flex-1 py-4 space-y-6 overflow-y-auto">
            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 text-white font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                    Dashboard Pusat
                </a>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Kependudukan & Master</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.wilayah.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.wilayah.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Wilayah Pelayanan
                    </a>
                    <a href="{{ route('admin.pelanggan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.pelanggan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Data Pelanggan
                    </a>
                    <a href="{{ route('admin.jenis-sampah.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.jenis-sampah.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        Jenis Sampah & Tarif
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        Pengguna & Staf
                    </a>
                    <a href="{{ route('admin.armada.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.armada.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100 4 2 2 0 000 4zm10 0a2 2 0 100 4 2 2 0 000 4zM4 17h1m11 0h1m2 0h1a2 2 0 002-2v-3a1 1 0 00-.293-.707l-3-3A1 1 0 0015.293 8H13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 h0" /></svg>
                        Armada & Kendaraan
                    </a>
                    <a href="{{ route('admin.tps.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.tps.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        TPS
                    </a>
                    <a href="{{ route('admin.notifikasi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.notifikasi.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        Notifikasi
                    </a>
                    <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('notifikasi.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Pusat Notifikasi
                        @php $unreadNotif = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                        @if($unreadNotif > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $unreadNotif }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.rute.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.rute.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                        Rute & Peta Digital
                    </a>
                </div>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Operasional & Kas</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.iuran.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.iuran.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Konfigurasi Iuran & Denda
                    </a>
                    <a href="{{ route('admin.pengangkutan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.pengangkutan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                        Monitoring Pengangkutan
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.laporan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Laporan Sistem
                    </a>
                    <a href="{{ route('admin.keputusan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.keputusan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Pengaturan DSS
                    </a>
                </div>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Core Sistem</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.sistem.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.sistem.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                        Utilitas & Backup
                    </a>
                </div>
            </div>
        </div>
    </aside>
</div>
