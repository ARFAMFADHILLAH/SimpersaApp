<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('admin.armada.update', $armada->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kendaraan</label>
                    <input type="text" name="nama_kendaraan" value="{{ old('nama_kendaraan', $armada->nama_kendaraan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" required>
                    @error('nama_kendaraan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" name="nomor_plat" value="{{ old('nomor_plat', $armada->nomor_plat) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm uppercase" required>
                        @error('nomor_plat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Field Jenis Kendaraan -->
<div>
    <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
    <!-- GANTI name="jenis" MENJADI name="jenis_kendaraan" -->
    <select name="jenis_kendaraan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" required>
        <option value="Mobil" {{ old('jenis_kendaraan', $armada->jenis_kendaraan) == 'Mobil' ? 'selected' : '' }}>Mobil</option>
        <option value="Truk" {{ old('jenis_kendaraan', $armada->jenis_kendaraan) == 'Truk' ? 'selected' : '' }}>Truk</option>
        <option value="Bus" {{ old('jenis_kendaraan', $armada->jenis_kendaraan) == 'Bus' ? 'selected' : '' }}>Bus</option>
        <option value="Motor" {{ old('jenis_kendaraan', $armada->jenis_kendaraan) == 'Motor' ? 'selected' : '' }}>Motor</option>
    </select>
</div>

                <!-- Field Kapasitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kapasitas (m³)</label>
                    <input type="number" name="kapasitas_m3" value="{{ old('kapasitas_m3', $armada->kapasitas_m3) }}" placeholder="Contoh: 15" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                    @error('kapasitas_m3') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Field Status Kondisi -->
<div>
    <label class="block text-sm font-medium text-gray-700">Status Kondisi</label>
    <select name="status_kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" required>
        <option value="aktif" {{ old('status_kondisi', $armada->status_kondisi) == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="perawatan" {{ old('status_kondisi', $armada->status_kondisi) == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
        <option value="nonaktif" {{ old('status_kondisi', $armada->status_kondisi) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
    </select>
</div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.armada.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition">Perbarui Armada</button>
                </div>
            </form>
        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>