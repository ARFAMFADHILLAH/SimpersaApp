<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Tunggakan</h2>
                        <p class="text-sm text-gray-500">Monitoring warga yang menunggak iuran</p>
                    </div>
                    <a href="{{ route('owner.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Warga Menunggak</span>
                        <p class="text-lg font-bold text-red-600">{{ $jumlahWargaTunggak }} Warga</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Total Tunggakan</span>
                        <p class="text-lg font-bold text-red-700">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Akumulasi Denda</span>
                        <p class="text-lg font-bold text-amber-600">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">No Warga</th>
                                    <th class="px-6 py-3 text-left">Nama</th>
                                    <th class="px-6 py-3 text-left">Wilayah</th>
                                    <th class="px-6 py-3 text-center">Bulan Menunggak</th>
                                    <th class="px-6 py-3 text-left">Mulai Tunggakan</th>
                                    <th class="px-6 py-3 text-right">Total Tunggakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($tunggakan as $t)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-mono text-xs">{{ $t->no_warga }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $t->nama_warga }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $t->nama_wilayah ?? '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ $t->jumlah_bln }} bln</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $t->mulai_tunggakan }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-red-700">Rp {{ number_format($t->total_tunggakan, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada warga yang menunggak. Semua iuran lunas!</td>
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
