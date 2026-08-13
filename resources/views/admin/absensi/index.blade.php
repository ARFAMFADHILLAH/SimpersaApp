<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Rekap Kehadiran Petugas</h1>
                    <p class="text-sm text-gray-500 mt-1">Pemantauan kontrol kinerja petugas lapangan.</p>
                </div>

                <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                    <form method="GET" action="{{ route('admin.absensi.index') }}" class="flex items-end gap-4">
                        <div>
                            <x-input-label for="bulan" value="Pilih Bulan" />
                            <x-text-input id="bulan" name="bulan" type="month" value="{{ $bulan }}" class="mt-1 block" />
                        </div>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">Tampilkan</x-primary-button>
                    </form>
                </div>

                @forelse($dataAbsensi as $item)
                    <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 border-b pb-3">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">{{ $item->petugas->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $item->petugas->email }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                                <span class="px-2.5 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">Hadir: {{ $item->total_hadir }}</span>
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">Izin: {{ $item->total_izin }}</span>
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Sakit: {{ $item->total_sakit }}</span>
                                <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-200">Alpha: {{ $item->total_alpha }}</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="border-b bg-gray-50">
                                        <th class="p-3 font-semibold text-gray-600">Tanggal</th>
                                        <th class="p-3 font-semibold text-gray-600">Jam Masuk</th>
                                        <th class="p-3 font-semibold text-gray-600">Jam Pulang</th>
                                        <th class="p-3 font-semibold text-gray-600">Foto Masuk</th>
                                        <th class="p-3 font-semibold text-gray-600">Foto Pulang</th>
                                        <th class="p-3 font-semibold text-gray-600">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->riwayat as $absen)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-3 text-gray-700">{{ date('d/m/Y', strtotime($absen->tanggal)) }}</td>
                                            <td class="p-3 text-gray-700">{{ $absen->jam_masuk ?? '-' }}</td>
                                            <td class="p-3 text-gray-700">{{ $absen->jam_pulang ?? '-' }}</td>
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
                                            <td class="p-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $absen->status == 'hadir' ? 'bg-green-100 text-green-800' : ($absen->status == 'izin' ? 'bg-blue-100 text-blue-800' : ($absen->status == 'sakit' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($absen->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data absensi pada bulan ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="p-6 bg-white shadow sm:rounded-lg text-center text-gray-500">
                        Belum ada petugas lapangan yang aktif.
                    </div>
                @endforelse

            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>