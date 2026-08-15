<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Laporan Pembelian Sampah Warga</h1>
                        <p class="text-sm text-gray-500 mt-1">Rincian belanja operasional dari warga</p>
                    </div>
                    <form action="{{ route('owner.laporan.pembelian') }}" method="GET" class="flex gap-2">
                        <select name="warga_id" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            <option value="">Semua Warga</option>
                            @foreach($dataWarga as $warga)
                                <option value="{{ $warga->id }}" {{ $wargaId == $warga->id ? 'selected' : '' }}>{{ $warga->user->name ?? 'Warga' }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold rounded-lg">Filter</button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Nama Warga</th>
                                    <th class="p-3 font-semibold text-gray-600">Tanggal</th>
                                    <th class="p-3 font-semibold text-gray-600">Jenis Sampah</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Berat (Kg)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Harga/Kg (Rp)</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Total (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-medium">{{ $item->warga->user->name ?? 'Warga' }}</td>
                                        <td class="p-3 text-xs">{{ \Carbon\Carbon::parse($item->tanggal_setoran)->format('d/m/Y') }}</td>
                                        <td class="p-3">{{ $item->jenisSampah->nama_jenis ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                                        <td class="p-3 text-right">{{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-semibold">{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada pembelian.</td></tr>
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