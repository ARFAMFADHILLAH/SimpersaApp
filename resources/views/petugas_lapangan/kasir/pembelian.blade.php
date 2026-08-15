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
                        <h1 class="text-2xl font-bold text-gray-900">Transaksi Pembelian Sampah</h1>
                        <p class="text-sm text-gray-500 mt-1">Timbang sampah warga, catat transaksi, dan cetak nota.</p>
                    </div>
                    <form action="{{ route('petugas.pembelian.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg">Tampilkan</button>
                    </form>
                </div>

                <!-- FORM PENIMBANGAN -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" x-data="formBeli()">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Penimbangan &amp; Kelola Saldo</h3>
                    <form action="{{ route('petugas.pembelian.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="warga_id" value="Pilih Warga / Nasabah *" />
                                <select id="warga_id" name="warga_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach($dataWarga as $warga)
                                        <option value="{{ $warga->id }}" {{ old('warga_id') == $warga->id ? 'selected' : '' }}>
                                            {{ $warga->user->name ?? 'Warga' }} ({{ $warga->no_warga }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="tanggal_setoran" value="Tanggal Setoran *" />
                                <x-text-input id="tanggal_setoran" name="tanggal_setoran" type="date" class="mt-1 block w-full" value="{{ old('tanggal_setoran', $tanggal) }}" required />
                            </div>
                        </div>

                        <!-- DAFTAR ITEM -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-700">Daftar Barang Sampah</h4>
                                <button type="button" @click="tambahItem()" class="inline-flex items-center gap-1 px-5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"></svg>
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
                                            <x-input-label value="Jenis Sampah & Harga Beli per Kg *" />
                                            <select :name="'items[' + index + '][jenis_sampah_id]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" x-model="item.jenisId" @change="pilihJenis(item)" required>
                                                <option value="">-- Pilih Kategori terlebih dahulu --</option>
                                                <template x-for="jenis in jenisSesuaiKategori(item.kategoriId)" :key="jenis.id">
                                                    <option :value="jenis.id" x-text="jenis.nama + ' — Rp ' + formatRupiah(jenis.harga) + '/kg'"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        <div>
                                            <x-input-label value="Berat Sampah (Kg) *" />
                                            <input type="number" step="0.01" min="0.01" required :name="'items[' + index + '][berat_kg]'" x-model.number="item.berat" @input="hitung(item)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />
                                        </div>
                                        <div>
                                            <x-input-label value="Harga per Kg (Rp)" />
                                            <input type="text" readonly x-model="formatRupiah(item.hargaPerKg)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" />
                                        </div>
                                        <div>
                                            <x-input-label value="Total Bayar (Rp)" />
                                            <input type="text" readonly x-model="formatRupiah(item.totalBayar)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-green-50 font-bold text-green-700" />
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
                            <x-input-label for="keterangan" value="Keterangan (Opsional)" />
                            <x-text-input id="keterangan" name="keterangan" type="text" class="mt-1 block w-full" value="{{ old('keterangan') }}" />
                            <p class="mt-1 text-xs text-gray-400">Total otomatis dikredit ke saldo tabungan warga. Nota akan dicetak setelah transaksi tersimpan.</p>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button>
                                Simpan &amp; Cetak Nota 
                            </x-primary-button>
                        </div>
                    </form>

                    @push('scripts')
                    <script>
                        function formBeli() {
                            return {
                                daftarJenis: @json($daftarJenis),
                                items: [{ kategoriId: '', jenisId: '', berat: 1, hargaPerKg: 0, totalBayar: 0 }],
                                jenisSesuaiKategori(kategoriId) {
                                    return this.daftarJenis.filter(j => j.kategori_id == kategoriId);
                                },
                                formatRupiah(n) { return new Intl.NumberFormat('id-ID').format(n || 0); },
                                pilihKategori(item) {
                                    item.jenisId = '';
                                    item.hargaPerKg = 0;
                                    this.hitung(item);
                                },
                                pilihJenis(item) {
                                    const j = this.daftarJenis.find(x => x.id == item.jenisId);
                                    item.hargaPerKg = j ? j.harga : 0;
                                    this.hitung(item);
                                },
                                hitung(item) {
                                    item.totalBayar = Math.round((item.berat || 0) * (item.hargaPerKg || 0));
                                },
                                tambahItem() {
                                    this.items.push({ kategoriId: '', jenisId: '', berat: 1, hargaPerKg: 0, totalBayar: 0 });
                                },
                                hapusItem(index) {
                                    if (this.items.length > 1) this.items.splice(index, 1);
                                },
                                subtotal() {
                                    return this.items.reduce((s, i) => s + (i.totalBayar || 0), 0);
                                }
                            }
                        }
                    </script>
                    @endpush
                </div>

                <!-- RIWAYAT HARI INI -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Transaksi — {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h3>
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
                            <p class="text-xs text-gray-500">Total Dibayar</p>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">No. Transaksi</th>
                                    <th class="p-3 font-semibold text-gray-600">Warga</th>
                                    <th class="p-3 font-semibold text-gray-600">Jenis</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Berat</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Harga/Kg</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Total</th>
                                    <th class="p-3 font-semibold text-gray-600 text-center">Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-xs text-gray-500">{{ $item->kode_transaksi ?? '-' }}</td>
                                        <td class="p-3">{{ $item->warga->user->name ?? 'Warga' }} <span class="text-xs text-gray-400">({{ $item->warga->no_warga }})</span></td>
                                        <td class="p-3">{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('petugas.pembelian.nota', $item->id) }}" class="text-xs font-semibold text-green-600 hover:text-green-800">Cetak Nota</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="p-4 text-center text-gray-500">Belum ada transaksi pada tanggal ini.</td></tr>
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