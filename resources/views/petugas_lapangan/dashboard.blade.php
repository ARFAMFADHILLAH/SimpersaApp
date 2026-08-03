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
                        <span class="text-xs text-emerald-600 font-medium">Truk Isuzu (B 1234 XYZ)</span>
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
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <!-- Tombol Clock In -->
                    <form action="{{ route('petugas.absensi.clockin') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            Clock In (Masuk)
                        </button>
                    </form>
                    <!-- Tombol Clock Out -->
                    <form action="{{ route('petugas.absensi.clockout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-red-600/80 hover:bg-red-600 text-white font-bold rounded-xl shadow transition flex items-center justify-center gap-2 text-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Clock Out (Pulang)
                        </button>
                    </form>
                </div>
            </div>

            <!-- GRID AKSI UTAMA (QUICK ACTION MODUL 3, 4, 6, 11) -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Modul 3: Lihat Rute & Mulai Pengangkutan -->
                <a href="{{ route('petugas.rute.index') }}" class="flex flex-col items-center justify-center p-5 bg-white hover:bg-emerald-50 border border-gray-100 rounded-2xl shadow-sm transition text-center group">
                    <div class="bg-emerald-100 p-3 rounded-2xl mb-2 text-emerald-700 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Rute Harian</span>
                    <span class="text-[11px] text-gray-400 mt-0.5">(Pengangkutan)</span>
                </a>

                <!-- Modul 4: Input Volume/Berat Sampah -->
                <a href="{{ route('petugas.pengangkutan.create') }}" class="flex flex-col items-center justify-center p-5 bg-white hover:bg-blue-50 border border-gray-100 rounded-2xl shadow-sm transition text-center group">
                    <div class="bg-blue-100 p-3 rounded-2xl mb-2 text-blue-700 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 18h12l3-18H3zm3 0V4a2 2 0 012-2h8a2 2 0 012 2v2" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Input Sampah</span>
                    <span class="text-[11px] text-gray-400 mt-0.5">(Volume/Berat)</span>
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

<!-- MODUL 3: TARGET & MONITORING PENGANGKUTAN HARI INI -->
@php
    // Mengambil data rute asli dari database
    $dataRute = \Illuminate\Support\Facades\DB::table('rute')->take(3)->get();
    
    // Menghitung statistik berdasarkan status di tabel pengangkutan
    $totalPengangkutan = \Illuminate\Support\Facades\DB::table('pengangkutan')->count();
    $selesaiPengangkutan = \Illuminate\Support\Facades\DB::table('pengangkutan')->where('status_tugas', 'Selesai')->count();
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h4 class="font-bold text-sm text-gray-800">Rute Pengangkutan Hari Ini</h4>
            <p class="text-xs text-gray-400">Update Status & Upload Foto</p>
        </div>
        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
            {{ $selesaiPengangkutan }} / {{ max($totalPengangkutan, 1) }} Selesai
        </span>
    </div>
    
    <div class="divide-y divide-gray-100">
        @forelse($dataRute as $index => $rute)
            @php
                // Logika status visual dinamis berdasarkan urutan / data rute
                $statusList = ['Selesai', 'Sedang Dikerjakan', 'Belum Dikerjakan'];
                $status = $statusList[$index % count($statusList)];
                
                if ($status == 'Selesai') {
                    $bgCard = '';
                    $iconClass = 'bg-green-100 text-green-700';
                    $badgeClass = 'bg-green-50 text-green-700';
                    $isSelesai = true;
                } elseif ($status == 'Sedang Dikerjakan') {
                    $bgCard = 'bg-emerald-50/40';
                    $iconClass = 'bg-emerald-600 text-white animate-pulse';
                    $badgeClass = 'bg-emerald-200 text-emerald-800';
                    $isSelesai = false;
                } else {
                    $bgCard = 'opacity-60';
                    $iconClass = 'bg-gray-100 text-gray-500';
                    $badgeClass = 'bg-gray-100 text-gray-500';
                    $isSelesai = false;
                }
            @endphp

            <div class="p-4 {{ $bgCard }} flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="{{ $iconClass }} p-2 rounded-xl">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            @if($isSelesai)
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @endif
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $rute->nama_rute }}</p>
                        <p class="text-xs text-gray-400">Jadwal: {{ $rute->hari_angkut }} • {{ $rute->keterangan ?? 'Operasional Reguler' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold {{ $badgeClass }} px-2.5 py-1 rounded-lg">{{ $status }}</span>
                    <a href="{{ route('petugas.rute.detail', $rute->id) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow transition">
                        Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-xs text-gray-400">
                Belum ada data rute pengangkutan di database.
            </div>
        @endforelse
    </div>
</div>

            <!-- MODUL: LAPORAN KENDALA / PENGADUAN TERBARU -->
@php
    $laporanTerbaru = \Illuminate\Support\Facades\DB::table('laporan_kendalas')->latest()->take(3)->get();
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h4 class="font-bold text-sm text-gray-800">Laporan Kendala Lapangan</h4>
            <p class="text-xs text-gray-400">Daftar kendala operasional terbaru</p>
        </div>
        <a href="{{ route('petugas.pengaduan.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua</a>
    </div>
    <div class="p-4 divide-y divide-gray-100">
        @forelse($laporanTerbaru as $item)
            <div class="py-3 first:pt-0 last:pb-0 flex items-start justify-between gap-3">
                <div class="space-y-1">
                    <p class="text-xs text-gray-600 line-clamp-2">"{{ $item->keterangan ?? 'Kendala operasional' }}"</p>
                    <span class="text-[10px] text-gray-400">{{ $item->created_at }}</span>
                </div>
                <a href="{{ route('petugas.pengaduan.show', $item->id) }}" class="px-3 py-1.5 bg-gray-800 hover:bg-black text-white text-xs font-bold rounded-lg shrink-0">
                    Detail
                </a>
            </div>
        @empty
            <div class="py-3 text-center text-xs text-gray-400">
                Belum ada laporan kendala.
            </div>
        @endforelse
    </div>
</div>

        </main>

        <x-petugas-bottom-nav />

    </div>
</x-app-layout>