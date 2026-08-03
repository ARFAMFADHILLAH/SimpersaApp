<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">

        <x-manager-sidebar />

        <main class="flex-1 py-8 px-6 sm:px-8 lg:px-10 overflow-y-auto">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- Header Halaman -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-4 border-gray-200 gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Executive Dashboard</h1>
                        <p class="text-xs text-gray-500 mt-1">Monitoring High-Level & Analisis Pengambilan Keputusan Real-Time</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> Mode Pimpinan
                        </span>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- MODUL 12: SYSTEM DECISION SUPPORT (DSS)    -->
                <!-- ========================================== -->
                <div class="bg-gradient-to-r from-green-900 via-emerald-950 to-green-900 text-white p-6 rounded-2xl shadow-xl border border-green-900/50">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="bg-green-500/30 text-green-300 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider border border-green-500/40">
                                    DSS Assistant
                                </span>
                                <span class="text-xs text-gray-300">Rekomendasi Keputusan Berbasis Data</span>
                            </div>
                            <h3 class="text-xl font-extrabold text-white">Evaluasi & Penilaian Wilayah Prioritas</h3>
                            <p class="text-xs text-gray-300 max-w-2xl leading-relaxed">
                                Sistem menganalisis rasio tunggakan, tren timbulan sampah harian, serta beban kerja armada. Disarankan penambahan rute armada di wilayah dengan pertumbuhan sampah terbesar bulan ini.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto shrink-0">
                            <a href="{{ Route::has('manager.dss.index') ? route('manager.dss.index') : '#' }}" class="inline-flex justify-center items-center bg-green-600 hover:bg-green-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow transition">
                                Evaluasi Prioritas Wilayah
                            </a>
                            <a href="{{ Route::has('manager.laporan.index') ? route('manager.laporan.index') : '#' }}" class="inline-flex justify-center items-center bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-bold px-4 py-2.5 rounded-xl border border-gray-700 transition">
                                Unduh Rekap Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- METRIK RINGKASAN DATA (KPI CARDS) -->
                <!-- ========================================== -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    <!-- 1. Pelanggan & Tunggakan -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between text-gray-500 mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider">Status Pelanggan</span>
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black text-gray-900">{{ number_format($totalPelanggan ?? 0) }} <span class="text-xs font-normal text-gray-500">Warga</span></h4>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-green-600 font-bold">● {{ number_format($pelangganAktif ?? 0) }} Aktif</span>
                            <span class="text-red-600 font-bold">● {{ number_format($pelangganMenunggak ?? 0) }} Menunggak</span>
                        </div>
                    </div>

                    <!-- 2. Pendapatan Iuran -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between text-gray-500 mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider">Pendapatan Iuran</span>
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black text-gray-900">Rp {{ number_format($totalPendapatanIuran ?? 0, 0, ',', '.') }}</h4>
                        <p class="text-[11px] text-gray-500 mt-1">Total penerimaan kas iuran bulan ini</p>
                    </div>

                    <!-- 3. Beban Operasional & Gaji -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between text-gray-500 mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider">Operasional & Gaji</span>
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black text-gray-900">Rp {{ number_format(($totalBiayaOperasional ?? 0) + ($totalGajiPetugas ?? 0), 0, ',', '.') }}</h4>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                            <span>BBM/Servis: <b>Rp {{ number_format($totalBiayaOperasional ?? 0, 0, ',', '.') }}</b></span>
                            <span>Gaji: <b>Rp {{ number_format($totalGajiPetugas ?? 0, 0, ',', '.') }}</b></span>
                        </div>
                    </div>

                    <!-- 4. Total Volume Sampah -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between text-gray-500 mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider">Volume Terangkut</span>
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black text-gray-900">{{ number_format($volumeSampahBulanIni ?? 0, 1, ',', '.') }} <span class="text-xs font-normal text-gray-500">m³</span></h4>
                        <p class="text-[11px] text-gray-500 mt-1">Hari ini: <b>{{ number_format($volumeSampahHariIni ?? 0, 1, ',', '.') }} m³</b></p>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- GRAFIK 12 BULAN (MODUL 9)                  -->
                <!-- ========================================== -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h3 class="text-base font-extrabold text-gray-900 mb-4">Grafik Pendapatan Iuran</h3>
                        <canvas id="grafikPembayaran" height="140"></canvas>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h3 class="text-base font-extrabold text-gray-900 mb-4">Grafik Volume Sampah (m&sup3;)</h3>
                        <canvas id="grafikVolume" height="140"></canvas>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h3 class="text-base font-extrabold text-gray-900 mb-4">Grafik Biaya Operasional</h3>
                        <canvas id="grafikBiaya" height="140"></canvas>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- STATISTIK OPERASIONAL & PRODUKTIVITAS      -->
                <!-- ========================================== -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Status Kesiapan Armada & Petugas -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h3 class="text-base font-extrabold text-gray-900 mb-4">Status Armada & Petugas</h3>
                        <div class="space-y-4">
                            <!-- Kendaraan -->
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-gray-600">Kondisi Armada Kendaraan</span>
                                    <span class="text-gray-900">{{ $kendaraanAktif ?? 0 }} Siap / {{ $kendaraanRusak ?? 0 }} Perbaikan</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden flex">
                                    @php
                                        $totalKendaraan = ($kendaraanAktif ?? 0) + ($kendaraanRusak ?? 0);
                                        $persenAktif = $totalKendaraan > 0 ? (($kendaraanAktif ?? 0) / $totalKendaraan) * 100 : 0;
                                    @endphp
                                    <div class="bg-green-500 h-2.5" style="width: {{ $persenAktif }}%"></div>
                                    <div class="bg-red-500 h-2.5" style="width: {{ 100 - $persenAktif }}%"></div>
                                </div>
                            </div>

                            <!-- Produktivitas Tugas -->
                            <div class="pt-3 border-t border-gray-100">
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-gray-600">Penyelesaian Rute</span>
                                    <span class="text-gray-900">{{ $totalRuteHariIni ?? 0 }} Rute</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    @php
                                        $persenRute = ($totalRuteHariIni ?? 0) > 0 ? 100 : 0;
                                    @endphp
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $persenRute }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 grid grid-cols-2 gap-2 text-center text-xs">
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <span class="block text-gray-400 font-semibold">Petugas Hadir</span>
                                <span class="font-extrabold text-gray-800 text-sm mt-0.5 block">{{ $petugasHadir ?? 0 }} Orang</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <span class="block text-gray-400 font-semibold">Pengaduan Baru</span>
                                <span class="font-extrabold text-red-600 text-sm mt-0.5 block">{{ $pengaduanBaru ?? 0 }} Kasus</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modul 9: Produktivitas Petugas -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h3 class="text-base font-extrabold text-gray-900 mb-4">Produktivitas Petugas (Bulan Ini)</h3>
                        @if(($produktivitasPetugas ?? collect())->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($produktivitasPetugas as $prod)
                                    <div>
                                        <div class="flex justify-between text-xs font-semibold mb-1">
                                            <span class="text-gray-600">{{ $prod->nama_petugas }}</span>
                                            <span class="text-gray-900">{{ $prod->tugas_selesai }}/{{ $prod->total_tugas }} tugas</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $prod->persentase }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400">Belum ada data penugasan bulan ini.</p>
                        @endif
                    </div>

                    <!-- Modul 10: Quick Link Akses Laporan -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-gray-900">Pusat Laporan Eksekutif</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Filter, cetak, dan unduh data operasional & keuangan secara berkala.</p>
                                </div>
                                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-lg">Cetak Ready</span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <a href="{{ Route::has('manager.laporan.pelanggan') ? route('manager.laporan.pelanggan') : '#' }}" class="p-3 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 transition text-left group">
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-green-600">Laporan Pelanggan</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Status & Tunggakan</span>
                                </a>

                                <a href="{{ Route::has('manager.laporan.iuran') ? route('manager.laporan.iuran') : '#' }}" class="p-3 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 transition text-left group">
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-green-600">Laporan Iuran</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Penerimaan & Arus Kas</span>
                                </a>

                                <a href="{{ Route::has('manager.laporan.volume') ? route('manager.laporan.volume') : '#' }}" class="p-3 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 transition text-left group">
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-green-600">Laporan Sampah</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Rekap Volume & TPA</span>
                                </a>

                                <a href="{{ Route::has('manager.laporan.keuangan') ? route('manager.laporan.keuangan') : '#' }}" class="p-3 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 transition text-left group">
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-green-600">Laporan Keuangan</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Laba Rugi & Kas</span>
                                </a>

                                <a href="{{ Route::has('manager.laporan.gaji') ? route('manager.laporan.gaji') : '#' }}" class="p-3 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 transition text-left group">
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-green-600">Laporan Gaji</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Rekap Payroll Petugas</span>
                                </a>

                                <a href="{{ Route::has('manager.laporan.armada') ? route('manager.laporan.armada') : '#' }}" class="p-3 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 transition text-left group">
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-green-600">Laporan Armada</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Servis & Biaya BBM</span>
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Format Laporan Dukung Export PDF & Excel</span>
                            <a href="{{ Route::has('manager.laporan.index') ? route('manager.laporan.index') : '#' }}" class="text-xs font-bold text-green-600 hover:text-green-800">
                                Lihat Semua Jenis Laporan &rarr;
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>
    <x-manager-bottom-nav />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const grafikPembayaran = @json($grafikPembayaran ?? []);
        const grafikVolume = @json($grafikVolume ?? []);
        const grafikBiaya = @json($grafikBiaya ?? []);

        const chartOptions = {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('id-ID') } } }
        };

        new Chart(document.getElementById('grafikPembayaran'), {
            type: 'bar',
            data: {
                labels: grafikPembayaran.map(d => d.bulan),
                datasets: [{
                    label: 'Pendapatan Iuran',
                    data: grafikPembayaran.map(d => d.total),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: chartOptions
        });

        new Chart(document.getElementById('grafikVolume'), {
            type: 'line',
            data: {
                labels: grafikVolume.map(d => d.bulan),
                datasets: [{
                    label: 'Volume (m³)',
                    data: grafikVolume.map(d => d.total),
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: { ...chartOptions, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('grafikBiaya'), {
            type: 'bar',
            data: {
                labels: grafikBiaya.map(d => d.bulan),
                datasets: [{
                    label: 'Biaya Operasional',
                    data: grafikBiaya.map(d => d.total),
                    backgroundColor: 'rgba(244, 63, 94, 0.6)',
                    borderColor: '#e11d48',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: chartOptions
        });
    </script>
</x-app-layout>