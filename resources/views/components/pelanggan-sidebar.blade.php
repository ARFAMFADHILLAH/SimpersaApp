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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <span class="block font-bold text-sm tracking-tight leading-none text-white">SIMPERSA</span>
                <span class="text-[10px] text-green-500 font-semibold uppercase tracking-widest mt-0.5 block">Warga / Pelanggan</span>
            </div>
        </div>

        <div class="flex-1 py-4 space-y-6 overflow-y-auto">
            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Layanan Warga</p>
                <div class="space-y-1">
                    <a href="{{ route('pelanggan.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pelanggan.dashboard') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Beranda
                    </a>

                    <a href="{{ route('pelanggan.profile') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pelanggan.profile*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Profil & Riwayat
                    </a>

                    <a href="{{ route('pelanggan.iuran.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pelanggan.iuran*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tagihan & Pembayaran
                    </a>

                    <a href="{{ route('pelanggan.pengaduan.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pelanggan.pengaduan*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Pengaduan
                    </a>

                    <a href="{{ route('pelanggan.notifikasi.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pelanggan.notifikasi*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        Notifikasi
                        @php $unreadNotif = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                        @if($unreadNotif > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $unreadNotif }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-zinc-800 text-center text-xs text-zinc-500 font-mono">
            SIMPERSA Warga v1.0
        </div>
    </aside>
</div>
