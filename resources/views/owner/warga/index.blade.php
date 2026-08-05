<x-app-layout>

    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />

        <!-- Main Content Wrapper -->

        <!-- Main Content Wrapper -->
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Tabel Data Warga -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Warga Terregistrasi</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">No Warga</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Nama</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Email</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">No HP</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dataWarga as $plg)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="p-3 text-sm text-indigo-600 font-bold">{{ $plg->no_warga }}</td>
                                        <td class="p-3 text-sm text-gray-900 font-medium">{{ $plg->user->name ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $plg->user->email ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $plg->no_hp ?? '-' }}</td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($plg->user->status ?? '') == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($plg->user->status ?? 'nonaktif') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada data warga yang terdaftar.</td>
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