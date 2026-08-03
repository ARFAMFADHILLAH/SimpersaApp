<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Total Biaya</span>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-xl shadow-sm border border-blue-200">
                        <span class="text-xs text-blue-600">BBM</span>
                        <p class="text-xl font-bold text-blue-700">Rp {{ number_format($totalBbm, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-xl shadow-sm border border-amber-200">
                        <span class="text-xs text-amber-600">Servis Kendaraan</span>
                        <p class="text-xl font-bold text-amber-700">Rp {{ number_format($totalServis, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Lainnya</span>
                        <p class="text-xl font-bold text-gray-700">Rp {{ number_format($totalLainnya, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Input Pengeluaran Operasional Baru</h3>
                        <form method="GET" class="flex gap-2">
                            <select name="kategori" class="text-sm border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <option value="BBM" {{ $kategoriFilter == 'BBM' ? 'selected' : '' }}>BBM</option>
                                <option value="Servis Kendaraan" {{ $kategoriFilter == 'Servis Kendaraan' ? 'selected' : '' }}>Servis</option>
                                <option value="Pergantian Ban" {{ $kategoriFilter == 'Pergantian Ban' ? 'selected' : '' }}>Ganti Ban</option>
                                <option value="Lainnya" {{ $kategoriFilter == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <input type="month" name="bulan" value="{{ $bulanFilter }}" class="text-sm border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                        </form>
                    </div>

                    <form action="{{ route('bendahara.operasional.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="kategori_biaya" value="Kategori Biaya" />
                                <select id="kategori_biaya" name="kategori_biaya" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih</option>
                                    <option value="BBM">BBM</option>
                                    <option value="Servis Kendaraan">Servis Kendaraan</option>
                                    <option value="Pergantian Ban">Pergantian Ban</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="armada_id" value="Armada (Opsional)" />
                                <select id="armada_id" name="armada_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Tidak Terkait</option>
                                    @foreach($dataArmada as $armada)
                                        <option value="{{ $armada->id }}">{{ $armada->nama_kendaraan }} ({{ $armada->nomor_plat }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="tanggal_pengeluaran" value="Tanggal" />
                                <x-text-input id="tanggal_pengeluaran" name="tanggal_pengeluaran" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                            </div>
                            <div>
                                <x-input-label for="jumlah_biaya" value="Jumlah Biaya (Rp)" />
                                <x-text-input id="jumlah_biaya" name="jumlah_biaya" type="number" class="mt-1 block w-full" value="0" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="keterangan" value="Keterangan" />
                            <textarea id="keterangan" name="keterangan" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Detail pengeluaran..."></textarea>
                        </div>

                        <div>
                            <x-input-label for="bukti_foto" value="Bukti Foto (Opsional)" />
                            <input type="file" id="bukti_foto" name="bukti_foto" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>Simpan Pengeluaran</x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pengeluaran Operasional</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">No</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Kategori</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Armada</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Keterangan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Verifikasi</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-red-600">Jumlah</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataPengeluaran as $key => $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm text-gray-700">{{ $key + 1 }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ date('d/m/Y', strtotime($item->tanggal_pengeluaran)) }}</td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->kategori_biaya }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm text-gray-700">{{ $item->armada->nama_kendaraan ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-600 max-w-xs truncate">{{ $item->keterangan ?? '-' }}</td>
                                        <td class="p-3 text-sm">
                                            @if($item->status_verifikasi == 'Disetujui')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                            @elseif($item->status_verifikasi == 'Ditolak')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm font-bold text-red-600">Rp {{ number_format($item->jumlah_biaya, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm">
                                            <div class="flex gap-1">
                                                @if($item->status_verifikasi == 'Menunggu')
                                                    <form action="{{ route('bendahara.operasional.verifikasi', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status_verifikasi" value="Disetujui">
                                                        <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Setuju</button>
                                                    </form>
                                                    <form action="{{ route('bendahara.operasional.verifikasi', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status_verifikasi" value="Ditolak">
                                                        <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Tolak</button>
                                                    </form>
                                                @endif
                                                @if($item->bukti_foto)
                                                    <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                                       class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">Foto</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-4 text-center text-sm text-gray-500">Belum ada pengeluaran operasional.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>
