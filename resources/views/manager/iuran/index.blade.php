<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-manager-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Panel Tombol Otomatisasi -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Otomatisasi Tagihan Bulanan</h3>
                    <p class="text-sm text-gray-500">Gunakan fitur ini untuk membuat tagihan iuran secara serentak bagi semua pelanggan aktif bulan ini.</p>
                </div>
                <form action="{{ route('manager.iuran.generate') }}" method="POST">
                    @csrf
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700">
                        {{ __('Generate Tagihan Bulan Ini') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- Tabel Monitoring Tunggakan & Pembayaran -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Tagihan & Status Pembayaran</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-600">Bulan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Pelanggan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Total Tagihan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataIuran as $iur)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm font-bold text-gray-700">{{ $iur->bulan_tagihan }}</td>
                                <td class="p-3 text-sm text-gray-900">
                                    {{ $iur->pelanggan->user->name }} <br>
                                    <span class="text-xs text-gray-400">No: {{ $iur->pelanggan->no_pelanggan }}</span>
                                </td>
                                <td class="p-3 text-sm text-gray-900 font-semibold">
                                    Rp {{ number_format($iur->jumlah_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $iur->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $iur->status_pembayaran }}
                                    </span>
                                    @if($iur->status_pembayaran == 'Lunas')
                                        <div class="text-xs text-gray-400 mt-1">Metode: {{ $iur->metode_pembayaran }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-sm">
                                    @if($iur->status_pembayaran == 'Belum Bayar')
                                        <form action="{{ route('manager.iuran.bayar', $iur->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pelunasan iuran?')">
                                            @csrf
                                            <select name="metode" class="text-xs border-gray-300 rounded-md shadow-sm mr-2 py-1 focus:ring-0">
                                                <option value="Tunai">Tunai</option>
                                                <option value="Non-Tunai">Non-Tunai</option>
                                            </select>
                                            <button type="submit" class="text-xs bg-green-500 text-white px-3 py-1.5 rounded hover:bg-green-600 transition-colors font-medium">
                                                Konfirmasi Lunas
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">Tercatat lunas pada {{ date('d/m/Y', strtotime($iur->tanggal_bayar)) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada catatan iuran. Tekan tombol "Generate Tagihan" di atas untuk memulai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        </main>
    </div>
    <x-manager-bottom-nav />
</x-app-layout>
