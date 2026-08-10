<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-petugas-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow overflow-hidden">

                    <!-- Kop Nota -->
                    <div class="p-6 border-b border-dashed">
                        <div class="flex items-center gap-3">
                            <img src="/logo-kisuci.png" alt="SIMPERSA" class="h-10 w-10 object-cover rounded-lg">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">SIMPERSA — Bank Sampah</h2>
                                <p class="text-xs text-gray-500">Koperasi Sistem Sampah Terintegrasi</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 font-semibold">NOTA PENIMBANGAN &amp; PEMBELIAN SAMPAH</p>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <p>No. Transaksi: <span class="font-semibold text-gray-900">ST-{{ str_pad($setoran->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                            <p class="text-right">Tanggal: {{ \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('d/m/Y') }}</p>
                            <p>Petugas: {{ auth()->user()->name }}</p>
                        </div>
                    </div>

                    <!-- Rincian -->
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Nama Warga</p>
                            <p class="text-sm font-semibold">{{ $setoran->warga->user->name ?? 'Warga' }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">No. Warga</p>
                            <p class="text-sm font-semibold">{{ $setoran->warga->no_warga }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Kategori</p>
                            <p class="text-sm font-semibold">{{ $setoran->jenisSampah->kategoriSampah->nama_kategori ?? '-' }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Jenis Sampah</p>
                            <p class="text-sm font-semibold">{{ $setoran->jenisSampah->nama_jenis ?? '-' }}</p>
                        </div>

                        <div class="border-t border-dashed pt-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">Berat / Volume</p>
                                <p class="text-sm font-semibold">{{ number_format($setoran->berat_kg, 2, ',', '.') }} kg</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">Harga per Kg</p>
                                <p class="text-sm font-semibold">Rp {{ number_format($setoran->harga_per_kg, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">Total Nilai Belanja</p>
                                <p class="text-lg font-bold text-green-600">Rp {{ number_format($setoran->total_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($setoran->keterangan)
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">Keterangan</p>
                                <p class="text-sm">{{ $setoran->keterangan }}</p>
                            </div>
                        @endif

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">
                            Nilai belanja di atas telah dikreditkan ke saldo tabungan warga.<br>
                            Saldo tabungan saat ini: <b>Rp {{ number_format($setoran->warga->saldo_tabungan, 0, ',', '.') }}</b>
                        </div>

                        <p class="text-center text-xs text-gray-400 pt-2">Simpan nota ini sebagai bukti setoran. Terima kasih atas partisipasi Anda!</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ route('petugas.pembelian.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">Kembali ke Form</a>
                    <button onclick="window.print()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">Cetak Nota</button>
                </div>
            </div>
        </main>
    </div>
    <x-petugas-bottom-nav />
</x-app-layout>