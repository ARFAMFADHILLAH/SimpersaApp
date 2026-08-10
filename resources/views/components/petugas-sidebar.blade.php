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
                  md:sticky md:top-0 md:h-screen md:translate-x-0"
           :class="{ 'translate-x-0': open }"
           @keydown.escape.window="open = false">
        <div class="px-6 py-5 border-b border-zinc-800 flex items-center gap-3">
            <img src="/logo-kisuci.png" alt="SIMPERSA" class="h-8 w-8 object-cover rounded-lg">
            <div>
                <span class="block font-bold text-sm tracking-tight leading-none text-white">SIMPERSA</span>
                <span class="text-[10px] text-green-500 font-semibold uppercase tracking-widest mt-0.5 block">Petugas</span>
            </div>
        </div>

        <div class="flex-1 py-4 space-y-6 overflow-y-auto">
            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Utama</p>
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('petugas.dashboard') ? 'bg-green-600 text-white font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard Konter
                </a>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Petugas</p>
                <div class="space-y-1">
                    <a href="{{ route('petugas.pembelian.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('petugas.pembelian.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h16M3 10h16M3 14h16M3 18h16" /></svg>
                        Pembelian Sampah
                    </a>
                    <a href="{{ route('petugas.penjualan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('petugas.penjualan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h12a1 1 0 011 1l-1 7H5l-1-7zM5 5l5 12H8m7-12l-5 12" /></svg>
                        Penjualan ke Pengepul
                    </a>
                    <a href="{{ route('petugas.gaji.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('petugas.gaji.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Slip Gaji Saya
                    </a>
                    <a href="{{ route('petugas.absensi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('petugas.absensi.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Absensi (Clock In/Out)
                    </a>
                </div>
            </div>
        </div>
    </aside>
</div>