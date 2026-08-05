<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <a href="{{ route('admin.operasional.index') }}" class="text-cyan-600 hover:underline text-sm">&larr; Kembali</a>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Rekap Volume Harian (Bulan Ini)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Volume (m&sup3;)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Berat (kg)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Pengangkutan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($rekapHarian as $rekap)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($rekap->tanggal)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ number_format($rekap->total_volume, 1) }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ number_format($rekap->total_berat, 0) }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $rekap->total_angkut }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ $rekapHarian->links() }}</div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Rekap Volume Mingguan (12 Minggu Terakhir)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Periode</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Volume (m&sup3;)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Berat (kg)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Pengangkutan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($rekapMingguan as $rekap)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($rekap->tanggal_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($rekap->tanggal_akhir)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ number_format($rekap->total_volume, 1) }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ number_format($rekap->total_berat, 0) }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $rekap->total_angkut }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Rekap Volume Bulanan (12 Bulan Terakhir)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Bulan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Volume (m&sup3;)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Berat (kg)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total Pengangkutan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($rekapBulanan as $rekap)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::create()->month($rekap->bulan)->format('F') }} {{ $rekap->tahun }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ number_format($rekap->total_volume, 1) }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ number_format($rekap->total_berat, 0) }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $rekap->total_angkut }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
