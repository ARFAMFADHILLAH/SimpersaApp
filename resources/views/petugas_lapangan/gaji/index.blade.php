<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Absensi Saya</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Jam Masuk</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Jam Pulang</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatAbsensi as $absen)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-700">{{ date('d/m/Y', strtotime($absen->tanggal)) }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $absen->jam_masuk ?? '-' }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $absen->jam_pulang ?? '-' }}</td>
                                    <td class="p-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $absen->status == 'hadir' ? 'bg-green-100 text-green-800' : ($absen->status == 'izin' ? 'bg-blue-100 text-blue-800' : ($absen->status == 'sakit' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ ucfirst($absen->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-sm text-gray-500">Belum ada riwayat absensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Penggajian</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">Bulan</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Gaji Pokok</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Insentif</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Potongan</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 bg-indigo-50 text-indigo-700">Bersih</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Slip</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatGaji as $gaji)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm font-medium text-gray-900">{{ $gaji->bulan_gaji }}</td>
                                    <td class="p-3 text-sm text-gray-700">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm text-green-700">Rp {{ number_format($gaji->insentif_lembur, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm text-red-600">Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm font-bold bg-indigo-50 text-indigo-700">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $gaji->status_pembayaran == 'Dibayar' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $gaji->status_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-sm">
                                        <a href="{{ route('bendahara.penggajian.slip', $gaji->id) }}" target="_blank"
                                           class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200">
                                            Slip
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-sm text-gray-500">Belum ada data penggajian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
    <x-petugas-bottom-nav />
</x-app-layout>
