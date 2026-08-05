<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h2>
                        <p class="text-sm text-gray-500">Ringkasan laba rugi & arus kas</p>
                    </div>
                    <a href="{{ route('owner.laporan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg border border-green-200">
                            <span class="font-semibold text-green-800">Total Pemasukan (Iuran Lunas)</span>
                            <span class="text-xl font-bold text-green-700">Rp {{ number_format($pemasukan ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border border-red-200">
                            <span class="font-semibold text-red-800">Total Pengeluaran Operasional</span>
                            <span class="text-xl font-bold text-red-700">Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border border-red-200">
                            <span class="font-semibold text-red-800">Total Pengeluaran Gaji</span>
                            <span class="text-xl font-bold text-red-700">Rp {{ number_format($gaji ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @php $laba = ($pemasukan ?? 0) - ($pengeluaran ?? 0) - ($gaji ?? 0); @endphp
                        <div class="flex justify-between items-center p-4 {{ $laba >= 0 ? 'bg-blue-50 border-blue-200' : 'bg-purple-50 border-purple-200' }} rounded-lg border">
                            <span class="font-semibold {{ $laba >= 0 ? 'text-blue-800' : 'text-purple-800' }}">Laba / Rugi Bersih</span>
                            <span class="text-xl font-bold {{ $laba >= 0 ? 'text-blue-700' : 'text-purple-700' }}">Rp {{ number_format($laba, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>
