<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <!-- AREA DAFTAR TUGAS -->
        <main class="flex-1 p-4 sm:p-6 space-y-4">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($daftarTugas->isEmpty())
                <!-- Tampilan jika tidak ada tugas -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100">
                    <div class="inline-flex bg-gray-100 p-4 rounded-full text-gray-400 mb-3">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-gray-700 font-bold text-base">Tidak Ada Tugas</h3>
                    <p class="text-gray-400 text-xs mt-1">Jadwal pengangkutan Anda kosong atau belum diatur oleh admin.</p>
                </div>
            @else
                <!-- PETA RUTE & TITIK TUGAS -->
                @php
                    $titikPeta = $daftarTugas->filter(fn ($t) => $t->latitude && $t->longitude);
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">Peta Titik Tugas</h4>
                            <p class="text-xs text-gray-400">Klik marker untuk membuka navigasi Google Maps</p>
                        </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">{{ $titikPeta->count() }} titik</span>
                        <a href="{{ route('petugas.rute.index') }}" class="text-[11px] font-bold text-gray-500 hover:text-emerald-700 transition">Zonasi Rute &rarr;</a>
                    </div>
                    </div>
                    @if($titikPeta->isEmpty())
                        <div class="p-6 text-center text-xs text-gray-400">
                            Belum ada koordinat titik tugas. Gunakan tombol "Buka Peta Petunjuk" pada tiap kartu di bawah.
                        </div>
                    @else
                        <div id="petaTugas" class="h-72 w-full" style="z-index: 0;"></div>
                    @endif
                </div>

                <!-- Looping Daftar Rute/Tugas -->
                <div class="space-y-4">
                    @foreach($daftarTugas as $index => $tugas)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden {{ $tugas->status_tugas == 'proses' ? 'ring-2 ring-emerald-600' : '' }}">
                            <div class="p-4 sm:p-5">

                                <!-- Baris Atas: Nama Rute & Status badge -->
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <div>
                                        <span class="text-[10px] font-bold tracking-wider text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded">
                                            {{ $tugas->nama_rute }}
                                        </span>
                                        <h3 class="font-bold text-gray-800 text-base mt-1">
                                            #{{ $index + 1 }}. {{ $tugas->nama_warga }}
                                        </h3>
                                    </div>

                                    <!-- Badge Status Kontrol -->
                                    @if($tugas->status_tugas == 'selesai')
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">Selesai</span>
                                    @elseif($tugas->status_tugas == 'proses')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full animate-pulse">Sedang Proses</span>
                                    @elseif($tugas->status_tugas == 'lewat')
                                        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">Dilewati</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-full">Menunggu</span>
                                    @endif
                                </div>

                                <!-- Alamat Warga -->
                                <p class="text-xs text-gray-500 flex items-start gap-1.5 mb-3">
                                    <svg class="h-4 w-4 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $tugas->alamat_lengkap }}
                                </p>

                                <!-- Tombol Aksi Lapangan -->
                                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-gray-100 justify-between">
                                    <!-- Tombol Navigasi Google Maps -->
                                    <a href="{{ $tugas->latitude && $tugas->longitude
                                            ? 'https://www.google.com/maps/search/?api=1&query=' . $tugas->latitude . ',' . $tugas->longitude
                                            : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($tugas->alamat_lengkap) }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-1 text-xs text-emerald-700 font-bold bg-emerald-50 hover:bg-emerald-100 px-3 py-2 rounded-xl transition">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        Buka Peta Petunjuk
                                    </a>

                                    <!-- Update Status -->
                                    @if($tugas->pengangkutan_id)
                                    <form action="{{ route('petugas.rute.update', $tugas->pengangkutan_id) }}" method="POST" class="inline-flex gap-1.5">
                                        @csrf
                                        @if($tugas->status_tugas != 'selesai')
                                            <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-2.5 py-1.5 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 font-medium">
                                                <option value="" disabled selected>Pilih Status</option>
                                                <option value="proses" {{ $tugas->status_tugas == 'proses' ? 'selected' : '' }}>Angkut/Proses</option>
                                                <option value="selesai">Selesai Angkut</option>
                                                <option value="lewat" {{ $tugas->status_tugas == 'lewat' ? 'selected' : '' }}>Lewati (Rumah Kosong)</option>
                                            </select>
                                        @else
                                            <span class="text-xs text-green-600 font-semibold inline-flex items-center gap-1 py-1.5">
                                                ✓ Berhasil Diselesaikan
                                            </span>
                                        @endif
                                    </form>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium py-1.5">Belum ada tugas</span>
                                    @endif
                                </div>

                                <!-- Dokumentasi & Hasil Pengangkutan -->
                                @if($tugas->pengangkutan_id)
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    @if($tugas->status_tugas == 'selesai' && ($tugas->foto_sebelum || $tugas->foto_sesudah))
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="flex gap-2">
                                                @if($tugas->foto_sebelum)
                                                    <div>
                                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">Sebelum</span>
                                                        <a href="{{ asset('storage/dokumentasi/' . $tugas->foto_sebelum) }}" target="_blank">
                                                            <img src="{{ asset('storage/dokumentasi/' . $tugas->foto_sebelum) }}" class="mt-1 w-24 h-20 object-cover rounded-lg border border-gray-200 shadow-sm hover:scale-105 transition">
                                                        </a>
                                                    </div>
                                                @endif
                                                @if($tugas->foto_sesudah)
                                                    <div>
                                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Sesudah</span>
                                                        <a href="{{ asset('storage/dokumentasi/' . $tugas->foto_sesudah) }}" target="_blank">
                                                            <img src="{{ asset('storage/dokumentasi/' . $tugas->foto_sesudah) }}" class="mt-1 w-24 h-20 object-cover rounded-lg border border-gray-200 shadow-sm hover:scale-105 transition">
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-600 space-y-0.5">
                                                <p class="font-semibold text-gray-800">Hasil: {{ $tugas->volume_m3 ?? '-' }} m³ | {{ $tugas->berat_kg ?? '-' }} kg</p>
                                                @if($tugas->catatan)
                                                    <p class="italic text-gray-500">Catatan: "{{ $tugas->catatan }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('petugas.pengangkutan.upload', $tugas->pengangkutan_id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                            @csrf
                                            <div class="flex items-center justify-between">
                                                <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Dokumentasi & Hasil Pengangkutan</h5>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Foto Sebelum <span class="text-red-500">*</span></label>
                                                    <input type="file" name="foto_sebelum" accept="image/*" required class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-gray-200 rounded-lg cursor-pointer">
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Foto Sesudah <span class="text-red-500">*</span></label>
                                                    <input type="file" name="foto_sesudah" accept="image/*" required class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-lg cursor-pointer">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Volume Sampah (m³) — opsional</label>
                                                    <input type="number" name="volume_m3" step="0.1" min="0" class="block w-full text-xs border-gray-200 rounded-lg">
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Berat Sampah (kg) — opsional</label>
                                                    <input type="number" name="berat_kg" step="0.1" min="0" class="block w-full text-xs border-gray-200 rounded-lg">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Catatan Lapangan</label>
                                                <textarea name="catatan" rows="2" class="w-full text-xs border-gray-200 rounded-lg" placeholder="Kendala atau keterangan kondisi di titik ini..."></textarea>
                                            </div>
                                            <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow transition">
                                                Simpan & Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </main>
    </div>

    <x-petugas-bottom-nav />

    @if($daftarTugas->isNotEmpty() && $titikPeta->isNotEmpty())
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var map = L.map('petaTugas');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            @php
                $titikJson = $titikPeta->map(function ($t, $key) {
                    return [
                        'no' => $key + 1,
                        'lat' => (float) $t->latitude,
                        'lng' => (float) $t->longitude,
                        'nama' => $t->nama_warga,
                        'rute' => $t->nama_rute,
                        'alamat' => $t->alamat_lengkap,
                        'link' => 'https://www.google.com/maps/search/?api=1&query=' . $t->latitude . ',' . $t->longitude,
                    ];
                })->values();
            @endphp
            var titik = @json($titikJson);

            var markers = [];
            titik.forEach(function (t, i) {
                var marker = L.marker([t.lat, t.lng]).addTo(map);
                marker.bindPopup(
                    '<strong>#' + t.no + '. ' + t.nama + '</strong><br>' +
                    '<span style="font-size:11px">' + t.rute + '<br>' + t.alamat + '</span><br>' +
                    '<a href="' + t.link + '" target="_blank" style="font-size:11px;font-weight:bold">Buka Navigasi &rarr;</a>'
                );
                markers.push(marker);
            });

            var bounds = L.latLngBounds(markers.map(function (m) { return m.getLatLng(); }));
            map.fitBounds(bounds, { padding: [30, 30], maxZoom: 16 });
        </script>
    @endif
</x-app-layout>
