<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Volume Sampah</h2>
                        <p class="text-sm text-gray-500">Rekap pengangkutan & volume sampah</p>
                    </div>
                    <a href="{{ route('owner.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Armada</th>
                                <th class="px-6 py-3 text-left">Lokasi Angkut</th>
                                <th class="px-6 py-3 text-right">Volume (m³)</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengangkutan as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-xs text-gray-500">{{ $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $a->nama_armada ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $a->lokasi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($a->volume_m3 ?? 0, 1, ',', '.') }} m³</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $a->status_tugas ?? 'Selesai' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data pengangkutan.</td>
                                </tr>
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
