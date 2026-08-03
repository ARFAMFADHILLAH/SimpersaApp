<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('bendahara.laporan.index') }}" class="text-xs text-indigo-600 hover:underline">&larr; Kembali ke Laporan Keuangan</a>
                        <h2 class="text-xl font-bold text-gray-900 mt-1">Laporan Arus Kas ({{ $tahun }})</h2>
                        <p class="text-xs text-gray-500">Arus kas masuk, keluar, dan saldo berjalan per bulan</p>
                    </div>
                    <form method="GET" class="flex gap-2 items-center">
                        <input type="number" name="tahun" min="2020" max="{{ date('Y') + 1 }}" value="{{ $tahun }}" class="text-sm border-gray-300 rounded-md shadow-sm">
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">Filter</button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Arus Kas Tahunan</h3>
                    <div class="relative" style="height:300px;">
                        <canvas id="chartArusKas"></canvas>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rekap Arus Kas Bulanan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Bulan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Kas Masuk</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Kas Keluar</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Arus Kas Bersih</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Saldo Berjalan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($labels as $i => $label)
                                    @php
                                        $bersih = $arusMasuk[$i] - $arusKeluar[$i];
                                    @endphp
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm font-medium text-gray-900">{{ $label }} {{ $tahun }}</td>
                                        <td class="p-3 text-sm font-bold text-emerald-600">Rp {{ number_format($arusMasuk[$i], 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-bold text-rose-600">Rp {{ number_format($arusKeluar[$i], 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-bold {{ $bersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                            {{ $bersih >= 0 ? '+' : '-' }} Rp {{ number_format(abs($bersih), 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 text-sm font-bold text-gray-900">Rp {{ number_format($saldoAkhir[$i], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
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
        const labels = {!! json_encode($labels) !!};
        const masuk = {!! json_encode($arusMasuk) !!};
        const keluar = {!! json_encode($arusKeluar) !!};
        const saldo = {!! json_encode($saldoAkhir) !!};

        new Chart(document.getElementById('chartArusKas'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Kas Masuk', data: masuk, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', borderWidth: 2, fill: true, tension: 0.35 },
                    { label: 'Kas Keluar', data: keluar, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', borderWidth: 2, fill: true, tension: 0.35 },
                    { label: 'Saldo Berjalan', data: saldo, borderColor: '#6366f1', borderWidth: 3, borderDash: [6, 4], fill: false, tension: 0.35 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') }
                    }
                }
            }
        });
    </script>
</x-app-layout>