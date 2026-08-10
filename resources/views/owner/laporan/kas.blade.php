<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Arus Kas — POS Bank Sampah</h1>
                        <p class="text-sm text-gray-500 mt-1">Pemasukan &amp; pengeluaran kas bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('M Y') }}.</p>
                    </div>
                    <form action="{{ route('owner.laporan.kas') }}" method="GET" class="flex gap-2">
                        <input type="month" name="bulan" value="{{ $bulan }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg">Tampilkan</button>
                    </form>
                </div>

                <!-- KARTU KAS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pemasukan</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($masuk, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Belanja Warga</p>
                        <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($keluarBeli, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">penarikan Rp {{ number_format($keluarTarik, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-amber-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Gaji Petugas</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($keluarGaji, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sisa Kas</p>
                        <p class="text-2xl font-bold {{ $sisaKas >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">Rp {{ number_format($sisaKas, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- DAFTAR TRANSAKSI -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Transaksi — {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('F Y') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 border-b">
                                    <th class="py-2">Tanggal</th>
                                    <th class="py-2">Keterangan</th>
                                    <th class="py-2 text-center">Jenis</th>
                                    <th class="py-2 text-right">Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $t)
                                    <tr class="border-b">
                                        <td class="py-2 text-xs">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                        <td class="py-2">{{ $t->keterangan }}</td>
                                        <td class="py-2 text-center">
                                            @if($t->kategori == 'Masuk')
                                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Masuk</span>
                                            @else
                                                <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded">Keluar</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-right font-medium {{ $t->kategori == 'Masuk' ? 'text-green-600' : 'text-red-500' }}">
                                            {{ $t->kategori == 'Masuk' ? '+' : '-' }} {{ number_format($t->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-4 text-center text-gray-500">Tidak ada transaksi pada bulan ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>