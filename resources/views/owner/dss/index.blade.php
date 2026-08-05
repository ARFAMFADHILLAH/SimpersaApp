<x-app-layout>

    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />

        <!-- Main Content Wrapper -->

        <!-- Main Content Wrapper -->
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Tabel Kriteria --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kriteria DSS</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kriteria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bobot</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($kriteria as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->kode_kriteria ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_kriteria ?? $item->nama ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->bobot ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->tipe ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data kriteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Evaluasi Wilayah --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Evaluasi Wilayah Pelayanan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Wilayah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Warga</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($evaluasiWilayah as $wilayah)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $wilayah->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $wilayah->nama_wilayah }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $wilayah->total_warga }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data wilayah.</td>
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