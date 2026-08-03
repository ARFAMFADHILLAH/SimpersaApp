<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-zinc-900 border-t border-zinc-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('bendahara.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.dashboard') ? 'text-emerald-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        <span class="text-[10px]">Dashboard</span>
    </a>
    <a href="{{ route('bendahara.iuran.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.iuran*') ? 'text-emerald-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        <span class="text-[10px]">Iuran</span>
    </a>
    <a href="{{ route('bendahara.penggajian.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.penggajian*') ? 'text-emerald-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        <span class="text-[10px]">Gaji</span>
    </a>
    <a href="{{ route('bendahara.operasional.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.operasional*') ? 'text-emerald-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4z" /></svg>
        <span class="text-[10px]">Operasional</span>
    </a>
    <a href="{{ route('bendahara.laporan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.laporan*') ? 'text-emerald-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        <span class="text-[10px]">Laporan</span>
    </a>
</nav>