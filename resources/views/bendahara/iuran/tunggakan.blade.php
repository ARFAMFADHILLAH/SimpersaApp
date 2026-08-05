<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Monitoring Warga Menunggak</h3>

                    @if($dataTunggakan->isEmpty())
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            Semua warga telah melunasi iuran. Tidak ada tunggakan.
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($dataTunggakan as $wargaId => $tagihanList)
                                @php
                                    $warga = $tagihanList->first()->warga;
                                    $totalTunggakan = $tagihanList->sum('jumlah_tagihan') + $tagihanList->sum('denda');
                                    $jumlahBulan = $tagihanList->count();
                                @endphp
                                <div class="border border-red-200 rounded-lg overflow-hidden">
                                    <div class="bg-red-50 px-4 py-3 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $warga->user->name ?? 'Warga' }}</h4>
                                            <p class="text-xs text-gray-500">No. {{ $warga->no_warga ?? '-' }} | {{ $warga->alamat_lengkap ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-red-600">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
                                            <span class="text-xs text-red-500 block">{{ $jumlahBulan }} bulan menunggak</span>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-100">
                                        @foreach($tagihanList as $tagihan)
                                            <div class="px-4 py-2 flex justify-between items-center text-sm">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-700">{{ $tagihan->bulan_tagihan }}</span>
                                                    @if($tagihan->status_pembayaran === 'Sedang Diproses')
                                                        <span class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">MENUNGGU VERIFIKASI</span>
                                                    @else
                                                        <span class="text-[10px] font-semibold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">BELUM BAYAR</span>
                                                    @endif
                                                </div>
                                                <span class="font-semibold text-red-600">Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="px-4 py-2 bg-gray-50 flex justify-end">
                                        <a href="{{ route('bendahara.iuran.index', ['bulan' => $tagihanList->first()->bulan_tagihan]) }}"
                                           class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700">
                                            Tagih Sekarang
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>
