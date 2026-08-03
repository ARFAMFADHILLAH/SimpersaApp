<x-app-layout>

    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-manager-sidebar />

        <!-- Main Content Wrapper -->

        <!-- Main Content Wrapper -->
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Ringkasan Status Armada --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Total Unit Armada</h4>
                        <p class="text-3xl font-bold text-gray-800">{{ $dataArmada->count() }} Unit</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Armada Siap / Aktif</h4>
                        <p class="text-3xl font-bold text-green-600">{{ $armadaAktif }} Unit</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Armada Rusak / Perbaikan</h4>
                        <p class="text-3xl font-bold text-red-600">{{ $armadaRusak }} Unit</p>
                    </div>
                </div>

                {{-- Tabel Data Armada --}}
                <div class="bg-white p-6 shadow-sm border border-gray-100 sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Kendaraan Armada</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kendaraan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plat Nomor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kondisi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dataArmada as $armada)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $armada->nama_armada ?? $armada->nama_kendaraan ?? $armada->merk ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-700">{{ $armada->plat_nomor ?? $armada->nomor_plat ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $armada->kapasitas ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ in_array(strtolower($armada->status_kondisi ?? ''), ['aktif', 'baik', 'siap', 'beroperasi']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $armada->status_kondisi ?? 'Belum Diatur' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data armada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Monitoring Rute --}}
                <div class="bg-white p-6 shadow-sm border border-gray-100 sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal & Rute Pengangkutan Sampah</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Rute</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari Angkut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titik Koordinat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dataRute as $rute)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $rute->nama_rute }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rute->hari_angkut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $rute->titik_koordinat_pusat ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $rute->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data rute pengangkutan.</td>
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