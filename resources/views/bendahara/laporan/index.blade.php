<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Laporan Keuangan & Laba/Rugi</h2>
                    <div class="flex gap-2 items-center">
                        <a href="{{ route('bendahara.laporan.neraca') }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Neraca Kas</a>
                        <a href="{{ route('bendahara.laporan.arus-kas') }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md text-sm hover:bg-indigo-100">Arus Kas</a>
                        <form method="GET" class="flex gap-2 items-center">
                            <input type="month" name="bulan" value="{{ $bulan }}" class="text-sm border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                            <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">Filter</button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Pemasukan</span>
                        <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Beban Gaji</span>
                        <p class="text-lg font-bold text-rose-600">Rp {{ number_format($pengeluaranGaji, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Beban Operasional</span>
                        <p class="text-lg font-bold text-rose-500">Rp {{ number_format($pengeluaranOperasional, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Total Beban</span>
                        <p class="text-lg font-bold text-rose-700">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="{{ $labaRugi >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} p-4 rounded-xl shadow-sm border">
                        <span class="text-xs {{ $labaRugi >= 0 ? 'text-emerald-600' : 'text-red-600' }}">LABA / RUGI</span>
                        <p class="text-lg font-bold {{ $labaRugi >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                            Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
                            <span class="text-xs">{{ $labaRugi >= 0 ? '(Surplus)' : '(Defisit)' }}</span>
                        </p>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Pendapatan & Pengeluaran Tahunan</h3>
                    <div class="relative" style="height:300px;">
                        <canvas id="chartKeuangan"></canvas>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Riwayat Transaksi</h3>
                        <form action="{{ route('bendahara.laporan.cetak') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                                Cetak Laporan
                            </button>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Keterangan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Kategori</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatTransaksi as $trx)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm text-gray-700">{{ $trx->tanggal }}</td>
                                        <td class="p-3 text-sm text-gray-900">{{ $trx->keterangan }}</td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $trx->kategori == 'Pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $trx->kategori }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm font-bold {{ $trx->kategori == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $trx->kategori == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center text-sm text-gray-500">Belum ada transaksi.</td>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartKeuangan').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dataGrafik['labels']) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: {!! json_encode($dataGrafik['pemasukan']) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($dataGrafik['pengeluaran']) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
