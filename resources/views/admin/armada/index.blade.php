<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

        <!-- Alert Notifikasi Sukses -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg flex items-center justify-between text-emerald-800 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Data Armada</h2>
            <a href="{{ route('admin.armada.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                + Tambah Armada
            </a>
        </div>

        <!-- Card Tabel Data Armada -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-[11px] tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3.5">Nama Kendaraan</th>
                            <th class="px-6 py-3.5">Plat Nomor</th>
                            <th class="px-6 py-3.5">Jenis</th>
                            <th class="px-6 py-3.5">Kapasitas</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                   <tbody class="divide-y divide-gray-200">
    @forelse ($armadas as $armada)
        <tr class="hover:bg-gray-50/80 transition">
            <td class="px-6 py-4 font-semibold text-gray-900">
                {{ $armada->nama_kendaraan }}
            </td>
            <td class="px-6 py-4 font-mono font-medium text-gray-700">
                <span class="px-2 py-1 bg-gray-100 rounded border border-gray-200 uppercase text-xs">
                    {{ $armada->nomor_plat }}
                </span>
            </td>
            <td class="px-6 py-4">
                {{ $armada->jenis_kendaraan }}
            </td>
            <td class="px-6 py-4">
                @if($armada->kapasitas_m3)
                    <span class="font-medium text-gray-700">{{ number_format($armada->kapasitas_m3, 1) }} m³</span>
                @else
                    <span class="text-gray-400">—</span>
                @endif
            </td>
            <td class="px-6 py-4">
                @if(strtolower($armada->status_kondisi) === 'aktif')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        ● Aktif
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                        ● {{ ucfirst($armada->status_kondisi) }}
                    </span>
                @endif
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('admin.armada.edit', $armada->id) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-md transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </a>
                    <form action="{{ route('admin.armada.destroy', $armada->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 hover:bg-gray-100 rounded-md transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada data armada.</td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($armadas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $armadas->links() }}
                </div>
            @endif
        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>