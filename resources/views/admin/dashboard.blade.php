<x-app-layout>
    <!-- Flex container untuk membagi Sidebar (Kiri) dan Konten (Kanan) -->
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">

        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

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

                <!-- BARIS 2: METRIK OPERASIONAL & PELANGGAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Box Kiri: Data Pelanggan -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">👥 Status Kepelangganan</h4>
                        <div class="grid grid-cols-3 gap-2 text-center mb-4">
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-xs text-gray-500">Total</span>
                                <div class="text-xl font-bold text-gray-800">{{ $totalPelanggan ?? 0 }}</div>
                            </div>
                            <div class="bg-green-50 p-3 rounded">
                                <span class="text-xs text-green-600">Aktif</span>
                                <div class="text-xl font-bold text-green-700">{{ $pelangganAktif ?? 0 }}</div>
                            </div>
                            <div class="bg-red-50 p-3 rounded">
                                <span class="text-xs text-red-600">Menunggak</span>
                                <div class="text-xl font-bold text-red-700">{{ $pelangganMenunggak ?? 0 }}</div>
                            </div>
                        </div>

                        @if(($daftarMenunggak ?? collect())->isNotEmpty())
                            <div class="border-t pt-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Detail Tunggakan</p>
                                <div class="overflow-x-auto max-h-48 overflow-y-auto">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="text-gray-400 uppercase text-[10px]">
                                                <th class="text-left py-1 pr-2">Pelanggan</th>
                                                <th class="text-center py-1 px-2">Blm Bayar</th>
                                                <th class="text-right py-1 pl-2">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($daftarMenunggak as $m)
                                                <tr class="hover:bg-red-50">
                                                    <td class="py-1.5 pr-2 font-medium text-gray-900 truncate max-w-[120px]">
                                                        {{ $m->pelanggan->user->name ?? '-' }}
                                                        <span class="text-gray-400 font-mono">({{ $m->pelanggan->no_pelanggan ?? '-' }})</span>
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

                <!-- BARIS 4: TABEL ADUAN MASYARAKAT & PELANGGAN -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">📢</span>
                            <h4 class="text-gray-800 font-semibold">Pengaduan & Aspirasi Pelanggan Terbaru</h4>
                        </div>
                        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ count($pengaduanTerbaru ?? []) }} Menunggu Respon
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Pelanggan / No ID</th>
                                    <th class="px-6 py-3 text-left">Tipe Keluhan</th>
                                    <th class="px-6 py-3 text-left">Isi Pengaduan</th>
                                    <th class="px-6 py-3 text-left">Status Respon</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($pengaduanTerbaru ?? [] as $aduan)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $aduan->pelanggan?->user?->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $aduan->pelanggan?->no_pelanggan ?? '-' }}</div>
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
                                @if(($pelangganMenunggak ?? 0) > 0)
                                    Terdeteksi ada {{ $pelangganMenunggak }} pelanggan yang menunggak iuran. Sistem menyarankan Anda untuk segera mengirimkan <span class="underline font-bold text-indigo-900">Notifikasi Pengingat Jatuh Tempo</span> otomatis.
                                @else
                                    Neraca kas dan status kepelangganan terpantau stabil. Alokasi anggaran BBM operasional dapat dijadwatch ulang secara aman.
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