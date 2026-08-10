<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Saldo Tabungan Warga</h1>
                    <p class="text-sm text-gray-500 mt-1">Posisi tabungan seluruh nasabah (read-only).</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah Nasabah</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($dataWarga->count(), 0, ',', '.') }} warga</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Saldo Tabungan</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">No. Warga</th>
                                    <th class="p-3 font-semibold text-gray-600">Nama Warga</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Saldo Tabungan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataWarga as $warga)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-xs text-gray-500">{{ $warga->no_warga }}</td>
                                        <td class="p-3 font-medium">{{ $warga->user->name ?? 'Warga' }}</td>
                                        <td class="p-3 text-right font-semibold text-green-600">{{ number_format($warga->saldo_tabungan, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="p-4 text-center text-gray-500">Belum ada data warga.</td></tr>
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