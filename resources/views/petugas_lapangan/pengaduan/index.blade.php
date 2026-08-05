<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Pengaduan & Disposisi Tugas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pengaduan dari masyarakat yang memerlukan penanganan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold">Tanggal</th>
                                <th class="p-4 font-bold">Pelapor</th>
                                <th class="p-4 font-bold">Kendala</th>
                                <th class="p-4 font-bold">Status</th>
                                <th class="p-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($dataPengaduan as $item)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 text-xs text-gray-500">
                                        {{ $item->created_at ? date('d/m/Y', strtotime($item->created_at)) : '-' }}
                                    </td>
                                    <td class="p-4 font-medium text-gray-900">
                                        {{ $item->warga->user->name ?? 'Warga' }}
                                    </td>
                                    <td class="p-4 text-xs text-gray-700">
                                        @if($item->tipe_kendala)
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full text-[10px] font-semibold">{{ $item->tipe_kendala }}</span>
                                        @endif
                                        @if($item->catatan_lokasi)
                                            <p class="mt-1 text-gray-400">{{ $item->catatan_lokasi }}</p>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs">
                                        <span class="px-2.5 py-1 font-semibold rounded-full
                                            {{ $item->status_respon == 'Selesai' ? 'bg-green-100 text-green-800' : ($item->status_respon == 'Sedang Dikerjakan' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $item->status_respon }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('petugas.pengaduan.show', $item->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            Detail & Tanggapi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 text-sm">
                                        Belum ada pengaduan yang perlu ditangani.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
    <x-petugas-bottom-nav />
</x-app-layout>
