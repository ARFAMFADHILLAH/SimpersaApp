<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- ALERT ERROR JIKA VALIDASI GAGAL -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.armada.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kendaraan *</label>
                    <input type="text" name="nama_kendaraan" value="{{ old('nama_kendaraan') }}" placeholder="Contoh: Toyota Avanza" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('nama_kendaraan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- SESUAIKAN: name="nomor_plat" -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plat Nomor *</label>
                        <input type="text" name="nomor_plat" value="{{ old('nomor_plat') }}" placeholder="B 1234 ABC" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm uppercase" required>
                        @error('nomor_plat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- SESUAIKAN: name="jenis_kendaraan" -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan *</label>
                        <select name="jenis_kendaraan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Mobil" {{ old('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                            <option value="Bus" {{ old('jenis_kendaraan') == 'Bus' ? 'selected' : '' }}>Bus</option>
                            <option value="Truk" {{ old('jenis_kendaraan') == 'Truk' ? 'selected' : '' }}>Truk</option>
                            <option value="Motor" {{ old('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option>
                        </select>
                        @error('jenis_kendaraan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kapasitas (m³)</label>
                        <input type="number" name="kapasitas_m3" value="{{ old('kapasitas_m3') }}" placeholder="Contoh: 15" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('kapasitas_m3') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Kondisi *</label>
                        <select name="status_kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="aktif" {{ old('status_kondisi') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="rusak" {{ old('status_kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="servis" {{ old('status_kondisi') == 'servis' ? 'selected' : '' }}>Servis</option>
                        </select>
                        @error('status_kondisi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.armada.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">Simpan Kendaraan</button>
                </div>
            </form>
        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>