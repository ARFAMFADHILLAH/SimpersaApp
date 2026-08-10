<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-petugas-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Konter — Petugas</h1>
                    <p class="text-sm text-gray-500 mt-1">Ringkasan transaksi penimbangan hari ini, {{ now()->format('d M Y') }}</p>
                </div>

                <!-- Kartu Hari Ini -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Setoran Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalSetoranHariIni, 0, ',', '.') }} transaksi</p>
                        <p class="text-xs text-gray-400 mt-1">{{ number_format($totalKgHariIni, 2, ',', '.') }} kg dibeli dari warga</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-indigo-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Dibayar ke Warga</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalRupiahHariIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">dikredit ke saldo tabungan</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-amber-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Penjualan Pengepul Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ number_format($totalKgJualHariIni, 2, ',', '.') }} kg terjual</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nasabah Warga</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalNasabah, 0, ',', '.') }} warga</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $penarikanMenunggu }} penarikan menunggu bendahara</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Transaksi Terbaru -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Setoran Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 border-b">
                                        <th class="py-2">Warga</th><th class="py-2">Jenis</th><th class="py-2 text-right">Berat</th><th class="py-2 text-right">Total</th><th class="py-2">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiTerbaru as $s)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">{{ $s->warga->user->name ?? 'Warga' }}</td>
                                            <td class="py-2">{{ $s->jenisSampah->nama_jenis ?? '-' }}</td>
                                            <td class="py-2 text-right">{{ number_format($s->berat_kg, 2, ',', '.') }} kg</td>
                                            <td class="py-2 text-right">Rp {{ number_format($s->total_bayar, 0, ',', '.') }}</td>
                                            <td class="py-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($s->tanggal_setoran)->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada transaksi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('petugas.pembelian.index') }}" class="inline-block text-sm font-semibold text-green-600 hover:text-green-800">&rarr; Buka Form Pembelian Sampah</a>
                        </div>
                    </div>

                    <!-- Saldo Terbesar -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Saldo Tabungan Terbesar</h3>
                        @if($saldoTerbesar)
                            <p class="text-sm text-gray-500">{{ $saldoTerbesar->user->name ?? 'Warga' }}</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($saldoTerbesar->saldo_tabungan, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-2">No. {{ $saldoTerbesar->no_warga }}</p>
                        @else
                            <p class="text-sm text-gray-400">Belum ada data warga.</p>
                        @endif
                        <div class="mt-6 pt-4 border-t">
                            <a href="{{ route('petugas.gaji.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Lihat Slip Gaji Saya &raquo;</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-petugas-bottom-nav />
</x-app-layout>