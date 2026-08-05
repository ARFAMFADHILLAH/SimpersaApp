<x-app-layout>

    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />

        <!-- Main Content Wrapper -->

        <!-- Main Content Wrapper -->
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Pusat Laporan Eksekutif</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('owner.laporan.tunggakan') }}" class="px-3 py-1.5 bg-red-50 text-red-700 text-sm font-semibold rounded-md hover:bg-red-100">Tunggakan</a>
                        <a href="{{ route('owner.laporan.petugas') }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-md hover:bg-indigo-100">Petugas</a>
                        <a href="{{ route('owner.laporan.rekap-tahunan') }}" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-md hover:bg-emerald-100">Rekap Tahunan</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php
                        $quickLinks = [
                            ['route' => 'owner.laporan.warga', 'label' => 'Laporan Warga', 'desc' => 'Status & kewargaan'],
                            ['route' => 'owner.laporan.iuran', 'label' => 'Laporan Iuran', 'desc' => 'Penerimaan iuran'],
                            ['route' => 'owner.laporan.volume', 'label' => 'Laporan Volume', 'desc' => 'Rekap volume sampah & TPA'],
                            ['route' => 'owner.laporan.keuangan', 'label' => 'Laporan Keuangan', 'desc' => 'Laba rugi & kas'],
                            ['route' => 'owner.laporan.gaji', 'label' => 'Laporan Gaji', 'desc' => 'Rekap payroll petugas'],
                            ['route' => 'owner.laporan.armada', 'label' => 'Laporan Armada', 'desc' => 'Servis & biaya BBM'],
                            ['route' => 'owner.laporan.tunggakan', 'label' => 'Laporan Tunggakan', 'desc' => 'Monitoring tunggakan iuran'],
                            ['route' => 'owner.laporan.petugas', 'label' => 'Laporan Petugas', 'desc' => 'Kinerja & beban kerja'],
                            ['route' => 'owner.laporan.kendala', 'label' => 'Laporan Kendala Petugas', 'desc' => 'Kendala di lapangan'],
                            ['route' => 'owner.laporan.rekap-tahunan', 'label' => 'Rekap Tahunan', 'desc' => 'Agregasi bulanan per tahun'],
                        ];
                    @endphp
                    @foreach($quickLinks as $link)
                        <a href="{{ route($link['route']) }}" class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow transition">
                            <span class="block text-sm font-bold text-gray-900">{{ $link['label'] }}</span>
                            <span class="text-xs text-gray-500">{{ $link['desc'] }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="max-w-md mx-auto">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pilih Periode Laporan</h3>
                            <p class="text-xs text-gray-500">Tentukan rentang tanggal untuk mencetak dokumen executive</p>
                        </div>
                    </div>

                    <form action="{{ route('owner.laporan.cetak') }}" method="POST" target="_blank">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            </div>

                            <div class="pt-2">
                                <x-primary-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 py-2.5">
                                    {{ __('Buka & Cetak Dokumen') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>

            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>