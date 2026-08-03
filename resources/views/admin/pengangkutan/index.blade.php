<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabel Monitoring -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Log Monitoring Angkutan & Sampah</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Pelanggan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Petugas & Armada</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Hasil Sampah</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPengangkutan as $p)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm text-gray-700">{{ $p->tanggal_tugas }}</td>
                                <td class="p-3 text-sm text-gray-900 font-medium">
                                    {{ $p->pelanggan->user->name }} <br>
                                    <span class="text-xs text-gray-400">({{ $p->pelanggan->no_pelanggan }})</span>
                                </td>
                                <td class="p-3 text-sm text-gray-700">
                                    Ptg: {{ $p->petugas->name }} <br>
                                    <span class="text-xs text-indigo-500">{{ $p->armada->nama_kendaraan }}</span>
                                </td>
                                <td class="p-3 text-sm text-gray-700">
                                    Jenis: <span class="font-medium text-gray-900">{{ $p->jenisSampah->nama_jenis }}</span><br>
                                    Vol: {{ $p->volume_m3 }} m³ | Brt: {{ $p->berat_kg }} Kg
                                </td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $p->status_tugas == 'Selesai' ? 'bg-green-100 text-green-800' : ($p->status_tugas == 'Sedang dikerjakan' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $p->status_tugas }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada log aktivitas pengangkutan sampah.</td>
                            </tr>
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
