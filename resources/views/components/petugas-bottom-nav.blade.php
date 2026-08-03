<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-emerald-950 border-t border-emerald-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('petugas.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.dashboard') ? 'text-emerald-400 font-bold' : 'text-emerald-200' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span class="text-[10px]">Beranda</span>
    </a>
    <a href="{{ route('petugas.rute.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.rute.*') ? 'text-emerald-400 font-bold' : 'text-emerald-200' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
        <span class="text-[10px]">Rute</span>
    </a>
    <a href="{{ route('petugas.pengangkutan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.pengangkutan.*') ? 'text-emerald-400 font-bold' : 'text-emerald-200' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        <span class="text-[10px]">Input Sampah</span>
    </a>
    <a href="{{ route('petugas.pengaduan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.pengaduan.*') ? 'text-emerald-400 font-bold' : 'text-emerald-200' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        <span class="text-[10px]">Aduan</span>
    </a>
    <a href="{{ route('petugas.gaji.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.gaji.*') ? 'text-emerald-400 font-bold' : 'text-emerald-200' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        <span class="text-[10px]">Gaji</span>
    </a>
    <a href="{{ route('petugas.laporan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.laporan.*') ? 'text-emerald-400 font-bold' : 'text-emerald-300' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span class="text-[10px]">Kendala</span>
    </a>
</nav>