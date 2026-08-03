<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-manager-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <!-- CSS Leaflet Peta -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 bg-white shadow sm:rounded-lg">
                <div class="mb-4">
                    <a href="{{ route('manager.rute.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke daftar rute</a>
                    <p class="text-sm text-gray-500 mt-1">Berikut adalah sebaran lokasi pelanggan aktif di dalam sistem rute armada ini.</p>
                </div>

                <!-- Kontainer Peta -->
                <div id="map" style="height: 500px; width: 100%;" class="rounded border shadow-inner"></div>
            </div>
        </div>
        </main>
    </div>

    <!-- Script JavaScript Leaflet Peta -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta ke koordinat default (Contoh pusat Indonesia / Jakarta jika data rute kosong)
        // Set view default ke koordinat Jakarta jika data lokasi pelanggan kosong [-6.2088, 106.8456]
        var map = L.map('map').setView([-6.2088, 106.8456], 13);

        // Pasang layer map OpenStreetMap gratisan
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Array penampung koordinat untuk auto-zoom peta agar fokus ke sekumpulan titik marker
        var markersBounds = [];

        // Parsing data pelanggan dari Laravel Blade ke JavaScript Object secara aman
        @foreach($pelangganPeta as $plg)
            var lat = {{ $plg->latitude }};
            var lng = {{ $plg->longitude }};
            var nama = "{{ $plg->user->name }}";
            var noPlg = "{{ $plg->no_pelanggan }}";

            // Buat penanda pin (marker) di peta
            var marker = L.marker([lat, lng]).addTo(map)
                .bindPopup("<b>" + nama + "</b><br>No Pelanggan: " + noPlg);

            markersBounds.push([lat, lng]);
        @endforeach

        // Jika ada pelanggan yang memiliki titik koordinat, atur fokus kamera peta otomatis ke area tersebut
        if (markersBounds.length > 0) {
            var bounds = L.latLngBounds(markersBounds);
            map.fitBounds(bounds);
        }
    </script>
    <x-manager-bottom-nav />
</x-app-layout>
