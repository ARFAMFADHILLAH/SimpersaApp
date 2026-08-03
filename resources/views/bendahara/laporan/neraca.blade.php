<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('bendahara.laporan.index') }}" class="text-xs text-indigo-600 hover:underline">&larr; Kembali ke Laporan Keuangan</a>
                        <h2 class="text-xl font-bold text-gray-900 mt-1">Neraca Kas</h2>
                        <p class="text-xs text-gray-500">Posisi kas (saldo) hingga periode terpilih</p>
                    </div>
                    <form method="GET" class="flex gap-2 items-center">
                        <input type="month" name="bulan" value="{{ $bulan }}" class="text-sm border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">Filter</button>
                    </form>
                </div>

                <!-- Ringkasan Neraca -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Saldo Awal (Kumulatif)</span>
                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Kas Masuk ({{ $bulan }})</span>
                        <p class="text-lg font-bold text-emerald-600">+ Rp {{ number_format($masukBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Kas Keluar ({{ $bulan }})</span>
                        <p class="text-lg font-bold text-rose-600">- Rp {{ number_format($keluarBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="{{ $saldoAkhir >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} p-4 rounded-xl shadow-sm border">
                        <span class="text-xs {{ $saldoAkhir >= 0 ? 'text-emerald-600' : 'text-red-600' }}">SALDO AKHIR</span>
                        <p class="text-lg font-bold {{ $saldoAkhir >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Detail Posisi Kas -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Posisi Kas - {{ $bulan }}</h3>
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <tr class="border-b bg-gray-50">
                                <td colspan="2" class="p-3 text-sm font-bold text-gray-700">A. KAS MASUK</td>
                                <td class="p-3 text-sm font-bold text-right text-gray-700">Rp {{ number_format($masukSampai, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="p-3 pl-8 text-sm text-gray-700">Penerimaan Iuran (kumulatif s.d. periode)</td>
                                <td class="p-3 text-sm text-right text-gray-700">Rp {{ number_format($masukSampai, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                            <tr class="border-b">
                                <td class="p-3 pl-8 text-sm text-gray-700">Penerimaan Iuran Periode Berjalan</td>
                                <td class="p-3 text-sm text-right text-emerald-600 font-bold">Rp {{ number_format($masukBulanIni, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>

                            <tr class="border-b bg-gray-50">
                                <td colspan="2" class="p-3 text-sm font-bold text-gray-700">B. KAS KELUAR</td>
                                <td class="p-3 text-sm font-bold text-right text-gray-700">Rp {{ number_format($keluarSampai, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="p-3 pl-8 text-sm text-gray-700">Pembayaran Gaji Petugas (kumulatif)</td>
                                <td class="p-3 text-sm text-right text-gray-700">Rp {{ number_format($keluarSampai, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                            <tr class="border-b">
                                <td class="p-3 pl-8 text-sm text-gray-700">Gaji Petugas Periode Berjalan</td>
                                <td class="p-3 text-sm text-right text-rose-600 font-bold">Rp {{ number_format($keluarGaji, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                            <tr class="border-b">
                                <td class="p-3 pl-8 text-sm text-gray-700">Pengeluaran Operasional Periode Berjalan (BBM/Servis/Alat)</td>
                                <td class="p-3 text-sm text-right text-rose-600 font-bold">Rp {{ number_format($keluarOperasional, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>

                            <tr class="bg-gray-100">
                                <td class="p-3 text-sm font-extrabold text-gray-900" colspan="2">C. SALDO KAS</td>
                                <td class="p-3 text-sm font-extrabold text-right {{ $saldoAkhir >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>
