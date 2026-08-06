<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-warga-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Bank Sampah — Riwayat Setoran Saya</h1>
                    <p class="text-sm text-gray-500 mt-1">Setoran sampah Anda dibeli mitra dan dibayar tunai langsung.</p>
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
                @endif

                <!-- Ringkas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-emerald-600 text-white p-5 rounded-xl shadow">
                        <p class="text-sm font-medium opacity-80">Total Sampah Disetor</p>
                        <p class="text-3xl font-bold">{{ number_format($totalKg, 2, ',', '.') }} kg</p>
                    </div>
                    <div class="bg-orange-600 text-white p-5 rounded-xl shadow">
                        <p class="text-sm font-medium opacity-80">Total Uang Diterima (Tunai)</p>
                        <p class="text-3xl font-bold">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Riwayat -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Setoran</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Jenis Sampah</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Berat</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Harga/Kg</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Total Diterima</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Dibeli Mitra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatSetoran as $s)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm text-gray-700">{{ $s->tanggal_setoran->format('d M Y') }}</td>
                                        <td class="p-3 text-sm text-gray-900 font-medium">{{ $s->jenisSampah?->nama_jenis ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ number_format($s->berat_kg, 2, ',', '.') }} kg</td>
                                        <td class="p-3 text-sm text-gray-700">Rp {{ number_format($s->harga_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-semibold text-emerald-700">Rp {{ number_format($s->total_bayar, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $s->mitra?->nama_mitra ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-sm text-gray-400">Belum ada setoran tercatat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $riwayatSetoran->links() }}</div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>