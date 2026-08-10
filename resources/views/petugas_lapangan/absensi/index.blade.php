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

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Absensi Petugas</h1>
                    <p class="text-sm text-gray-500 mt-1">Clock-in saat mulai kerja dan clock-out saat selesai.</p>
                </div>

                <!-- STATUS & TOMBOL ABSEN -->
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="font-bold text-gray-900">{{ now()->format('d/m/Y') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Jam Masuk</p>
                                <p class="font-bold text-gray-900">{{ $absensiHariIni->jam_masuk ?? '-' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Jam Pulang</p>
                                <p class="font-bold text-gray-900">{{ $absensiHariIni->jam_pulang ?? '-' }}</p>
                            </div>
                        </div>
<div class="flex items-center gap-3">
                        @if(!$absensiHariIni || !$absensiHariIni->jam_masuk)
                            <form action="{{ route('petugas.absensi.clockin') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-end gap-2 w-full sm:w-auto">
                                @csrf
                                <div class="w-full sm:w-72">
                                    <x-camera-capture name="foto_masuk" label="Foto Wajah saat Masuk" facing="user" :required="true"
                                                      hint="Kamera realtime — ambil foto wajah langsung." />
                                    @error('foto_masuk')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm">
                                    Clock-In
                                </button>
                            </form>
                        @elseif(!$absensiHariIni->jam_pulang)
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-xl">Sudah Clock-In &#10003;</span>
                                @if($absensiHariIni->foto_masuk)
                                    <a href="{{ asset('storage/' . $absensiHariIni->foto_masuk) }}" target="_blank" title="Foto wajah saat masuk">
                                        <img src="{{ asset('storage/' . $absensiHariIni->foto_masuk) }}" alt="Foto masuk" class="w-12 h-12 object-cover rounded-lg border shadow-sm">
                                    </a>
                                @endif
                                <form action="{{ route('petugas.absensi.clockout') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-end gap-2">
                                    @csrf
                                    <div class="w-full sm:w-72">
                                        <x-camera-capture name="foto_pulang" label="Foto Wajah saat Pulang" facing="user" :required="true"
                                                          hint="Kamera realtime — ambil foto wajah langsung." />
                                        @error('foto_pulang')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm">
                                        Clock-Out
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-xl">Selesai hari ini</span>
                        @endif
                    </div>
                    </div>
                </div>

                <!-- RIWAYAT ABSENSI -->
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Riwayat Absensi Saya</h3>
                    <p class="text-sm text-gray-500 mb-4">Total kehadiran: <span class="font-bold text-gray-800">{{ $totalHadir }}</span> hari</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
<thead>
                                    <tr class="border-b bg-gray-50">
                                        <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                                        <th class="p-3 text-sm font-semibold text-gray-600">Jam Masuk</th>
                                        <th class="p-3 text-sm font-semibold text-gray-600">Jam Pulang</th>
                                        <th class="p-3 text-sm font-semibold text-gray-600">Foto Masuk</th>
                                        <th class="p-3 text-sm font-semibold text-gray-600">Foto Pulang</th>
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
                                            @if($absen->foto_masuk)
                                                <a href="{{ asset('storage/' . $absen->foto_masuk) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $absen->foto_masuk) }}" alt="Foto masuk" class="w-10 h-10 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm">
                                            @if($absen->foto_pulang)
                                                <a href="{{ asset('storage/' . $absen->foto_pulang) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $absen->foto_pulang) }}" alt="Foto pulang" class="w-10 h-10 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $absen->status == 'hadir' ? 'bg-green-100 text-green-800' : ($absen->status == 'izin' ? 'bg-blue-100 text-blue-800' : ($absen->status == 'sakit' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')) }}">
                                                {{ ucfirst($absen->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-sm text-gray-500">Belum ada riwayat absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-petugas-bottom-nav />
</x-app-layout>