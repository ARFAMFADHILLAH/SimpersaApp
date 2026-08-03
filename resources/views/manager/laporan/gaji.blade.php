<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-manager-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Gaji Petugas</h2>
                        <p class="text-sm text-gray-500">Rekap penggajian & slip gaji</p>
                    </div>
                    <a href="{{ route('manager.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Periode</th>
                                <th class="px-6 py-3 text-left">Petugas</th>
                                <th class="px-6 py-3 text-right">Gaji Pokok</th>
                                <th class="px-6 py-3 text-right">Bonus</th>
                                <th class="px-6 py-3 text-right">Potongan</th>
                                <th class="px-6 py-3 text-right">Total Bersih</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($gaji as $g)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-xs">{{ $g->periode_gaji ?? '-' }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $g->nama_petugas }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($g->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($g->bonus ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($g->potongan ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($g->total_gaji_bersih ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $g->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $g->status_pembayaran ?? 'Belum Dibayar' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada data penggajian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-manager-bottom-nav />
</x-app-layout>
