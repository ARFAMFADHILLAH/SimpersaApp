<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-manager-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Armada</h2>
                        <p class="text-sm text-gray-500">Data kendaraan & status operasional</p>
                    </div>
                    <a href="{{ route('manager.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama Kendaraan</th>
                                <th class="px-6 py-3 text-left">Nomor Plat</th>
                                <th class="px-6 py-3 text-left">Jenis</th>
                                <th class="px-6 py-3 text-right">Kapasitas (m³)</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($armada as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $a->nama_kendaraan }}</td>
                                    <td class="px-6 py-4 font-mono text-xs">{{ $a->nomor_plat }}</td>
                                    <td class="px-6 py-4">{{ $a->jenis_kendaraan }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($a->kapasitas_m3 ?? 0, 1, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $a->status_kondisi == 'aktif' ? 'bg-green-100 text-green-700' : ($a->status_kondisi == 'servis' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ $a->status_kondisi }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data armada.</td>
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
