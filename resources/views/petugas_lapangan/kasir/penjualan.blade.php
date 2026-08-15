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

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pencatatan Penjualan ke Pengepul</h1>
                        <p class="text-sm text-gray-500 mt-1">Catat hasil penjualan sampah terpilah ke pengepul untuk pembukuan kas.</p>
                    </div>
                    <form action="{{ route('petugas.penjualan.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg">Tampilkan</button>
                    </form>
                </div>

                <!-- FORM PENJUALAN -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" x-data="formJual()">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Pencatatan Penjualan</h3>
                    <form action="{{ route('petugas.penjualan.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="tanggal_penjualan" value="Tanggal Penjualan *" />
                                <x-text-input id="tanggal_penjualan" name="tanggal_penjualan" type="date" class="mt-1 block w-full" value="{{ old('tanggal_penjualan', $tanggal) }}" required />
                            </div>
                            <div>
                                <x-input-label for="nama_pengepul" value="Nama Pengepul / Pembeli (Opsional)" />
                                <x-text-input id="nama_pengepul" name="nama_pengepul" type="text" class="mt-1 block w-full" value="{{ old('nama_pengepul') }}" placeholder="Contoh: Pengepul Pak Budi" />
                            </div>
                        </div>

                        <!-- DAFTAR ITEM -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-700">Daftar Barang Sampah</h4>
                                <button type="button" @click="tambahItem()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                    + Tambah Barang
                                </button>
                            </div>

                            <template x-for="(item, index) in items" :key="index">
                                <div class="border border-gray-200 rounded-lg p-4 mb-3 bg-gray-50/50">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Barang #<span x-text="index + 1"></span></p>
                                        <button type="button" @click="hapusItem(index)" x-show="items.length > 1" class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label value="Kategori Sampah *" />
                                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" x-model="item.kategoriId" @change="pilihKategori(item)">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($dataKategori as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label value="Jenis Sampah *" />
                                            <select :name="'items[' + index + '][jenis_sampah_id]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" x-model="item.jenisId" @change="pilihJenis(item)" required>
                                                <option value="">-- Pilih Kategori terlebih dahulu --</option>
                                                <template x-for="jenis in jenisSesuaiKategori(item.kategoriId)" :key="jenis.id">
                                                    <option :value="jenis.id" x-text="jenis.nama + ' — stok ' + stokLabel(jenis.id) + ' — patokan jual Rp ' + formatRupiah(jenis.harga) + '/kg'"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        <div>
                                            <x-input-label value="Berat (Kg) *" />
                                            <input type="number" step="0.01" min="0.01" required :name="'items[' + index + '][berat_kg]'" x-model.number="item.berat" @change="hitung(item)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />
                                        </div>
                                        <div>
                                            <x-input-label value="Harga Jual per Kg (Rp) *" />
                                            <input type="number" step="0.01" min="0" required :name="'items[' + index + '][harga_jual_per_kg]'" x-model.number="item.harga" @change="hitung(item)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />
                                        </div>
                                        <div>
                                            <x-input-label value="Total (Rp)" />
                                            <input type="text" readonly x-model="formatRupiah(item.totalHarga)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-green-50 font-bold text-green-700" />
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- SUBTOTAL -->
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center justify-between">
                                <p class="text-sm font-semibold text-emerald-800">Subtotal (<span x-text="items.length"></span> barang)</p>
                                <p class="text-xl font-bold text-emerald-700">Rp <span x-text="formatRupiah(subtotal())"></span></p>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="catatan" value="Catatan (Opsional)" />
                            <x-text-input id="catatan" name="catatan" type="text" class="mt-1 block w-full" value="{{ old('catatan') }}" />
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button type="submit">
                                {{ __('Simpan Pencatatan') }} <span class="ml-1 text-xs opacity-80">(<span x-text="items.length"></span> item)</span>
                            </x-primary-button>
                        </div>
                    </form>

                    @push('scripts')
                    <script>
                        function formJual() {
                            return {
                                daftarJenis: @json($daftarJenis),
                                stokPerJenis: @json($stokPerJenis),
                                items: [{ kategoriId: '', jenisId: '', berat: 0, harga: 0, totalHarga: 0 }],
                                jenisSesuaiKategori(kategoriId) {
                                    return this.daftarJenis.filter(j => j.kategori_id == kategoriId);
                                },
                                stokLabel(id) {
                                    const kg = this.stokPerJenis[id] ?? 0;
                                    return kg + ' kg';
                                },
                                formatRupiah(n) { return new Intl.NumberFormat('id-ID').format(n || 0); },
                                pilihKategori(item) {
                                    item.jenisId = '';
                                    item.totalHarga = 0;
                                },
                                pilihJenis(item) {
                                    const j = this.daftarJenis.find(x => x.id == item.jenisId);
                                    if (j && !item.harga) item.harga = j.harga;
                                    this.hitung(item);
                                },
                                hitung(item) {
                                    item.totalHarga = Math.round((item.berat || 0) * (item.harga || 0));
                                },
                                tambahItem() {
                                    this.items.push({ kategoriId: '', jenisId: '', berat: 0, harga: 0, totalHarga: 0 });
                                },
                                hapusItem(index) {
                                    if (this.items.length > 1) this.items.splice(index, 1);
                                },
                                subtotal() {
                                    return this.items.reduce((s, i) => s + (i.totalHarga || 0), 0);
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
                                    <th class="p-3 font-semibold text-gray-600">No. Transaksi</th>
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
                                        <td class="p-3 text-xs text-gray-500">{{ $item->kode_transaksi ?? '-' }}</td>
                                        <td class="p-3">{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                        <td class="p-3">{{ $item->kategoriSampah->nama_kategori ?? '-' }}</td>
                                        <td class="p-3">{{ $item->nama_pengepul ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">Rp {{ number_format($item->harga_jual_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="p-4 text-center text-gray-500">Belum ada penjualan pada tanggal ini.</td></tr>
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