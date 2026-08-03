<x-app-layout>

    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-manager-sidebar />

        <!-- Main Content Wrapper -->

        <!-- Main Content Wrapper -->
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Ringkasan Keuangan Bulan Ini --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Total Pemasukan (Iuran)</h4>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Total Pengeluaran</h4>
                        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Total Gaji Petugas</h4>
                        <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border-b-4 {{ $saldoNetoBulanIni >= 0 ? 'border-green-500' : 'border-red-500' }}">
                        <h4 class="text-sm text-gray-500 font-semibold mb-1">Saldo Arus Kas Bersih</h4>
                        <p class="text-2xl font-bold {{ $saldoNetoBulanIni >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($saldoNetoBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Tabel Pemasukan Iuran --}}
                <div class="bg-white p-6 shadow-sm border border-gray-100 sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pemasukan Iuran Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pemasukanIuran as $iuran)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($iuran->created_at)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $iuran->nama_pelanggan ?? 'NN' }} <span class="text-xs text-gray-500 font-normal">({{ $iuran->no_pelanggan ?? '-' }})</span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $iuran->status_pembayaran ?? 'Belum Bayar' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pemasukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pemasukanIuran->appends(['pengeluaran_page' => request('pengeluaran_page'), 'gaji_page' => request('gaji_page')])->links() }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tabel Pengeluaran Operasional --}}
                    <div class="bg-white p-6 shadow-sm border border-gray-100 sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengeluaran Operasional</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Biaya</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($pengeluaranOperasional as $pengeluaran)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($pengeluaran->created_at)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-800">{{ $pengeluaran->keterangan ?? $pengeluaran->nama_pengeluaran ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">Rp {{ number_format($pengeluaran->biaya ?? $pengeluaran->jumlah ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pengeluaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabel Penggajian --}}
                    <div class="bg-white p-6 shadow-sm border border-gray-100 sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Penggajian</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Gaji</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($dataPenggajian as $gaji)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($gaji->created_at)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $gaji->nama_petugas ?? 'NN' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">Rp {{ number_format($gaji->total_gaji ?? $gaji->total_gaji_bersih ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data penggajian.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-manager-bottom-nav />
</x-app-layout>