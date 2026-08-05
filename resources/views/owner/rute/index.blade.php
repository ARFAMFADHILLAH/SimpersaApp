<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabel Informasi Rute -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-1">Daftar Rute Operasional</h3>
                <p class="text-sm text-gray-500 mb-4">Monitoring rute (read-only). Penambahan rute dilakukan oleh admin.</p>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-600">Nama Rute</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Jadwal Penarikan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Jumlah Rumah Warga</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataRute as $rt)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm font-bold text-gray-800">{{ $rt->nama_rute }}</td>
                                <td class="p-3 text-sm text-gray-600">{{ $rt->hari_angkut }}</td>
                                <td class="p-3 text-sm text-gray-600">{{ $rt->warga_count }} Warga</td>
                                <td class="p-3 text-sm">
                                    <a href="{{ route('owner.rute.peta', $rt->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded">
                                        🗺️ Lihat Peta Digital
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-sm text-gray-500">Belum ada rute terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>
