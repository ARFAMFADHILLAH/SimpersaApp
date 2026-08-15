<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Laporan Penggajian</h1>
                        <p class="text-sm text-gray-500 mt-1">Rekap penggajian petugas</p>
                    </div>
                    <form action="{{ route('owner.laporan.gaji') }}" method="GET" class="flex gap-2">
                        <input type="month" name="bulan" value="{{ $bulan }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold rounded-lg">Tampilkan</button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rekap Gaji — {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('F Y') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Petugas</th>
                                    <th class="p-3 font-semibold text-gray-600">Bulan Gaji</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Gaji Pokok</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Bonus / Insentif</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Total Penerimaan</th>
                                    <th class="p-3 font-semibold text-gray-600 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-medium">{{ $item->petugas->name ?? 'Petugas' }}</td>
                                        <td class="p-3 text-xs">{{ \Carbon\Carbon::createFromFormat('Y-m', $item->bulan_gaji)->format('F Y') }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->insentif_lembur ?? 0, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold">{{ number_format($item->total_gaji_bersih, 0, ',', '.') }}</td>
                                        <td class="p-3 text-center">
                                            @if($item->status_pembayaran == 'Dibayar')
                                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Dibayar</span>
                                            @else
                                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded">Belum Dibayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada data penggajian untuk bulan ini.</td></tr>
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