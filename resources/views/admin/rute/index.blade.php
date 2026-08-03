<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Tambah Rute -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Master Rute Baru</h3>
                <form action="{{ route('admin.rute.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Wilayah / Rute</label>
                        <input type="text" name="nama_rute" placeholder="Contoh: Zona Perumahan Permai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jadwal Hari Angkut</label>
                        <input type="text" name="hari_angkut" placeholder="Contoh: Senin, Rabu, Jumat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titik Koordinat Pusat</label>
                        <input type="text" name="titik_koordinat_pusat" placeholder="Contoh: -6.2088,106.8456" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 justify-center py-2.5">
                        {{ __('Simpan Rute') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- Tabel Informasi Rute -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Rute Operasional</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-600">Nama Rute</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Jadwal Penarikan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Titik Koordinat Pusat</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Jumlah Rumah Warga</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataRute as $rt)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm font-bold text-gray-800">{{ $rt->nama_rute }}</td>
                                <td class="p-3 text-sm text-gray-600">{{ $rt->hari_angkut }}</td>
                                <td class="p-3 text-sm text-gray-600">
                                    @if($rt->titik_koordinat_pusat)
                                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $rt->titik_koordinat_pusat }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="p-3 text-sm text-gray-600">{{ $rt->pelanggan_count }} Pelanggan</td>
                                <td class="p-3 text-sm">
                                    <a href="{{ route('admin.rute.peta', $rt->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded">
                                        🗺️ Lihat Peta Digital
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada rute terdaftar.</td>
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
