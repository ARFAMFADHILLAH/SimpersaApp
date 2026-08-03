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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
            </div>
            <div>
                <span class="block font-bold text-sm tracking-tight leading-none text-white">SIMPERSA</span>
                <span class="text-[10px] text-green-500 font-semibold uppercase tracking-widest mt-0.5 block">Manager</span>
            </div>
        </div>

        <div class="flex-1 py-4 space-y-6 overflow-y-auto">
            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Utama</p>
                <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.dashboard') ? 'bg-green-600 text-white font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                    Dashboard Manager
                </a>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Analisis & Keputusan</p>
                <div class="space-y-1">
                    <a href="{{ route('manager.dss.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.dss.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        DSS & Rekomendasi
                    </a>
                    <a href="{{ route('manager.laporan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.laporan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Pusat Laporan Executive
                    </a>
                </div>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Monitoring Data</p>
                <div class="space-y-1">
                    <a href="{{ route('manager.pelanggan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.pelanggan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Monitoring Pelanggan
                    </a>
                    <a href="{{ route('manager.iuran.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.iuran.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Iuran & Tagihan
                    </a>
                    <a href="{{ route('manager.keuangan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.keuangan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Arus Kas & Gaji
                    </a>
                    <a href="{{ route('manager.armada.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.armada.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        Kondisi Armada & Rute
                    </a>
                    <a href="{{ route('manager.rute.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.rute.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                        Rute & Peta Digital
                    </a>
                    <a href="{{ route('manager.pengaduan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manager.pengaduan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                        Log Pengaduan
                    </a>
                    <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('notifikasi.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        Pusat Notifikasi
                        @php $unreadNotif = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                        @if($unreadNotif > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $unreadNotif }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-zinc-800 text-center text-xs text-zinc-500 font-mono">
            SIMPERSA Manager v1.0
        </div>
    </aside>
</div>
