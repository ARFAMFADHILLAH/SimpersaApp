<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Dashboard Administrasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Ringkasan operasional kantor hari ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalPelanggan) }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500">Armada Aktif</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalArmadaAktif }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500">Pengaduan Baru</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $pengaduanBaru }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500">Pengangkutan (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalPengangkutanBulanIni }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500">Volume (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalVolumeBulanIni, 1) }} m&sup3;</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaduan Terbaru</h3>
                        <div class="space-y-3">
                            @forelse($pengaduanTerbaru as $aduan)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        {{ $aduan->status_respon == 'Belum Dikerjakan' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $aduan->status_respon == 'Sedang Dikerjakan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $aduan->status_respon == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ $aduan->status_respon }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $aduan->pelanggan?->user?->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $aduan->tipe_kendala }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada pengaduan.</p>
                            @endforelse
                        </div>
                        <a href="{{ route('administrasi.pengaduan.index') }}" class="text-sm text-green-600 hover:underline mt-3 inline-block">Lihat semua</a>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengangkutan Terbaru</h3>
                        <div class="space-y-3">
                            @forelse($pengangkutanTerbaru as $angkut)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $angkut->pelanggan?->user?->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $angkut->armada?->nama_kendaraan ?? '-' }} &middot; {{ $angkut->volume_m3 }} m&sup3;</p>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($angkut->tanggal_tugas)->format('d/m/Y') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada pengangkutan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
