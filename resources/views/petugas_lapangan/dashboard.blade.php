<x-app-layout>
    <!-- Container Utama (Mobile First & Modern) -->
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100">

        <x-petugas-sidebar />

        <!-- MAIN CONTENT AREA -->

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 pb-28 md:pb-8">

            <!-- WELCOME & USER CARD -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-800 font-black text-lg">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 2)) }}
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-400 block">Selamat Bertugas,</span>
                        <h3 class="text-base font-bold text-gray-800 leading-tight">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</h3>
                        <span class="text-xs text-emerald-600 font-medium">{{ $armadaSaya?->nama_kendaraan ?? 'Belum ada armada ditugaskan' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 animate-pulse">
                        • Aktif Bertugas
                    </span>
                </div>
            </div>

            <!-- MODUL 6: WIDGET PRESENSI HARIAN (CLOCK IN / CLOCK OUT) -->
            <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 p-5 rounded-2xl text-white shadow-md">
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <span class="text-xs text-emerald-300 font-medium uppercase tracking-wider block">Absensi Hari Ini</span>
                        <p class="text-sm font-bold">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                    </div>
                    <span class="text-xs bg-emerald-700/60 px-3 py-1 rounded-lg border border-emerald-600 font-mono">07:45 WIB</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                    <!-- Tombol Clock In -->
                    <form action="{{ route('petugas.absensi.clockin') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <x-camera-capture name="foto_masuk" label="Foto Clock-In (selfie)" facing="user" hint="Wajib diisi untuk validasi kehadiran." required dark />
                        <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            Clock In (Masuk)
                        </button>
                    </form>
                    <!-- Tombol Clock Out -->
                    <form action="{{ route('petugas.absensi.clockout') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <x-camera-capture name="foto_pulang" label="Foto Clock-Out (selfie)" facing="user" hint="Wajib diisi untuk validasi kehadiran." required dark />
                        <button type="submit" class="w-full py-3 bg-red-600/80 hover:bg-red-600 text-white font-bold rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Clock Out (Pulang)
                        </button>
                    </form>
                </div>
            </div>

            <!-- GRID AKSI UTAMA (QUICK ACTION MODUL 3, 4, 6, 11) -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Modul 3: Tugas Harian & Navigasi Lapangan -->
                <a href="{{ route('petugas.rute.tugas') }}" class="flex flex-col items-center justify-center p-5 bg-white hover:bg-emerald-50 border border-gray-100 rounded-2xl shadow-sm transition text-center group">
                    <div class="bg-emerald-100 p-3 rounded-2xl mb-2 text-emerald-700 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Tugas Harian</span>
                    <span class="text-[11px] text-gray-400 mt-0.5">(Peta, Navigasi & Dokumentasi)</span>
                </a>

                <!-- Modul 11: Disposisi Pengaduan -->
                <a href="{{ route('petugas.pengaduan.index') }}" class="flex flex-col items-center justify-center p-5 bg-white hover:bg-amber-50 border border-gray-100 rounded-2xl shadow-sm transition text-center group relative">
                    <span class="absolute top-3 right-3 flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                    <div class="bg-amber-100 p-3 rounded-2xl mb-2 text-amber-700 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Disposisi Aduan</span>
                    <span class="text-[11px] text-gray-400 mt-0.5">(Pengaduan)</span>
                </a>

                <!-- Modul 6: Slip Gaji Bulanan -->
                <a href="{{ route('petugas.gaji.index') }}" class="flex flex-col items-center justify-center p-5 bg-white hover:bg-purple-50 border border-gray-100 rounded-2xl shadow-sm transition text-center group">
                    <div class="bg-purple-100 p-3 rounded-2xl mb-2 text-purple-700 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Cek Slip Gaji</span>
                    <span class="text-[11px] text-gray-400 mt-0.5">(Gaji & Insentif)</span>
                </a>
            </div>

<!-- MODUL 3: TARGET & MONITORING PENGANGKUTAN HARI INI (HANYA RUTE YANG DITUGASKAN) -->
@php
    $totalPengangkutan = $routesHariIni->sum('total');
    $selesaiPengangkutan = $routesHariIni->sum('selesai');
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h4 class="font-bold text-sm text-gray-800">Rute Tugas Saya</h4>
            <p class="text-xs text-gray-400">Hanya menampilkan rute yang ditugaskan admin untuk Anda</p>
        </div>
        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
            {{ $selesaiPengangkutan }} / {{ max($totalPengangkutan, 1) }} Selesai
        </span>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($routesHariIni as $rute)
            @php
                if ($rute->status == 'Selesai') {
                    $iconClass = 'bg-green-100 text-green-700';
                    $badgeClass = 'bg-green-50 text-green-700';
                } elseif ($rute->status == 'Sedang dikerjakan') {
                    $iconClass = 'bg-emerald-600 text-white animate-pulse';
                    $badgeClass = 'bg-emerald-200 text-emerald-800';
                } else {
                    $iconClass = 'bg-gray-100 text-gray-500';
                    $badgeClass = 'bg-gray-100 text-gray-500';
                }
            @endphp

            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="{{ $iconClass }} p-2 rounded-xl">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                @if($rute->status == 'Selesai')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $rute->nama_rute }}</p>
                            <p class="text-xs text-gray-400">Jadwal: {{ $rute->hari_angkut }} • {{ $rute->armada?->nama_kendaraan ?? 'Belum ada armada' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold {{ $badgeClass }} px-2.5 py-1 rounded-lg">{{ $rute->status }}</span>
                        <a href="{{ route('petugas.rute.detail', $rute->id) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow transition">
                            Detail
                        </a>
                    </div>
                </div>

                <div class="mt-3 border border-gray-100 bg-gray-50/60 rounded-xl divide-y divide-gray-100">
                    @foreach($rute->warga as $w)
                        @php
                            if ($w->status_tugas == 'Selesai') {
                                $wBadge = 'bg-green-100 text-green-700';
                            } elseif ($w->status_tugas == 'Sedang dikerjakan') {
                                $wBadge = 'bg-emerald-100 text-emerald-700';
                            } else {
                                $wBadge = 'bg-gray-100 text-gray-500';
                            }
                        @endphp
                        <div class="flex items-center justify-between gap-3 px-3 py-2.5">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate">{{ $w->nama_warga }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ $w->alamat_lengkap }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $wBadge }}">{{ $w->status_tugas }}</span>
                                @if($w->status_tugas != 'Selesai')
                                    <form action="{{ route('petugas.rute.update', $w->pengangkutan_id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="selesai">
                                        <button class="text-[10px] font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-lg transition">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <p class="text-sm font-bold text-gray-700">Belum Ada Penugasan</p>
                <p class="text-xs text-gray-400 mt-1">Admin belum menugaskan Anda ke rute mana pun. Hubungi admin jika ada kendala.</p>
            </div>
        @endforelse
    </div>
</div>

            <!-- MODUL: LAPORAN KENDALA / PENGADUAN TERBARU -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h4 class="font-bold text-sm text-gray-800">Laporan Kendala Saya</h4>
            <p class="text-xs text-gray-400">Kendala operasional yang pernah Anda laporkan</p>
        </div>
        <a href="{{ route('petugas.laporan.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua</a>
    </div>
    <div class="p-4 divide-y divide-gray-100">
        @forelse($laporanTerbaru as $item)
            <div class="py-3 first:pt-0 last:pb-0 flex items-start justify-between gap-3">
                <div class="space-y-1">
                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">{{ $item->tipe_kendala }}</span>
                    <p class="text-xs text-gray-600 line-clamp-2">{{ $item->deskripsi ?? 'Kendala operasional' }}</p>
                    <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @empty
            <div class="py-3 text-center text-xs text-gray-400">
                Belum ada laporan kendala dari Anda.
            </div>
        @endforelse
    </div>
</div>

        </main>

        <x-petugas-bottom-nav />

    </div>
</x-app-layout>