<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-zinc-900 border-t border-zinc-800 text-white flex justify-around py-2.5 px-2 z-50 shadow-2xl">
    <a href="{{ route('warga.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('warga.dashboard') ? 'text-indigo-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span class="text-[10px]">Beranda</span>
    </a>
    <a href="{{ route('warga.profile') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('warga.profile*') ? 'text-indigo-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        <span class="text-[10px]">Profil</span>
    </a>
    <a href="{{ route('warga.iuran.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('warga.iuran*') ? 'text-indigo-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="text-[10px]">Tagihan</span>
    </a>
    <a href="{{ route('warga.pengaduan.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('warga.pengaduan*') ? 'text-indigo-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span class="text-[10px]">Aduan</span>
    </a>
    <a href="{{ route('warga.notifikasi.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('warga.notifikasi*') ? 'text-indigo-400 font-bold' : 'text-zinc-400' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        <span class="text-[10px]">Notif</span>
    </a>
</nav>