<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-bendahara-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Keuangan — Bendahara</h1>
                    <p class="text-sm text-gray-500 mt-1">Ringkasan arus kas POS bank sampah, {{ now()->format('d M Y') }}</p>
                </div>

                <!-- Kartu Kas Bulan Ini -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pemasukan Kas (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">dari penjualan ke pengepul</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Belanja Warga</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalBelanjaBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">+ penarikan Rp {{ number_format($totalPenarikanBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-amber-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Gaji Dibayar</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">total pengeluaran Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 {{ $sisaKasBulanIni >= 0 ? 'border-blue-500' : 'border-red-600' }}">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sisa Kas (Laba Rugi)</p>
                        <p class="text-2xl font-bold {{ $sisaKasBulanIni >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">Rp {{ number_format($sisaKasBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">bulan {{ now()->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Grafik 6 Bulan -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Kas 6 Bulan (Masuk vs Keluar)</h3>
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
                    <!-- Transaksi Terbaru -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 border-b">
                                        <th class="py-2">Tanggal</th><th class="py-2">Keterangan</th><th class="py-2 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiTerbaru as $t)
                                        <tr class="border-b">
                                            <td class="py-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                            <td class="py-2">{{ $t->keterangan }}</td>
                                            <td class="py-2 text-right {{ $t->tipe == 'Masuk' ? 'text-green-600' : 'text-red-500' }}">
                                                {{ $t->tipe == 'Masuk' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-4 text-center text-gray-500">Belum ada transaksi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Penarikan Menunggu & Gaji Belum Bayar -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Penarikan Saldo Menunggu</h3>
                            @forelse($penarikanMenunggu as $p)
                                <div class="flex items-center justify-between py-2 border-b">
                                    <div>
                                        <p class="text-sm font-medium">{{ $p->warga->user->name ?? 'Warga' }}</p>
                                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($p->tanggal_penarikan)->format('d/m/Y') }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-amber-600">Rp {{ number_format($p->nominal, 0, ',', '.') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400">Tidak ada penarikan berstatus Diproses.</p>
                            @endforelse
                            <a href="{{ route('bendahara.tabungan.index') }}" class="inline-block mt-3 text-sm font-semibold text-green-600 hover:text-green-800">Kelola Tabungan &raquo;</a>
                        </div>

                        <div class="bg-white rounded-xl shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Slip Gaji Belum Dibayar</h3>
                            @forelse($gajiBelumBayar as $g)
                                <div class="flex items-center justify-between py-2 border-b">
                                    <p class="text-sm font-medium">{{ $g->petugas->name ?? 'Petugas' }}</p>
                                    <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($g->total_gaji_bersih, 0, ',', '.') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400">Semua slip bulan ini sudah dibayar.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>