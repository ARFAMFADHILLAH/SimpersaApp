<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-zinc-900 border-t border-zinc-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('petugas.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.dashboard') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span class="text-[10px]">Beranda</span>
    </a>
    <a href="{{ route('petugas.pembelian.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.pembelian*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h16M3 11h16M3 15h16M3 19h16" /></svg>
        <span class="text-[10px]">Belanja</span>
    </a>
    <a href="{{ route('petugas.penjualan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.penjualan*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16m-8 4l-8 4m16 0l-8-4m8 4v4m0 0l-8 4v-4m8-4v4m0-8v-4m-8 12v-4m8 0v4" /></svg>
        <span class="text-[10px]">Jual</span>
    </a>
    <a href="{{ route('petugas.absensi.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.absensi*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="text-[10px]">Absen</span>
    </a>
    <a href="{{ route('petugas.gaji.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('petugas.gaji*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="text-[10px]">Gaji</span>
    </a>
</nav>