<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('administrasi.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('administrasi.dashboard') ? 'text-cyan-400 font-bold' : 'text-slate-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        <span class="text-[10px]">Dashboard</span>
    </a>
    <a href="{{ route('administrasi.master.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('administrasi.master*') ? 'text-cyan-400 font-bold' : 'text-slate-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M10 12h4" /></svg>
        <span class="text-[10px]">Master</span>
    </a>
    <a href="{{ route('administrasi.pelanggan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('administrasi.pelanggan*') ? 'text-cyan-400 font-bold' : 'text-slate-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
        <span class="text-[10px]">Pelanggan</span>
    </a>
    <a href="{{ route('administrasi.operasional.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('administrasi.operasional*') ? 'text-cyan-400 font-bold' : 'text-slate-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
        <span class="text-[10px]">Operasional</span>
    </a>
    <a href="{{ route('administrasi.logistik.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('administrasi.logistik*') ? 'text-cyan-400 font-bold' : 'text-slate-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4z" /></svg>
        <span class="text-[10px]">Logistik</span>
    </a>
    <a href="{{ route('administrasi.pengaduan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('administrasi.pengaduan*') ? 'text-cyan-400 font-bold' : 'text-slate-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
        <span class="text-[10px]">Pengaduan</span>
    </a>
</nav>