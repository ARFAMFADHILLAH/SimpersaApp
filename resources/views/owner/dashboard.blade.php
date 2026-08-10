<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Pimpinan — Owner</h1>
                    <p class="text-sm text-gray-500 mt-1">Pemantauan kinerja bank sampah, {{ now()->format('d M Y') }}</p>
                </div>

                <!-- Kartu Utama -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nasabah</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalNasabah, 0, ',', '.') }} warga</p>
                        <p class="text-xs text-gray-400 mt-1">saldo tabungan Rp {{ number_format($totalSaldoTabungan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sampah Dikumpulkan (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalKgBulanIni, 2, ',', '.') }} kg</p>
                        <p class="text-xs text-gray-400 mt-1">belanja Rp {{ number_format($totalBelanjaBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pemasukan (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalMasukBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">dari penjualan ke pengepul</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 {{ $labaBulanIni >= 0 ? 'border-blue-500' : 'border-red-600' }}">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Laba Bulan Ini</p>
                        <p class="text-2xl font-bold {{ $labaBulanIni >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">Rp {{ number_format($labaBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">keluar Rp {{ number_format($totalKeluarBulanIni, 0, ',', '.') }} (termasuk gaji Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }})</p>
                    </div>
                </div>

                <!-- Grafik 12 Bulan -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Kas 12 Bulan (Masuk vs Keluar)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 border-b">
                                    <th class="py-2">Bulan</th>
                                    <th class="py-2 text-right">Masuk (Rp)</th>
                                    <th class="py-2 text-right">Keluar (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grafikBulan as $i => $bulan)
                                    <tr class="border-b">
                                        <td class="py-2 font-medium">{{ $bulan }}</td>
                                        <td class="py-2 text-right text-green-600">{{ number_format($grafikMasuk[$i], 0, ',', '.') }}</td>
                                        <td class="py-2 text-right text-red-500">{{ number_format($grafikKeluar[$i], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Penjualan Terbaru -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Penjualan ke Pengepul Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 border-b">
                                        <th class="py-2">Jenis</th><th class="py-2 text-right">Berat</th><th class="py-2 text-right">Total</th><th class="py-2">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penjualanTerbaru as $p)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">{{ $p->jenisSampah->nama_jenis ?? '-' }}</td>
                                            <td class="py-2 text-right">{{ number_format($p->berat_kg, 2, ',', '.') }} kg</td>
                                            <td class="py-2 text-right">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                            <td class="py-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_penjualan)->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-4 text-center text-gray-500">Belum ada penjualan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Setoran Terbaru -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Setoran Warga Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 border-b">
                                        <th class="py-2">Warga</th><th class="py-2 text-right">Berat</th><th class="py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($setoranTerbaru as $s)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">{{ $s->warga->user->name ?? 'Warga' }}</td>
                                            <td class="py-2 text-right">{{ number_format($s->berat_kg, 2, ',', '.') }} kg</td>
                                            <td class="py-2 text-right">Rp {{ number_format($s->total_bayar, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-4 text-center text-gray-500">Belum ada setoran.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('owner.laporan.index') }}" class="text-sm font-semibold text-green-600 hover:text-green-800">Buka Pusat Laporan &raquo;</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>