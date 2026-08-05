<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Petugas</h2>
                        <p class="text-sm text-gray-500">Kinerja & beban kerja petugas lapangan</p>
                    </div>
                    <a href="{{ route('owner.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Nama Petugas</th>
                                    <th class="px-6 py-3 text-left">Role</th>
                                    <th class="px-6 py-3 text-center">Total Tugas</th>
                                    <th class="px-6 py-3 text-center">Selesai</th>
                                    <th class="px-6 py-3 text-center">Proses</th>
                                    <th class="px-6 py-3 text-center">Belum</th>
                                    <th class="px-6 py-3 text-center">Persentase</th>
                                    <th class="px-6 py-3 text-left">Status Akun</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($petugas as $p)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium">{{ $p->name }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $p->nama_role }}</td>
                                        <td class="px-6 py-4 text-center font-bold">{{ $p->total_tugas }}</td>
                                        <td class="px-6 py-4 text-center text-green-600 font-semibold">{{ $p->tugas_selesai }}</td>
                                        <td class="px-6 py-4 text-center text-amber-600 font-semibold">{{ $p->tugas_proses }}</td>
                                        <td class="px-6 py-4 text-center text-gray-500">{{ $p->tugas_belum }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-20 bg-gray-100 rounded-full h-2 overflow-hidden">
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $p->persentase }}%"></div>
                                                </div>
                                                <span class="text-xs font-bold">{{ $p->persentase }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $p->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $p->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada data petugas.</td>
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
