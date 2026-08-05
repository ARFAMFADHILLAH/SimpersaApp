<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

            <!-- Informasi Rute -->
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
                            Jadwal: {{ $rute->hari_angkut }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mt-2">{{ $rute->nama_rute }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $rute->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
                    </div>
                    <a href="{{ route('petugas.rute.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Peta Rute -->
            @php
                $titikPeta = $rute->warga->filter(fn ($w) => $w->latitude && $w->longitude)->values();
            @endphp
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-sm text-gray-800">Peta Rute</h4>
                        <p class="text-xs text-gray-400">Titik rumah warga pada rute ini</p>
                    </div>
                    @if($titikPeta->isNotEmpty())
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">{{ $titikPeta->count() }} titik</span>
                    @endif
                </div>
                @if($titikPeta->isEmpty())
                    <div class="p-6 text-center text-xs text-gray-400">
                        Belum ada koordinat titik pada rute ini.
                    </div>
                @else
                    <div id="petaRute" class="h-72 w-full"></div>
                @endif
            </div>

            <!-- Daftar Warga / Titik di Rute Ini -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-md font-bold text-gray-900">Daftar Warga pada Rute Ini</h3>
                    <p class="text-xs text-gray-500">Titik-titik tujuan pengangkutan sampah.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold">No</th>
                                <th class="p-4 font-bold">Nama Warga</th>
                                <th class="p-4 font-bold">Alamat</th>
                                <th class="p-4 font-bold">Hasil Angkut</th>
                                <th class="p-4 font-bold text-center">Status & Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($rute->warga ?? [] as $index => $warga)
                                @php
                                    $tugas = $pengangkutan[$warga->id] ?? null;
                                    $sudahSelesai = $tugas && $tugas->status_tugas == 'Selesai';
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 text-xs text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-4 font-medium text-gray-900">{{ $warga->user->name ?? 'Warga' }}</td>
                                    <td class="p-4 text-xs text-gray-600">
                                        {{ $warga->alamat_lengkap ?? '-' }}
                                        @if($warga->latitude && $warga->longitude)
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $warga->latitude }},{{ $warga->longitude }}" target="_blank" class="text-emerald-600 hover:underline font-semibold block mt-0.5">Buka Peta &rarr;</a>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs text-gray-600">
                                        @if($tugas && ($tugas->volume_m3 || $tugas->berat_kg))
                                            Vol: {{ $tugas->volume_m3 ?? '-' }} m³ | Brt: {{ $tugas->berat_kg ?? '-' }} kg
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-4 text-center space-y-2">
                                        @if($sudahSelesai)
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full inline-block">
                                                Selesai
                                            </span>
                                            @if($tugas->foto_sebelum || $tugas->foto_sesudah)
                                                <div class="flex justify-center gap-2 mt-2">
                                                    @if($tugas->foto_sebelum)
                                                        <a href="{{ asset('storage/dokumentasi/' . $tugas->foto_sebelum) }}" target="_blank" title="Lihat Foto Sebelum">
                                                            <img src="{{ asset('storage/dokumentasi/' . $tugas->foto_sebelum) }}" class="w-10 h-10 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
                                                        </a>
                                                    @endif
                                                    @if($tugas->foto_sesudah)
                                                        <a href="{{ asset('storage/dokumentasi/' . $tugas->foto_sesudah) }}" target="_blank" title="Lihat Foto Sesudah">
                                                            <img src="{{ asset('storage/dokumentasi/' . $tugas->foto_sesudah) }}" class="w-10 h-10 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
                                                        </a>
                                                    @endif
                                                </div>
                                                @if($tugas->catatan)
                                                    <p class="text-[10px] text-gray-500 italic mt-1">Catatan: "{{ $tugas->catatan }}"</p>
                                                @endif
                                            @endif
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full inline-block">
                                                Belum Diangkut
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 text-sm">
                                        Belum ada data warga yang terdaftar pada rute ini.
                                    </td>
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

    @if($titikPeta->isNotEmpty())
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var map = L.map('petaRute');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            @php
                $titikJson = $titikPeta->map(function ($w, $key) {
                    return [
                        'no' => $key + 1,
                        'lat' => (float) $w->latitude,
                        'lng' => (float) $w->longitude,
                        'nama' => $w->user->name ?? 'Warga',
                        'alamat' => $w->alamat_lengkap ?? '',
                        'link' => 'https://www.google.com/maps/search/?api=1&query=' . $w->latitude . ',' . $w->longitude,
                    ];
                })->values();
            @endphp
            var titik = @json($titikJson);

            var markers = [];
            titik.forEach(function (t) {
                var marker = L.marker([t.lat, t.lng]).addTo(map);
                marker.bindPopup(
                    '<strong>#' + t.no + '. ' + t.nama + '</strong><br>' +
                    '<span style="font-size:11px">' + t.alamat + '</span><br>' +
                    '<a href="' + t.link + '" target="_blank" style="font-size:11px;font-weight:bold">Buka Navigasi &rarr;</a>'
                );
                markers.push(marker);
            });

            var bounds = L.latLngBounds(markers.map(function (m) { return m.getLatLng(); }));
            map.fitBounds(bounds, { padding: [30, 30], maxZoom: 16 });
        </script>
    @endif
</x-app-layout>
