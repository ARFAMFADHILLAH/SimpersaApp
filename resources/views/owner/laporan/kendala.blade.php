<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Kendala Petugas</h2>
                        <p class="text-sm text-gray-500">Kendala yang dilaporkan petugas lapangan di lapangan</p>
                    </div>
                    <a href="{{ route('owner.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Petugas</th>
                                    <th class="px-6 py-3 text-left">Tipe Kendala</th>
                                    <th class="px-6 py-3 text-left">Deskripsi</th>
                                    <th class="px-6 py-3 text-left">Lokasi</th>
                                    <th class="px-6 py-3 text-left">Waktu Lapor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($kendala as $k)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium">{{ $k->nama_petugas }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">{{ $k->tipe_kendala }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 max-w-md">{{ $k->deskripsi }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $k->lokasi ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($k->created_at)->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada laporan kendala.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
