<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-zinc-900 border-t border-zinc-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('bendahara.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.dashboard') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        <span class="text-[10px]">Kas</span>
    </a>
    <a href="{{ route('bendahara.penjualan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.penjualan*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16m-2 4l-8 4-4-2" /></svg>
        <span class="text-[10px]">Jual</span>
    </a>
    <a href="{{ route('bendahara.pembelian.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.pembelian*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h16M3 11h16M3 15h16M3 19h16" /></svg>
        <span class="text-[10px]">Beli</span>
    </a>
    <a href="{{ route('bendahara.tabungan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.tabungan*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6zm4 2h10" /></svg>
        <span class="text-[10px]">Tabungan</span>
    </a>
<a href="{{ route('bendahara.penggajian.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.penggajian*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    <span class="text-[10px]">Gaji</span>
</a>
<a href="{{ route('bendahara.absensi.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('bendahara.absensi*') ? 'text-green-400 font-bold' : 'text-zinc-400' }}">
    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    <span class="text-[10px]">Absensi</span>
</a>
</nav>