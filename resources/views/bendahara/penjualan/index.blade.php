<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-bendahara-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Rekap Penjualan ke Pengepul</h1>
                        <p class="text-sm text-gray-500 mt-1">Cetak rekap dan catat transaksi penjualan sampel ke pengepul.</p>
                    </div>

                    <!-- Filter Tanggal & Pengepul -->
                    <form action="{{ route('bendahara.penjualan.index') }}" method="GET" class="flex gap-2">
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <input type="text" name="nama_pengepul" value="{{ $namaPengepul }}" placeholder="Cari pengepul..." class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg">Filter</button>
                    </form>
                </div>

                <!-- TOTAL KARTU -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Berat Terjual</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalKg, 2, ',', '.') }} kg</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Pemasukan</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalRupiah, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- RINCIAN -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rincian Penjualan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Tanggal</th>
                                    <th class="p-3 font-semibold text-gray-600">Jenis Sampah</th>
                                    <th class="p-3 font-semibold text-gray-600 text-center">Kategori</th>
                                    <th class="p-3 font-semibold text-gray-600">Pengepul</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Berat (Kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Harga/Kg (Rp)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Total (Rp)</th>
                                    <th class="p-3 font-semibold text-gray-600">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-xs">{{ \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y') }}</td>
                                        <td class="p-3 font-medium">{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                        <td class="p-3 text-center text-xs">{{ $item->kategoriSampah->nama_kategori ?? '-' }}</td>
                                        <td class="p-3">{{ $item->nama_pengepul ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->harga_jual_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold">{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        <td class="p-3 text-xs text-gray-500">{{ $item->catatan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="p-4 text-center text-gray-500">Belum ada penjualan pada tanggal ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FORM TAMBAH PENJUALAN (rekap manual) -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Catat Penjualan Baru</h3>
                    <form action="{{ route('bendahara.penjualan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <x-input-label for="jenis_sampah_id" value="Jenis Sampah *" />
                            <select id="jenis_sampah_id" name="jenis_sampah_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">-- Pilih --</option>
                                @foreach($dataKategori as $kategori)
                                    <optgroup label="{{ $kategori->nama_kategori }}">
                                        @foreach($kategori->jenisSampah as $jenis)
                                            <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }} (jual: Rp {{ number_format($jenis->tarif_jual_per_kg, 0, ',', '.') }})</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="nama_pengepul" value="Nama Pengepul" />
                            <x-text-input id="nama_pengepul" name="nama_pengepul" type="text" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_penjualan" value="Tanggal *" />
                            <x-text-input id="tanggal_penjualan" name="tanggal_penjualan" type="date" class="mt-1 block w-full" value="{{ old('tanggal_penjualan', now()->toDateString()) }}" required />
                        </div>
                        <div>
                            <x-input-label for="berat_kg" value="Berat (Kg) *" />
                            <x-text-input id="berat_kg" name="berat_kg" type="number" step="0.01" min="0.01" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="harga_jual_per_kg" value="Harga Jual per Kg (Rp) *" />
                            <x-text-input id="harga_jual_per_kg" name="harga_jual_per_kg" type="number" step="0.01" min="0" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="catatan" value="Catatan" />
                            <x-text-input id="catatan" name="catatan" type="text" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-3">
                            <x-primary-button type="submit">{{ __('Simpan Penjualan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>