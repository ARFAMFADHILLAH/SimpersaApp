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
                <span class="text-[10px] text-green-500 font-semibold uppercase tracking-widest mt-0.5 block">Bendahara</span>
            </div>
        </div>

        <div class="flex-1 py-4 space-y-6 overflow-y-auto">
            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Utama</p>
                <a href="{{ route('bendahara.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.dashboard') ? 'bg-green-600 text-white font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard Keuangan
                </a>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Transaksi</p>
                <div class="space-y-1">
                    <a href="{{ route('bendahara.penjualan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.penjualan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16m-11 4l5 4-5 4" /></svg>
                        Penjualan ke Pengepul
                    </a>
                    <a href="{{ route('bendahara.pembelian.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.pembelian.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                        Rincian Pembelian
                    </a>
                    <a href="{{ route('bendahara.absensi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.absensi.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Rekap Kehadiran
                    </a>
                    <a href="{{ route('bendahara.tabungan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.tabungan.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6zm4 2h10" /></svg>
                        Tabungan & Penarikan
                    </a>
                </div>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Penggajian</p>
                <div class="space-y-1">
                    <a href="{{ route('bendahara.penggajian.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.penggajian.*') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Proses Gaji Petugas
                    </a>
                </div>
            </div>

            <div class="px-4">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2 px-2">Laporan</p>
                <div class="space-y-1">
                    <a href="{{ route('bendahara.laporan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.laporan.index') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Rekapitulasi
                    </a>
                    <a href="{{ route('bendahara.laporan.neraca') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.laporan.neraca') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zm3-9h4m-4 4h4" /></svg>
                        Neraca Kas
                    </a>
                    <a href="{{ route('bendahara.laporan.arus-kas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('bendahara.laporan.arus-kas') ? 'bg-green-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} text-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2m6-2a8 8 0 11-16 0 8 8 0 0116 0z" /></svg>
                        Arus Kas
                    </a>
                </div>
            </div>
        </div>
    </aside>
</div>