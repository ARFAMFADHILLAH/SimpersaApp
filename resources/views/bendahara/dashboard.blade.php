<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pemasukan Bulan Ini</div>
                        <div class="text-2xl font-black text-green-600 mt-2">
                            Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 block">Dari Iuran Sampah Lunas</span>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kas Keluar</div>
                        <div class="text-2xl font-black text-red-600 mt-2">
                            Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 block">Gaji + Operasional</span>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Saldo Bersih Bulan Ini</div>
                        <div class="text-2xl font-black {{ $sisaLabaRugiBersih >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                            Rp {{ number_format($sisaLabaRugiBersih, 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 block">Surplus / Defisit</span>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tunggakan Belum Dibayar</div>
                        <div class="text-2xl font-black text-green-600 mt-2">
                            Rp {{ number_format($totalNominalTunggakan, 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-green-700 font-semibold mt-1 block">Dari {{ $totalPelangganMenunggak }} Pelanggan</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-xs">Kas Masuk Terbaru</h4>
                            <a href="{{ route('bendahara.iuran.index') }}" class="text-[11px] text-green-600 font-semibold hover:underline">Lihat Semua</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($transaksiTerbaru as $iuran)
                                <div class="p-3 flex justify-between items-center text-xs">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $iuran->pelanggan->user->name ?? 'Warga' }}</p>
                                        <p class="text-[10px] text-gray-400">Periode: {{ $iuran->bulan_tagihan }}</p>
                                    </div>
                                    <span class="font-bold text-green-600">+ Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-gray-400">Belum ada penerimaan iuran bulan ini.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-xs">Pengeluaran Operasional Terbaru</h4>
                            <a href="{{ route('bendahara.operasional.index') }}" class="text-[11px] text-green-600 font-semibold hover:underline">Lihat Semua</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($operasionalTerbaru as $ops)
                                <div class="p-3 flex justify-between items-center text-xs">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $ops->kategori_biaya }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $ops->tanggal_pengeluaran }}</p>
                                    </div>
                                    <span class="font-bold text-red-600">- Rp {{ number_format($ops->jumlah_biaya, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-gray-400">Belum ada catatan pengeluaran operasional.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>
