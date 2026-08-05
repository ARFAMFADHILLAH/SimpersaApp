<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-warga-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <a href="{{ route('warga.profile') }}" class="text-xs text-indigo-600 hover:underline">&larr; Kembali ke Profil</a>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h4 class="font-bold text-gray-800 text-sm">Riwayat Pengangkutan Sampah</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tanggal</th>
                                    <th class="px-6 py-3 text-left">Armada</th>
                                    <th class="px-6 py-3 text-left">Jenis Sampah</th>
                                    <th class="px-6 py-3 text-left">Volume</th>
                                    <th class="px-6 py-3 text-left">Berat</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($riwayatPengangkutan as $angkut)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3">{{ \Carbon\Carbon::parse($angkut->tanggal_tugas)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-3">{{ $angkut->armada?->nama_kendaraan ?? '-' }}</td>
                                        <td class="px-6 py-3">{{ $angkut->jenisSampah?->nama_jenis ?? '-' }}</td>
                                        <td class="px-6 py-3">{{ $angkut->volume_m3 }} m&sup3;</td>
                                        <td class="px-6 py-3">{{ $angkut->berat_kg }} kg</td>
                                        <td class="px-6 py-3 font-bold {{ $angkut->status_tugas == 'Selesai' ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $angkut->status_tugas }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat pengangkutan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t">{{ $riwayatPengangkutan->links() }}</div>
                </div>
            </div>
        </main>
    </div>
    <x-warga-bottom-nav />
</x-app-layout>
