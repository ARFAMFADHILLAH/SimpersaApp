<x-app-layout>
    <!-- Flex container untuk membagi Sidebar (Kiri) dan Konten (Kanan) -->
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">

        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- AKSES PINTAS -->
                <div>
                    <h3 class="text-gray-600 font-bold uppercase tracking-wider text-sm">⚡ Akses Pintas</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mt-4">
                        <a href="{{ route('admin.warga.create') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Pendaftaran Warga</span>
                        </a>
                        <a href="{{ route('admin.master.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M10 12h4" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Master Data</span>
                        </a>
                        <a href="{{ route('admin.operasional.rekap-volume') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Rekap Sampah</span>
                        </a>
                        <a href="{{ route('admin.operasional.jadwal-rute') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Jadwal Rute</span>
                        </a>
                        <a href="{{ route('admin.pengaduan.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Pengaduan</span>
                        </a>
                        <a href="{{ route('admin.jenis-sampah.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Jenis Sampah & Tarif</span>
                        </a>
                        <a href="{{ route('admin.iuran.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Iuran & Denda</span>
                        </a>
                        <a href="{{ route('admin.wilayah.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Wilayah Pelayanan</span>
                        </a>
                        <a href="{{ route('admin.armada.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4zM4 17h1m11 0h1m2 0h1a2 2 0 002-2v-3a1 1 0 00-.293-.707l-3-3A1 1 0 0015.293 8H13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2h0" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Armada</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Pengguna & Staf</span>
                        </a>
                        <a href="{{ route('admin.warga.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span class="text-xs font-semibold text-gray-700">Data Warga</span>
                        </a>
                        <a href="{{ route('admin.tps.index') }}" class="bg-white p-4 rounded-lg shadow border border-gray-100 hover:border-green-300 hover:shadow-md transition">
                            <svg class="h-5 w-5 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            <span class="text-xs font-semibold text-gray-700">TPS</span>
                        </a>
                    </div>
                </div>

                <!-- OPERASIONAL HARIAN -->
                <div>
                    <h3 class="text-gray-600 font-bold uppercase tracking-wider text-sm">🏭 Operasional Hari Ini</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
                        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-l-green-500">
                            <p class="text-sm font-medium text-gray-500">Total Warga</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalWarga ?? 0) }}</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-l-green-500">
                            <p class="text-sm font-medium text-gray-500">Armada Aktif</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalArmadaAktif ?? 0 }}</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-l-red-500">
                            <p class="text-sm font-medium text-gray-500">Pengaduan Baru</p>
                            <p class="text-2xl font-bold text-red-600 mt-1">{{ $pengaduanBaru ?? 0 }}</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-l-blue-500">
                            <p class="text-sm font-medium text-gray-500">Pengangkutan (Bulan Ini)</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalPengangkutanBulanIni ?? 0 }}</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-l-indigo-500">
                            <p class="text-sm font-medium text-gray-500">Volume (Bulan Ini)</p>
                            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($totalVolumeBulanIni ?? 0, 1) }} m&sup3;</p>
                        </div>
                    </div>
                </div>

                <!-- BARIS 1: RINGKASAN KEUANGAN -->
                <h3 class="text-gray-600 font-bold uppercase tracking-wider text-sm">💰 Ikhtisar Keuangan & Neraca Kas</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                        <div class="text-sm text-gray-500 font-medium">Total Pendapatan (Iuran)</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-orange-500">
                        <div class="text-sm text-gray-500 font-medium">Pengeluaran Gaji</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalGaji ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                        <div class="text-sm text-gray-500 font-medium">Biaya Operasional & BBM</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalOperasional ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 {{ ($labaRugiBersih ?? 0) >= 0 ? 'border-blue-500' : 'border-purple-600' }}">
                        <div class="text-sm text-gray-500 font-medium">Laba / Rugi Bersih</div>
                        <div class="text-2xl font-bold mt-1 {{ ($labaRugiBersih ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            Rp {{ number_format($labaRugiBersih ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- BARIS 2: METRIK OPERASIONAL & WARGA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Box Kiri: Data Warga -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">👥 Status Kewargaan</h4>
                        <div class="grid grid-cols-3 gap-2 text-center mb-4">
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-xs text-gray-500">Total</span>
                                <div class="text-xl font-bold text-gray-800">{{ $totalWarga ?? 0 }}</div>
                            </div>
                            <div class="bg-green-50 p-3 rounded">
                                <span class="text-xs text-green-600">Aktif</span>
                                <div class="text-xl font-bold text-green-700">{{ $wargaAktif ?? 0 }}</div>
                            </div>
                            <div class="bg-red-50 p-3 rounded">
                                <span class="text-xs text-red-600">Menunggak</span>
                                <div class="text-xl font-bold text-red-700">{{ $wargaMenunggak ?? 0 }}</div>
                            </div>
                        </div>

                        @if(($daftarMenunggak ?? collect())->isNotEmpty())
                            <div class="border-t pt-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Detail Tunggakan</p>
                                <div class="overflow-x-auto max-h-48 overflow-y-auto">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="text-gray-400 uppercase text-[10px]">
                                                <th class="text-left py-1 pr-2">Warga</th>
                                                <th class="text-center py-1 px-2">Blm Bayar</th>
                                                <th class="text-right py-1 pl-2">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($daftarMenunggak as $m)
                                                <tr class="hover:bg-red-50">
                                                    <td class="py-1.5 pr-2 font-medium text-gray-900 truncate max-w-[120px]">
                                                        {{ $m->warga->user->name ?? '-' }}
                                                        <span class="text-gray-400 font-mono">({{ $m->warga->no_warga ?? '-' }})</span>
                                                    </td>
                                                    <td class="py-1.5 px-2 text-center font-semibold text-red-600">{{ $m->jumlah_blm_bayar }}x</td>
                                                    <td class="py-1.5 pl-2 text-right font-semibold text-red-700">Rp {{ number_format($m->total_tunggakan, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Box Kanan: Data Produksi Sampah & Armada -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">🚚 Logistik & Volume Sampah Terkelola</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded">
                                <div>
                                    <span class="text-xs text-gray-500">Total Akumulasi Sampah</span>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($totalVolumeSampah ?? 0, 1, ',', '.') }} m³ / {{ number_format($totalBeratSampah ?? 0, 0, ',', '.') }} Kg
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded">
                                <div>
                                    <span class="text-xs text-gray-500">Kesiapan Armada</span>
                                    <div class="text-sm font-semibold text-gray-800">
                                        🟢 Aktif: {{ $armadaAktif ?? 0 }} | 🔴 Rusak: {{ $armadaRusak ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BARIS 3: GRAFIK 12 BULAN (MODUL 9) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">📈 Pendapatan Iuran 12 Bulan</h4>
                        <canvas id="grafikPembayaran" height="140"></canvas>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">🗑️ Volume Sampah 12 Bulan (m&sup3;)</h4>
                        <canvas id="grafikVolume" height="140"></canvas>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">🚛 Biaya Operasional 12 Bulan</h4>
                        <canvas id="grafikBiaya" height="140"></canvas>
                    </div>
                </div>

                <!-- BARIS 4: PENGANGKUTAN TERBARU -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-gray-800 font-semibold">🚛 Pengangkutan Terbaru</h4>
                        <span class="text-xs text-gray-400">{{ count($pengangkutanTerbaru ?? []) }} catatan</span>
                    </div>
                    <div class="space-y-3">
                        @forelse($pengangkutanTerbaru ?? [] as $angkut)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $angkut->warga?->user?->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $angkut->armada?->nama_kendaraan ?? '-' }} &middot; {{ $angkut->volume_m3 }} m&sup3;</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($angkut->tanggal_tugas)->format('d/m/Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada pengangkutan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- BARIS 4: TABEL ADUAN MASYARAKAT & WARGA -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">📢</span>
                            <h4 class="text-gray-800 font-semibold">Pengaduan & Aspirasi Warga Terbaru</h4>
                        </div>
                        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ count($pengaduanTerbaru ?? []) }} Menunggu Respon
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Warga / No ID</th>
                                    <th class="px-6 py-3 text-left">Tipe Keluhan</th>
                                    <th class="px-6 py-3 text-left">Isi Pengaduan</th>
                                    <th class="px-6 py-3 text-left">Status Respon</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($pengaduanTerbaru ?? [] as $aduan)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $aduan->warga?->user?->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $aduan->warga?->no_warga ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                {{ $aduan->tipe_kendala }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs max-w-xs truncate">
                                            {{ $aduan->catatan_lokasi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                                            @if($aduan->status_respon == 'Belum Dikerjakan')
                                                <span class="text-red-600 font-semibold flex items-center gap-1">🔴 Belum Direspon</span>
                                            @elseif($aduan->status_respon == 'Sedang Dikerjakan')
                                                <span class="text-amber-600 font-semibold flex items-center gap-1">🟡 Petugas Menuju Lokasi</span>
                                            @else
                                                <span class="text-green-600 font-semibold flex items-center gap-1">🟢 Selesai / Bersih</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                            🎉 Hebat! Seluruh pengaduan dan keluhan masyarakat telah ditangani sepenuhnya.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BARIS 4: TABEL LAPORAN KENDALA LAPANGAN REAL-TIME -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">⚠️</span>
                            <h4 class="text-gray-800 font-semibold">Laporan Kendala Lapangan (Tim Teknis)</h4>
                        </div>
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $kendalaHariIni ?? 0 }} Hari Ini
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tanggal & Waktu</th>
                                    <th class="px-6 py-3 text-left">Petugas Lapangan</th>
                                    <th class="px-6 py-3 text-left">Lokasi / Sektor</th>
                                    <th class="px-6 py-3 text-left">Deskripsi Kendala</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($kendalaTerbaru ?? [] as $kendala)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                            {{ $kendala->created_at ? \Carbon\Carbon::parse($kendala->created_at)->translatedFormat('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 font-medium whitespace-nowrap">
                                            {{ $kendala->nama_petugas }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-800 border border-zinc-200">
                                                {{ $kendala->lokasi }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $kendala->deskripsi }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                            ✨ Bersih! Belum ada laporan kendala lapangan yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ANJURAN SISTEM PENDUKUNG KEPUTUSAN CEPAT (DSS) -->
                <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg flex items-center justify-between">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-indigo-600 font-bold">💡 DSS Hint :</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-indigo-700 font-medium">
                                @if(($wargaMenunggak ?? 0) > 0)
                                    Terdeteksi ada {{ $wargaMenunggak }} warga yang menunggak iuran. Sistem menyarankan Anda untuk segera mengirimkan <span class="underline font-bold text-indigo-900">Notifikasi Pengingat Jatuh Tempo</span> otomatis.
                                @else
                                    Neraca kas dan status kewargaan terpantau stabil. Alokasi anggaran BBM operasional dapat dijadwatch ulang secara aman.
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.keputusan.index') }}" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-md hover:bg-indigo-700 transition shrink-0 ml-4">
                        Buka Modul DSS &rarr;
                    </a>
                </div>

            </div>
        </main>
    </div>
    <x-admin-bottom-nav />

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
                datasets: [{ label: 'Pendapatan', data: grafikPembayaran.map(d => d.total), backgroundColor: 'rgba(16, 185, 129, 0.7)', borderColor: '#059669', borderWidth: 1, borderRadius: 4 }]
            },
            options: chartOptions
        });

        new Chart(document.getElementById('grafikVolume'), {
            type: 'line',
            data: {
                labels: grafikVolume.map(d => d.bulan),
                datasets: [{ label: 'Volume', data: grafikVolume.map(d => d.total), backgroundColor: 'rgba(99, 102, 241, 0.2)', borderColor: '#6366f1', borderWidth: 2, fill: true, tension: 0.35 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('grafikBiaya'), {
            type: 'bar',
            data: {
                labels: grafikBiaya.map(d => d.bulan),
                datasets: [{ label: 'Biaya', data: grafikBiaya.map(d => d.total), backgroundColor: 'rgba(244, 63, 94, 0.6)', borderColor: '#e11d48', borderWidth: 1, borderRadius: 4 }]
            },
            options: chartOptions
        });
    </script>
</x-app-layout>