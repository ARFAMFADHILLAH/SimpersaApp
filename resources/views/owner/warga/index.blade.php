<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Monitoring Nasabah Warga</h1>
                        <p class="text-sm text-gray-500 mt-1">Data nasabah beserta aktivitas setoran &amp; saldo</p>
                    </div>
                    <form action="{{ route('owner.warga.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari nama / no warga..." class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold rounded-lg">Cari</button>
                    </form>
                </div>

                <!-- TOTALS -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Nasabah</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalNasabah, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Saldo Tabungan</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Sampah Terkumpul</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalBerat, 2, ',', '.') }} kg</p>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">No. Warga</th>
                                    <th class="p-3 font-semibold text-gray-600">Nama Warga</th>
                                    <th class="p-3 font-semibold text-gray-600">No. HP</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Jumlah Setoran</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Total Berat (Kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Saldo (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataWarga as $warga)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-xs text-gray-500">{{ $warga->no_warga }}</td>
                                        <td class="p-3 font-medium">{{ $warga->nama_warga }}</td>
                                        <td class="p-3 text-xs">{{ $warga->no_hp ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ number_format($warga->jumlah_setoran, 0, ',', '.') }}x</td>
                                        <td class="p-3 text-right">{{ number_format($warga->total_setoran, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold text-green-600">{{ number_format($warga->saldo, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data warga.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>