<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <p class="text-sm font-medium text-gray-500">Volume Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalVolumeBulanIni, 1) }} m&sup3;</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <p class="text-sm font-medium text-gray-500">Pengangkutan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPengangkutanBulanIni }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <p class="text-sm font-medium text-gray-500">Pengangkutan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pengangkutanHariIni->count() }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('administrasi.operasional.rekap-volume') }}" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 text-sm">Rekap Volume Harian/Bulanan</a>
                    <a href="{{ route('administrasi.operasional.jadwal-rute') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">Jadwal Rute & Penugasan</a>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Pengangkutan Hari Ini</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Pelanggan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Armada</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Volume</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($pengangkutanHariIni as $angkut)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $angkut->pelanggan?->user?->name ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $angkut->armada?->nama_kendaraan ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $angkut->volume_m3 }} m&sup3;</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded
                                                {{ $angkut->status_tugas == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $angkut->status_tugas == 'Sedang dikerjakan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $angkut->status_tugas == 'Belum dikerjakan' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                {{ $angkut->status_tugas }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">Tidak ada pengangkutan hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Daftar Rute</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($rutes as $rute)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="font-medium text-gray-900">{{ $rute->nama_rute }}</p>
                                <p class="text-sm text-gray-500">{{ $rute->hari_angkut ?? '-' }} &middot; {{ $rute->pelanggan_count }} pelanggan</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada rute.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
