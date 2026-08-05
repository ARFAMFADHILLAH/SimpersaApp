<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Eksekutif Manajemen Persampahan') }}
        </h2>
    </x-slot> --}}

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- BARIS 1: RINGKASAN KEUANGAN (MODUL 8) -->
            <h3 class="text-gray-600 font-bold uppercase tracking-wider text-sm">💰 Ikhtisar Keuangan & Neraca Kas</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 font-medium">Total Pendapatan (Iuran)</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-orange-500">
                    <div class="text-sm text-gray-500 font-medium">Pengeluaran Gaji</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalGaji, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <div class="text-sm text-gray-500 font-medium">Biaya Operasional & BBM</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 {{ $labaRugiBersih >= 0 ? 'border-blue-500' : 'border-purple-600' }}">
                    <div class="text-sm text-gray-500 font-medium">Laba / Rugi Bersih</div>
                    <div class="text-2xl font-bold mt-1 {{ $labaRugiBersih >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        Rp {{ number_format($labaRugiBersih, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- BARIS 2: METRIK OPERASIONAL & WARGA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Box Kiri: Data Warga -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">👥 Status Kewargaan</h4>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-gray-50 p-3 rounded">
                            <span class="text-xs text-gray-500">Total</span>
                            <div class="text-xl font-bold text-gray-800">{{ $totalWarga }}</div>
                        </div>
                        <div class="bg-green-50 p-3 rounded">
                            <span class="text-xs text-green-600">Aktif</span>
                            <div class="text-xl font-bold text-green-700">{{ $wargaAktif }}</div>
                        </div>
                        <div class="bg-red-50 p-3 rounded">
                            <span class="text-xs text-red-600">Menunggak</span>
                            <div class="text-xl font-bold text-red-700">{{ $wargaMenunggak }}</div>
                        </div>
                    </div>
                </div>

                <!-- Box Kanan: Data Produksi Sampah & Armada -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-gray-800 font-semibold mb-4 border-b pb-2">🚚 Logistik & Volume Sampah Terkelola</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded">
                            <div>
                                <span class="text-xs text-gray-500">Total Akumulasi Sampah</span>
                                <div class="text-base font-bold text-gray-800">
                                    {{ $totalVolumeSampah }} m³ / {{ $totalBeratSampah }} Kg
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded">
                            <div>
                                <span class="text-xs text-gray-500">Kesiapan Armada</span>
                                <div class="text-sm font-semibold text-gray-800">
                                    🟢 Aktif: {{ $armadaAktif }} | 🔴 Rusak: {{ $armadaRusak }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ANJURAN SISTEM PENDUKUNG KEPUTUSAN CEPAT (MODUL 12) -->
            <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-indigo-600 font-bold">💡 DSS Hint:</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-indigo-700 font-medium">
                            @if($wargaMenunggak > 0)
                                Terdeteksi ada {{ $wargaMenunggak }} warga yang menunggak iuran. Sistem menyarankan Anda untuk segera mengaktifkan fitur Notifikasi Pengingat Otomatis (Modul 13).
                            @else
                                Neraca kas dan status kepangganan terpantau stabil. Alokasi anggaran BBM operasional dapat dijadwalkan ulang secara aman.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
