<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm text-sm" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Zonasi Rute Pengangkutan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar rute wilayah operasional penjemputan sampah.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold">No</th>
                                <th class="p-4 font-bold">Nama Rute</th>
                                <th class="p-4 font-bold">Hari Angkut</th>
                                <th class="p-4 font-bold">Jumlah Warga</th>
                                <th class="p-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($dataRute ?? [] as $index => $rute)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 text-xs text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $rute->nama_rute }}</td>
                                    <td class="p-4 text-xs text-gray-600">
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-semibold rounded-full text-xs">
                                            {{ $rute->hari_angkut }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-xs text-gray-600">
                                        {{ $rute->warga_count ?? 0 }} Titik
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('petugas.rute.detail', $rute->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            Detail Rute
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 text-sm">
                                        Belum ada data rute yang terdaftar.
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