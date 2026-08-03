<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Logistik Armada</h3>
                    <a href="{{ route('administrasi.logistik.create') }}" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 text-sm">+ Catat Pengeluaran</a>
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg mb-4">{{ session('success') }}</div>
                @endif

                <div class="bg-white shadow rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Armada</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengeluaran as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $item->armada?->nama_kendaraan ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-700">{{ $item->kategori_biaya }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium">Rp {{ number_format($item->jumlah_biaya, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded
                                            {{ $item->status_verifikasi == 'Disetujui' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $item->status_verifikasi == 'Ditolak' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $item->status_verifikasi == 'Menunggu' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                            {{ $item->status_verifikasi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">{{ $item->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-3 text-sm text-gray-500 text-center">Belum ada pengeluaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $pengeluaran->links() }}</div>
            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
