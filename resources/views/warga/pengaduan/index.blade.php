<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-warga-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-lg">{{ session('success') }}</div>
                @endif

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Pengaduan Saya</h3>
                    <a href="{{ route('warga.pengaduan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2 px-4 rounded-lg transition shadow-sm">+ Buat Pengaduan Baru</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tgl</th>
                                    <th class="px-6 py-3 text-left">Tipe Kendala</th>
                                    <th class="px-6 py-3 text-left">Lokasi</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($pengaduan as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3">{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-3 font-semibold">{{ $item->tipe_kendala }}</td>
                                        <td class="px-6 py-3 max-w-xs truncate">{{ $item->catatan_lokasi }}</td>
                                        <td class="px-6 py-3">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded
                                                {{ $item->status_respon == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $item->status_respon == 'Sedang Dikerjakan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $item->status_respon == 'Belum Dikerjakan' ? 'bg-red-100 text-red-700' : '' }}">
                                                {{ $item->status_respon }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 max-w-xs truncate text-gray-500">{{ $item->catatan_petugas ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada pengaduan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t">{{ $pengaduan->links() }}</div>
                </div>

            </div>
        </main>
    </div>
    <x-warga-bottom-nav />
</x-app-layout>
