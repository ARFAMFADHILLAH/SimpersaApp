<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-petugas-sidebar />
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

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pencatatan Penjualan ke Pengepul</h1>
                    <p class="text-sm text-gray-500 mt-1">Catat hasil penjualan sampah terpilah ke pengepul untuk pembukuan kas.</p>
                </div>

                <!-- FORM PENJUALAN -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" x-data="formJual()">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Pencatatan Penjualan</h3>
                    <form action="{{ route('petugas.penjualan.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="kategori_id" value="Kategori Sampah *" />
                                <select id="kategori_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" x-model="kategoriId" @change="pilihKategori()">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($dataKategori as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="tanggal_penjualan" value="Tanggal Penjualan *" />
                                <x-text-input id="tanggal_penjualan" name="tanggal_penjualan" type="date" class="mt-1 block w-full" value="{{ old('tanggal_penjualan', $tanggal) }}" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="jenis_sampah_id" value="Jenis Sampah *" />
                            <select id="jenis_sampah_id" name="jenis_sampah_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" x-model="jenisId" @change="pilihJenis()" required>
                                <option value="">-- Pilih Kategori terlebih dahulu --</option>
                                <template x-for="jenis in daftarJenis" :key="jenis.id">
                                    <option :value="jenis.id" x-text="jenis.nama + ' — patokan jual Rp ' + formatRupiah(jenis.harga) + '/kg'"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="nama_pengepul" value="Nama Pengepul / Pembeli (Opsional)" />
                            <x-text-input id="nama_pengepul" name="nama_pengepul" type="text" class="mt-1 block w-full" value="{{ old('nama_pengepul') }}" placeholder="Contoh: Pengepul Pak Budi" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="berat_kg" value="Berat (Kg) *" />
                                <x-text-input id="berat_kg" name="berat_kg" type="number" step="0.01" min="0.01" x-model.number="berat" @change="hitung()" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="harga_jual_per_kg" value="Harga Jual per Kg (Rp) *" />
                                <x-text-input id="harga_jual_per_kg" name="harga_jual_per_kg" type="number" step="0.01" min="0" x-model.number="harga" @change="hitung()" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="total_harga" value="Total (Rp)" />
                                <x-text-input id="total_harga" type="text" x-model="totalHarga" disabled class="mt-1 block w-full bg-green-50 font-bold text-green-700" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="catatan" value="Catatan (Opsional)" />
                            <x-text-input id="catatan" name="catatan" type="text" class="mt-1 block w-full" value="{{ old('catatan') }}" />
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button type="submit">{{ __('Simpan Pencatatan') }}</x-primary-button>
                        </div>
                    </form>

                    @push('scripts')
                    <script>
                        function formJual() {
                            return {
                                kategoriId: '',
                                jenisId: '',
                                berat: 0,
                                harga: 0,
                                totalHarga: 0,
                                daftarJenis: @json($daftarJenis),
                                formatRupiah(n) { return new Intl.NumberFormat('id-ID').format(n); },
                                pilihKategori() {
                                    this.jenisId = '';
                                    this.hitung();
                                },
                                pilihJenis() {
                                    const j = this.daftarJenis.find(x => x.id == this.jenisId);
                                    if (j && !this.harga) this.harga = j.harga;
                                    this.hitung();
                                },
                                hitung() {
                                    this.totalHarga = Math.round((this.berat || 0) * this.harga || 0);
                                }
                            }
                        }
                    </script>
                    @endpush
                </div>

                <!-- RIWAYAT HARI INI -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Penjualan — {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Transaksi</p>
                            <p class="text-lg font-bold text-gray-900">{{ $riwayat->count() }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Total Berat</p>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($totalBeratHariIni, 2, ',', '.') }} kg</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Total Pemasukan</p>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Jenis</th>
                                    <th class="p-3 font-semibold text-gray-600">Kategori</th>
                                    <th class="p-3 font-semibold text-gray-600">Pengepul</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Berat</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Harga/Kg</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                        <td class="p-3">{{ $item->kategoriSampah->nama_kategori ?? '-' }}</td>
                                        <td class="p-3">{{ $item->nama_pengepul ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">Rp {{ number_format($item->harga_jual_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada penjualan pada tanggal ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-petugas-bottom-nav />
</x-app-layout>