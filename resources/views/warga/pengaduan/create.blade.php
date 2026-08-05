<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-warga-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('warga.pengaduan.index') }}" class="text-xs text-indigo-600 hover:underline">&larr; Kembali</a>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mt-4">
                    <h4 class="font-bold text-gray-800 text-sm border-b pb-3 mb-4">Form Pengaduan Baru</h4>

                    <form action="{{ route('warga.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Tipe Kendala</label>
                            <select name="tipe_kendala" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-xs focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Sampah Belum Diangkut (>2 Hari)">Sampah Belum Diangkut (&gt;2 Hari)</option>
                                <option value="Volume Sampah Berlebih / Berserakan">Volume Sampah Berlebih / Berserakan</option>
                                <option value="Wadah Sampah Rusak / Hilang">Wadah Sampah Rusak / Hilang</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Titik Lokasi / Catatan</label>
                            <input type="text" name="catatan_lokasi" required placeholder="Contoh: Depan pagar rumah no. C12" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-xs focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase">Latitude</label>
                                <input type="text" name="latitude" id="latitude" placeholder="-6.917464" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-xs focus:border-indigo-500 focus:ring-indigo-500" value="{{ $warga->latitude ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase">Longitude</label>
                                <input type="text" name="longitude" id="longitude" placeholder="107.619123" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-xs focus:border-indigo-500 focus:ring-indigo-500" value="{{ $warga->longitude ?? '' }}">
                            </div>
                        </div>
                        <button type="button" id="btnLokasi" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs py-2 px-4 rounded-md transition border border-indigo-200">
                            📍 Gunakan Lokasi Saya
                        </button>
                        <div>
                            <x-camera-capture name="foto_bukti" label="Foto Bukti (Opsional)" facing="environment" hint="Buka kamera untuk memotret langsung kondisi di lapangan." />
                        </div>
                        <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white font-bold text-xs py-2 px-4 rounded-md transition shadow">
                            Kirim Pengaduan
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <x-warga-bottom-nav />

    <script>
        document.getElementById('btnLokasi').addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung geolokasi.');
                return;
            }
            this.textContent = '📡 Mendeteksi lokasi...';
            this.disabled = true;
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
                    document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
                    document.getElementById('btnLokasi').textContent = '✅ Lokasi terdeteksi';
                    document.getElementById('btnLokasi').disabled = false;
                },
                function () {
                    alert('Gagal mendapatkan lokasi. Silakan isi koordinat secara manual.');
                    document.getElementById('btnLokasi').textContent = '📍 Gunakan Lokasi Saya';
                    document.getElementById('btnLokasi').disabled = false;
                }
            );
        });
    </script>
</x-app-layout>
