<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stok Sampah</h1>
                    <p class="text-sm text-gray-500 mt-1">Sisa sampah terpilah di gudang: total setoran warga dikurangi penjualan ke pengepul ({{ now()->format('d M Y') }})</p>
                </div>

                <!-- Kartu Total Stok -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Stok Tersedia</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalStok, 2, ',', '.') }} kg</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $perJenis->where('stok_kg', '>', 0)->count() }} jenis sampah masih tersedia</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Masuk (Setoran)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($perJenis->sum('masuk_kg'), 2, ',', '.') }} kg</p>
                        <p class="text-xs text-gray-400 mt-1">Seluruh pembelian dari warga</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Keluar (Terjual)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($perJenis->sum('keluar_kg'), 2, ',', '.') }} kg</p>
                        <p class="text-xs text-gray-400 mt-1">Seluruh penjualan ke pengepul</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jenis Sampah Terdaftar</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $perJenis->count() }} Jenis</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $perKategori->count() }} kategori</p>
                    </div>
                </div>

                <!-- Ringkasan per Kategori -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan per Kategori</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Kategori</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Masuk (kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Keluar (kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Sisa Stok (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perKategori as $k)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-medium">{{ $k->kategori }}</td>
                                        <td class="p-3 text-right">{{ number_format($k->masuk_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">{{ number_format($k->keluar_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold text-green-600">{{ number_format($k->stok_kg, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada kategori sampah.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail per Jenis -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Stok per Jenis Sampah</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Jenis Sampah</th>
                                    <th class="p-3 font-semibold text-gray-600">Kategori</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Masuk (kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Keluar (kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Sisa Stok (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perJenis as $j)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-medium">{{ $j->jenis }}</td>
                                        <td class="p-3 text-xs text-gray-500">{{ $j->kategori }}</td>
                                        <td class="p-3 text-right">{{ number_format($j->masuk_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">{{ number_format($j->keluar_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold {{ $j->stok_kg > 0 ? 'text-green-600' : 'text-gray-400' }}">{{ number_format($j->stok_kg, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-4 text-center text-gray-500">Belum ada jenis sampah terdaftar.</td></tr>
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