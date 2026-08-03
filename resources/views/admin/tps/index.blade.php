<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8" x-data="{ openEditModal: false, editData: {} }">
            <!-- CSS & JS Leaflet CDN -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Sukses -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Alert Error Validasi -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Tambah Data TPS -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Lokasi TPS Baru</h3>
                <form action="{{ route('admin.tps.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nama_tps" value="Nama TPS *" />
                            <x-text-input id="nama_tps" name="nama_tps" type="text" class="mt-1 block w-full" value="{{ old('nama_tps') }}" placeholder="Contoh: TPS RW 05 Kedung" required />
                        </div>
                        <div>
                            <x-input-label for="kapasitas_maksimal_m3" value="Kapasitas Maksimal (m³) *" />
                            <x-text-input id="kapasitas_maksimal_m3" name="kapasitas_maksimal_m3" type="number" class="mt-1 block w-full" value="{{ old('kapasitas_maksimal_m3') }}" placeholder="Contoh: 50" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="lokasi_koordinat" value="Lokasi / Koordinat (Klik lokasi pada peta di bawah) *" />
                        <x-text-input id="lokasi_koordinat" name="lokasi_koordinat" type="text" class="mt-1 block w-full bg-gray-50" value="{{ old('lokasi_koordinat') }}" placeholder="-6.200000, 106.816666" readonly required />
                    </div>

                    <!-- Wadah Peta Leaflet untuk Form Tambah -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Pilih Titik Lokasi TPS di Peta:</label>
                        <div id="mapCreate" class="w-full h-64 rounded-lg border z-0"></div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>{{ __('Simpan TPS') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Tabel Menampilkan Data TPS -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Lokasi TPS Saat Ini</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">No</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Nama TPS</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Koordinat</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Kapasitas (m³)</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataTps as $key => $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-700">{{ $key + 1 }}</td>
                                    <td class="p-3 text-sm text-gray-900 font-medium">{{ $item->nama_tps }}</td>
                                    <td class="p-3 text-sm text-gray-700">
                                        @if($item->lokasi_koordinat)
                                            <a href="https://maps.google.com/?q={{ $item->lokasi_koordinat }}" target="_blank" class="text-indigo-600 hover:underline flex items-center gap-1">
                                                📍 {{ $item->lokasi_koordinat }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-gray-700">{{ $item->kapasitas_maksimal_m3 }} m³</td>
                                    <td class="p-3 text-sm text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <!-- Tombol Edit -->
                                            <button type="button" 
    @click="openEditModal = true; 
            editData = {
                id: '{{ $item->id }}',
                nama_tps: '{{ $item->nama_tps }}',
                lokasi_koordinat: '{{ $item->lokasi_koordinat }}',
                kapasitas_maksimal_m3: '{{ $item->kapasitas_maksimal_m3 }}'
            }; 
            $nextTick(() => { 
                document.getElementById('form-edit-tps').action = '{{ route('admin.tps.index') }}/' + editData.id;
                initEditMap(editData.lokasi_koordinat); 
            });"
    class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
    Edit
</button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.tps.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus TPS ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada data TPS. Silakan tambahkan lewat form di atas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- MODAL EDIT TPS -->
        <div x-show="openEditModal" 
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
             x-cloak
             x-transition>
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 space-y-4" @click.away="openEditModal = false">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Edit Data TPS</h3>
                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>
                <form :id="'form-edit-tps'" :action="'/admin/tps/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="edit_nama_tps" value="Nama TPS *" />
                        <x-text-input id="edit_nama_tps" name="nama_tps" type="text" class="mt-1 block w-full" x-model="editData.nama_tps" required />
                    </div>
                    <div>
                        <x-input-label for="edit_kapasitas_maksimal_m3" value="Kapasitas Maksimal (m³) *" />
                        <x-text-input id="edit_kapasitas_maksimal_m3" name="kapasitas_maksimal_m3" type="number" class="mt-1 block w-full" x-model="editData.kapasitas_maksimal_m3" required />
                    </div>
                    <div>
                        <x-input-label for="edit_lokasi_koordinat" value="Lokasi / Koordinat *" />
                        <x-text-input id="edit_lokasi_koordinat" name="lokasi_koordinat" type="text" class="mt-1 block w-full bg-gray-50" x-model="editData.lokasi_koordinat" readonly required />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Pilih / Ubah Lokasi TPS di Peta:</label>
                        <div id="mapEdit" class="w-full h-56 rounded-lg border z-0"></div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">Batal</button>
                        <x-primary-button>{{ __('Update TPS') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SCRIPT LEAFLET INTERAKTIF -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Koordinat Default (Misal: Indonesia / Jakarta)
            const defaultLat = -6.200000;
            const defaultLng = 106.816666;

            // 1. INISIALISASI PETA TAMBAH TPS
            const mapCreate = L.map('mapCreate').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapCreate);

            let markerCreate;

            mapCreate.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);
                const latlngStr = `${lat}, ${lng}`;

                document.getElementById('lokasi_koordinat').value = latlngStr;

                if (markerCreate) {
                    markerCreate.setLatLng(e.latlng);
                } else {
                    markerCreate = L.marker(e.latlng).addTo(mapCreate);
                }
            });
        });

        // 2. INISIALISASI PETA MODAL EDIT TPS
        let mapEdit;
        let markerEdit;

        function initEditMap(koordinatStr) {
            let lat = -6.200000;
            let lng = 106.816666;

            if (koordinatStr && koordinatStr.includes(',')) {
                const parts = koordinatStr.split(',');
                lat = parseFloat(parts[0].trim());
                lng = parseFloat(parts[1].trim());
            }

            if (!mapEdit) {
                mapEdit = L.map('mapEdit').setView([lat, lng], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapEdit);
            } else {
                mapEdit.setView([lat, lng], 14);
                mapEdit.invalidateSize(); // Memastikan ukuran peta pas saat modal terbuka
            }

            if (markerEdit) {
                markerEdit.setLatLng([lat, lng]);
            } else {
                markerEdit = L.marker([lat, lng]).addTo(mapEdit);
            }

            mapEdit.off('click'); // Reset event click sebelumnya
            mapEdit.on('click', function (e) {
                const newLat = e.latlng.lat.toFixed(6);
                const newLng = e.latlng.lng.toFixed(6);
                const latlngStr = `${newLat}, ${newLng}`;

                document.getElementById('edit_lokasi_koordinat').value = latlngStr;
                // Update juga state AlpineJS agar tersimpan di form edit
                Alpine.store('editData') ? null : null; 
                document.getElementById('edit_lokasi_koordinat').dispatchEvent(new Event('input'));

                markerEdit.setLatLng(e.latlng);
            });
        }
        </script>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>