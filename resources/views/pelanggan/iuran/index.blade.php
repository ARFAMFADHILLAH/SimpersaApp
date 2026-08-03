<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-pelanggan-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-lg">{{ session('success') }}</div>
                @endif

                @if($tagihanBulanIni)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-amber-200">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">TAGIHAN AKTIF</span>
                                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ $tagihanBulanIni->bulan_tagihan }}</h4>
                                <p class="text-3xl font-black text-gray-900 mt-1">Rp {{ number_format($tagihanBulanIni->jumlah_tagihan, 0, ',', '.') }}</p>
                                @if($tagihanBulanIni->denda > 0)
                                    <p class="text-xs text-red-600 mt-1">Denda: Rp {{ number_format($tagihanBulanIni->denda, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <div class="w-full md:w-72 bg-gray-50 p-4 rounded-lg border">
                                <p class="text-xs font-semibold text-gray-500 mb-2">Bayar Non-Tunai</p>
                                <form action="{{ route('pelanggan.iuran.bayar', $tagihanBulanIni->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <select name="metode_pembayaran" required class="block w-full rounded-md border-gray-300 shadow-sm text-xs">
                                        <option value="">Pilih Metode</option>
                                        <option value="Non-Tunai">Transfer Bank / E-Wallet / VA</option>
                                        <option value="Tunai">Tunai (Bayar di Kantor)</option>
                                    </select>
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2 px-4 rounded-md transition">
                                        Konfirmasi Pembayaran
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-6 rounded-xl">
                        <p class="font-semibold">Tidak ada tagihan yang perlu dibayar. Semua iuran Anda sudah lunas!</p>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h4 class="font-bold text-gray-800 text-sm">Riwayat Tagihan & Pembayaran</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-3 text-left">Periode</th>
                                    <th class="px-6 py-3 text-left">Tagihan</th>
                                    <th class="px-6 py-3 text-left">Denda</th>
                                    <th class="px-6 py-3 text-left">Total</th>
                                    <th class="px-6 py-3 text-left">Metode</th>
                                    <th class="px-6 py-3 text-left">Tgl Bayar</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($riwayatIuran as $iuran)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $iuran->bulan_tagihan }}</td>
                                        <td class="px-6 py-3">Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3">Rp {{ number_format($iuran->denda ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 font-semibold">Rp {{ number_format($iuran->jumlah_tagihan + ($iuran->denda ?? 0), 0, ',', '.') }}</td>
                                        <td class="px-6 py-3">{{ $iuran->metode_pembayaran ?? '-' }}</td>
                                        <td class="px-6 py-3">{{ $iuran->tanggal_bayar ? \Carbon\Carbon::parse($iuran->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-3 font-bold {{ $iuran->status_pembayaran == 'Lunas' ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $iuran->status_pembayaran }}
                                        </td>
                                        <td class="px-6 py-3">
                                            @if($iuran->status_pembayaran == 'Lunas')
                                                <a href="{{ route('pelanggan.iuran.kwitansi', $iuran->id) }}" class="text-indigo-600 hover:underline font-semibold" target="_blank">Kwitansi</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat tagihan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t">{{ $riwayatIuran->links() }}</div>
                </div>

            </div>
        </main>
    </div>
    <x-pelanggan-bottom-nav />
</x-app-layout>
