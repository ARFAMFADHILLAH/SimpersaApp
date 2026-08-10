<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-zinc-900 border-t border-zinc-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('owner.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('owner.dashboard') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span class="text-[10px]">Ringkas</span>
    </a>
    <a href="{{ route('owner.laporan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('owner.laporan.index') || request()->routeIs('owner.laporan.kas') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        <span class="text-[10px]">Laporan</span>
    </a>
    <a href="{{ route('owner.keuangan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('owner.keuangan*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h13m-4 4h4m-6 4h6" /></svg>
        <span class="text-[10px]">Keuangan</span>
    </a>
    <a href="{{ route('owner.warga.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('owner.warga*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" /></svg>
        <span class="text-[10px]">Warga</span>
    </a>
    <a href="{{ route('owner.pengguna.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('owner.pengguna*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" /></svg>
        <span class="text-[10px]">Pengguna</span>
    </a>
</nav>