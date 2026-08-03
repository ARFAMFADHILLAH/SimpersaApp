<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rekap Gaji Bulan {{ $bulan }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 p-3 rounded-lg border">
                            <span class="text-xs text-gray-500">Total Gaji Pokok</span>
                            <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalGajiPokok, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                            <span class="text-xs text-green-600">Total Insentif</span>
                            <p class="text-lg font-bold text-green-700">Rp {{ number_format($totalInsentif, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <span class="text-xs text-red-600">Total Potongan</span>
                            <p class="text-lg font-bold text-red-700">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-200">
                            <span class="text-xs text-indigo-600">Total Yang Dibayarkan</span>
                            <p class="text-lg font-bold text-indigo-700">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">No</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Petugas</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Gaji Pokok</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Insentif</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Potongan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 bg-indigo-50 text-indigo-700">Bersih</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap as $key => $gaji)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm">{{ $key + 1 }}</td>
                                        <td class="p-3 text-sm font-medium">{{ $gaji->petugas->name ?? '-' }}</td>
                                        <td class="p-3 text-sm">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm text-green-700">Rp {{ number_format($gaji->insentif_lembur, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm text-red-600">Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-bold bg-indigo-50 text-indigo-700">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $gaji->status_pembayaran == 'Dibayar' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $gaji->status_pembayaran }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-sm text-gray-500">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-4">
                        <form action="{{ route('bendahara.laporan.cetak') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                                Cetak Rekap Gaji
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>
