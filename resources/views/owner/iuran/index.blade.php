<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabel Monitoring Tunggakan & Pembayaran -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-1">Daftar Tagihan & Status Pembayaran</h3>
                <p class="text-sm text-gray-500 mb-4">Monitoring iuran warga (read-only). Pengelolaan tagihan & pembayaran dilakukan oleh admin/bendahara.</p>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-600">Bulan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Warga</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Total Tagihan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataIuran as $iur)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm font-bold text-gray-700">{{ $iur->bulan_tagihan }}</td>
                                <td class="p-3 text-sm text-gray-900">
                                    {{ $iur->warga->user->name }} <br>
                                    <span class="text-xs text-gray-400">No: {{ $iur->warga->no_warga }}</span>
                                </td>
                                <td class="p-3 text-sm text-gray-900 font-semibold">
                                    Rp {{ number_format($iur->jumlah_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $iur->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : ($iur->status_pembayaran == 'Sedang Diproses' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $iur->status_pembayaran }}
                                    </span>
                                    @if($iur->status_pembayaran == 'Lunas')
                                        <div class="text-xs text-gray-400 mt-1">Metode: {{ $iur->metode_pembayaran }}</div>
                                    @elseif($iur->status_pembayaran == 'Sedang Diproses' && $iur->bukti_pembayaran)
                                        <div class="mt-1">
                                            <a href="{{ \Storage::url($iur->bukti_pembayaran) }}" target="_blank" class="text-xs text-amber-700 hover:underline font-semibold">Lihat Bukti &rarr;</a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-sm text-gray-500">Belum ada catatan iuran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>
