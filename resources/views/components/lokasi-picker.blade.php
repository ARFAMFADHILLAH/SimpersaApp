@props([
    'mapId' => 'mapLokasi',
    'mode' => 'latlng', // 'latlng' = dua field, 'string' = satu field "lat,lng"
    'latName' => 'latitude',
    'lngName' => 'longitude',
    'stringName' => 'titik_koordinat_pusat',
    'initialLat' => null,
    'initialLng' => null,
    'initialString' => null,
    'addressInputId' => null,
    'hint' => 'Klik pada peta untuk menandai lokasi, atau gunakan "Cari dari Alamat".',
])

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<div id="{{ $mapId }}" class="h-64 w-full rounded-xl border border-gray-200 shadow-sm" style="z-index: 0;"></div>

@if($mode === 'latlng')
    <input type="hidden" name="{{ $latName }}" id="{{ $mapId }}_lat" value="{{ $initialLat }}">
    <input type="hidden" name="{{ $lngName }}" id="{{ $mapId }}_lng" value="{{ $initialLng }}">
@else
    <input type="hidden" name="{{ $stringName }}" id="{{ $mapId }}_string" value="{{ $initialString }}">
@endif

<div class="mt-2 flex flex-wrap items-center gap-2">
    <button type="button" id="{{ $mapId }}_geocode" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
        Cari dari Alamat
    </button>
    <button type="button" id="{{ $mapId }}_locate" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition">
        Pakai Lokasi Saya
    </button>
    <span id="{{ $mapId }}_info" class="text-xs text-gray-500 font-medium">
        @if($mode === 'latlng' && $initialLat)
            {{ $initialLat }}, {{ $initialLng }}
        @elseif($mode === 'string' && $initialString)
            {{ $initialString }}
        @else
            Belum ada koordinat
        @endif
    </span>
</div>
<p class="text-[11px] text-gray-400 mt-1">{{ $hint }}</p>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var mapId = '{{ $mapId }}';
    var mode = '{{ $mode }}';

    @php
        $startLat = $mode === 'latlng' ? $initialLat : (explode(',', (string) $initialString)[0] ?? null);
        $startLng = $mode === 'latlng' ? $initialLng : (explode(',', (string) $initialString)[1] ?? null);
    @endphp

    var startLat = {{ $startLat ?: '-6.2' }};
    var startLng = {{ $startLng ?: '106.816666' }};
    var hasStart = {{ ($startLat && $startLng) ? 'true' : 'false' }};

    var map = L.map(mapId).setView([startLat, startLng], hasStart ? 15 : 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = null;
    if (hasStart) {
        marker = L.marker([startLat, startLng]).addTo(map);
    }

    function setKoordinat(lat, lng) {
        var info = document.getElementById(mapId + '_info');
        if (mode === 'latlng') {
            document.getElementById(mapId + '_lat').value = lat;
            document.getElementById(mapId + '_lng').value = lng;
            info.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
        } else {
            document.getElementById(mapId + '_string').value = lat.toFixed(6) + ',' + lng.toFixed(6);
            info.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
        }
        if (marker) { map.removeLayer(marker); }
        marker = L.marker([lat, lng]).addTo(map);
    }

    map.on('click', function (e) {
        setKoordinat(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById(mapId + '_locate').addEventListener('click', function () {
        if (!navigator.geolocation) { return; }
        navigator.geolocation.getCurrentPosition(function (pos) {
            setKoordinat(pos.coords.latitude, pos.coords.longitude);
            map.setView([pos.coords.latitude, pos.coords.longitude], 15);
        });
    });

    document.getElementById(mapId + '_geocode').addEventListener('click', function () {
        var addressInput = document.getElementById('{{ $addressInputId }}');
        var alamat = addressInput ? addressInput.value.trim() : '';
        if (!alamat) {
            alert('Isi alamat lengkap terlebih dahulu.');
            return;
        }
        var btn = this;
        btn.disabled = true;
        btn.textContent = 'Mencari...';
        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(alamat))
            .then(function (r) { return r.json(); })
            .then(function (hasil) {
                if (hasil && hasil.length > 0) {
                    var lat = parseFloat(hasil[0].lat);
                    var lng = parseFloat(hasil[0].lon);
                    setKoordinat(lat, lng);
                    map.setView([lat, lng], 16);
                } else {
                    alert('Alamat tidak ditemukan di peta. Coba klik langsung pada peta.');
                }
            })
            .catch(function () {
                alert('Gagal mencari alamat. Periksa koneksi internet atau klik langsung pada peta.');
            })
            .finally(function () {
                btn.disabled = false;
                btn.textContent = 'Cari dari Alamat';
            });
    });
})();
</script>
