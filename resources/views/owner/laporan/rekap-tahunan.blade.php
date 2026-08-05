<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Rekap Tahunan {{ $tahun }}</h2>
                        <p class="text-sm text-gray-500">Pendapatan, pengeluaran, laba, dan volume sampah per bulan</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" class="flex items-center gap-2">
                            <select name="tahun" class="text-sm border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                                @foreach($daftarTahun as $th)
                                    <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>{{ $th }}</option>
                                @endforeach
                                <option value="{{ date('Y') }}" {{ $daftarTahun->isEmpty() ? 'selected' : '' }}>{{ date('Y') }}</option>
                            </select>
                        </form>
                        <a href="{{ route('owner.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Total Pendapatan</span>
                        <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <span class="text-xs text-gray-500">Total Pengeluaran</span>
                        <p class="text-lg font-bold text-rose-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="{{ $totalLaba >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} p-4 rounded-xl shadow-sm border">
                        <span class="text-xs {{ $totalLaba >= 0 ? 'text-emerald-600' : 'text-red-600' }}">LABA TAHUNAN</span>
                        <p class="text-lg font-bold {{ $totalLaba >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                            Rp {{ number_format(abs($totalLaba), 0, ',', '.') }}
                            <span class="text-xs">{{ $totalLaba >= 0 ? '(Surplus)' : '(Defisit)' }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Bulan</th>
                                    <th class="px-6 py-3 text-right">Pendapatan Iuran</th>
                                    <th class="px-6 py-3 text-right">Gaji</th>
                                    <th class="px-6 py-3 text-right">Operasional</th>
                                    <th class="px-6 py-3 text-right">Total Pengeluaran</th>
                                    <th class="px-6 py-3 text-right">Laba / Rugi</th>
                                    <th class="px-6 py-3 text-right">Volume (m&sup3;)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($rekap as $r)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $r['bulan'] }}</td>
                                        <td class="px-6 py-4 text-right text-emerald-600 font-semibold">Rp {{ number_format($r['pendapatan'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right text-rose-500">Rp {{ number_format($r['gaji'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right text-rose-500">Rp {{ number_format($r['operasional'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right text-rose-700 font-semibold">Rp {{ number_format($r['pengeluaran'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right font-bold {{ $r['laba'] >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                            {{ $r['laba'] >= 0 ? '+' : '-' }} Rp {{ number_format(abs($r['laba']), 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-gray-700">{{ number_format($r['volume'], 1) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>
