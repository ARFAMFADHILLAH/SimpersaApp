<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Warga</h3>
                    <div class="bg-white shadow rounded-lg overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rute</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($warga as $p)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $p->no_warga }}</td>
                                        <td class="px-4 py-3 text-sm font-medium">{{ $p->user?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ Str::limit($p->alamat_lengkap, 40) }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $p->rute?->nama_rute ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.master.warga.edit', $p->id) }}" class="text-cyan-600 hover:underline text-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $warga->links() }}</div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data TPS</h3>
                    <div class="bg-white shadow rounded-lg overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tpsList as $t)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $t->nama_tps }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $t->kapasitas_maksimal_m3 }} m&sup3;</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.master.tps.edit', $t->id) }}" class="text-cyan-600 hover:underline text-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Armada</h3>
                    <div class="bg-white shadow rounded-lg overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kendaraan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($armada as $a)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $a->nama_kendaraan }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $a->nomor_plat }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded
                                                {{ $a->status_kondisi == 'aktif' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $a->status_kondisi == 'rusak' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $a->status_kondisi == 'servis' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                                {{ $a->status_kondisi }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.master.armada.edit', $a->id) }}" class="text-cyan-600 hover:underline text-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
