<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Ringkasan Laporan</h1>
                        <p class="text-sm text-gray-500 mt-1">Rekapitulasi keuangan tahun {{ $tahun }}</p>
                    </div>
                    <form action="{{ route('owner.laporan.index') }}" method="GET" class="flex gap-2">
                        <input type="number" name="tahun" value="{{ $tahun }}" min="2000" max="2100" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">Tampilkan</button>
                    </form>
                </div>

                <!-- KARTU TAHUNAN -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pemasukan (Penjualan ke Pengepul)</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($masuk, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pengeluaran</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">belanja Rp {{ number_format($keluarBeli, 0, ',', '.') }} + tarik Rp {{ number_format($keluarTarik, 0, ',', '.') }} + gaji Rp {{ number_format($keluarGaji, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Volume Sampah</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalKgBeli, 2, ',', '.') }} kg</p>
                        <p class="text-xs text-gray-400 mt-1">beli · jual {{ number_format($totalKgJual, 2, ',', '.') }} kg</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nasabah & Saldo</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalNasabah, 0, ',', '.') }} warga</p>
                        <p class="text-xs text-gray-400 mt-1">saldo Rp {{ number_format($totalSaldoTabungan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- GRAFIK TABLE 12 BULAN -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Kas per Bulan — {{ $tahun }}</h3>
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
                                @foreach($labels as $i => $label)
                                    <tr class="border-b">
                                        <td class="py-2 font-medium">{{ $label }}</td>
                                        <td class="py-2 text-right text-green-600">{{ number_format($grafikMasuk[$i], 0, ',', '.') }}</td>
                                        <td class="py-2 text-right text-red-500">{{ number_format($grafikKeluar[$i], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>