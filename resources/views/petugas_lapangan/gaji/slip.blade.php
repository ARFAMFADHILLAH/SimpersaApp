<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-petugas-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow overflow-hidden">

                    <div class="p-6 border-b border-dashed">
                        <div class="flex items-center gap-3">
                            <img src="/logo-kisuci.png" alt="SIMPERSA" class="h-10 w-10 object-cover rounded-lg">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">SLIP GAJI — SIMPERSA</h2>
                                <p class="text-xs text-gray-500">Bank Sampah Terintegrasi</p>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <p>Nama: <span class="font-semibold text-gray-900">{{ $gaji->petugas->name ?? '-' }}</span></p>
                            <p class="text-right">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $gaji->bulan_gaji)->format('F Y') }}</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-3">
                        <div class="flex justify-between"><p class="text-sm text-gray-500">Gaji Pokok</p><p class="text-sm font-semibold">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</p></div>
                        <div class="flex justify-between"><p class="text-sm text-gray-500">Bonus / Insentif</p><p class="text-sm font-semibold">Rp {{ number_format($gaji->insentif_lembur ?? 0, 0, ',', '.') }}</p></div>
                        <div class="border-t border-dashed pt-3 flex justify-between">
                            <p class="text-sm font-bold text-gray-900">Total Penerimaan</p>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <p>Status: <span class="font-semibold {{ $gaji->status_pembayaran == 'Dibayar' ? 'text-green-600' : 'text-amber-600' }}">{{ $gaji->status_pembayaran }}</span></p>
                            <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ route('petugas.gaji.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">Kembali</a>
                    <button onclick="window.print()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">Cetak Slip</button>
                </div>
            </div>
        </main>
    </div>
    <x-petugas-bottom-nav />
</x-app-layout>