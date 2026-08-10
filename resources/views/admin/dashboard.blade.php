<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Pusat — Bank Sampah</h1>
                    <p class="text-sm text-gray-500 mt-1">Ringkasan operasional konter penimbangan & keuangan POS ({{ now()->format('d M Y') }})</p>
                </div>

                <!-- Kartu Statistik Utama -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nasabah Warga</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalWarga, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ number_format($wargaAktif, 0, ',', '.') }} warga aktif</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo Tabungan Warga</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalSaldoTabungan, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total aset tabungan nasabah</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Master Sampah</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalKategori, 0, ',', '.') }} Kategori</p>
                        <p class="text-xs text-gray-400 mt-1">{{ number_format($totalJenis, 0, ',', '.') }} jenis &amp; tarif</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pengguna Sistem</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalPetugas, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">admin, bendahara &amp; petugas</p>
                    </div>
                </div>

                <!-- Kartu Transaksi Bulan Ini -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Belanja Sampah Warga (Bulan Ini)</p>
                        <p class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalBelanjaBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ number_format($totalSetoranBulanIni, 0, ',', '.') }} transaksi · {{ number_format($totalKgBulanIni, 2, ',', '.') }} kg</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-indigo-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Penjualan ke Pengepul (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPenjualanBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total pemasukan ritel</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-amber-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Gaji Dibayarkan (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($penggajianBulanIni, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Gaji pokok terdaftar: Rp {{ number_format($gajiPokok, 0, ',', '.') }} /bulan</p>
                    </div>
                </div>

                <!-- Grafik 12 Bulan -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik 12 Bulan: Belanja Warga vs Penjualan Pengepul</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2">Bulan</th>
                                    <th class="py-2 text-right">Belanja Warga (Rp)</th>
                                    <th class="py-2 text-right">Penjualan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grafikBulan as $i => $bulan)
                                    <tr class="border-b">
                                        <td class="py-2 font-medium">{{ $bulan }}</td>
                                        <td class="py-2 text-right">{{ number_format($grafikBelanja[$i], 0, ',', '.') }}</td>
                                        <td class="py-2 text-right">{{ number_format($grafikJual[$i], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Setoran & Penarikan menunggu -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Setoran Terbaru</h3>
                            <a href="{{ route('admin.jenis-sampah.index') }}" class="text-xs font-semibold text-green-600 hover:text-green-800">Master Jenis &raquo;</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 border-b">
                                        <th class="py-2">Warga</th><th class="py-2">Jenis</th><th class="py-2 text-right">Berat</th><th class="py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($setoranTerbaru as $s)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">{{ $s->warga->user->name ?? 'Warga' }}</td>
                                            <td class="py-2">{{ $s->jenisSampah->nama_jenis ?? '-' }}</td>
                                            <td class="py-2 text-right">{{ number_format($s->berat_kg, 2, ',', '.') }} kg</td>
                                            <td class="py-2 text-right">Rp {{ number_format($s->total_bayar, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-4 text-center text-gray-500">Belum ada setoran.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Penarikan Tabungan Berstatus Diproses</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-1">
                                <p class="text-xs text-gray-500">Jumlah penarikan menunggu</p>
                                <p class="text-xl font-bold text-amber-600 mt-1">{{ number_format($penarikanMenunggu, 0, ',', '.') }} transaksi</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500">Nominal menunggu</p>
                                <p class="text-xl font-bold text-amber-600 mt-1">Rp {{ number_format($nominalPenarikanMenunggu, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">Konfirmasi oleh Bendahara pada menu Tabungan &amp; Penarikan.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>