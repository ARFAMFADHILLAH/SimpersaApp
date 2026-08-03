<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Monitoring Pengaduan Masyarakat</h3>

                <div class="bg-white shadow rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Masuk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Kendala</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengaduan as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $item->pelanggan?->user?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $item->tipe_kendala }}</td>
                                    <td class="px-4 py-3 text-sm max-w-xs truncate">{{ $item->catatan_lokasi ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded
                                            {{ $item->status_respon == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $item->status_respon == 'Sedang Dikerjakan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $item->status_respon == 'Belum Dikerjakan' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ $item->status_respon }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('administrasi.pengaduan.show', $item->id) }}" class="text-cyan-600 hover:underline text-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-3 text-sm text-gray-500 text-center">Belum ada pengaduan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $pengaduan->links() }}</div>
            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
