<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pelanggan</h3>
                    <a href="{{ route('administrasi.pelanggan.create') }}" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 text-sm">+ Daftarkan Baru</a>
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg mb-4">{{ session('success') }}</div>
                @endif

                <div class="bg-white shadow rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No HP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rute</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pelanggan as $p)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-mono">{{ $p->no_pelanggan }}</td>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $p->user?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $p->no_hp ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $p->rute?->nama_rute ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('administrasi.pelanggan.show', $p->id) }}" class="text-cyan-600 hover:underline text-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $pelanggan->links() }}</div>
            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
