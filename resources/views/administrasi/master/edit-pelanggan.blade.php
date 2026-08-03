<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Pelanggan: {{ $pelanggan->user?->name }}</h3>

                    <form method="POST" action="{{ route('administrasi.master.pelanggan.update', $pelanggan->id) }}">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">{{ old('alamat_lengkap', $pelanggan->alamat_lengkap) }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input type="text" name="latitude" value="{{ old('latitude', $pelanggan->latitude) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input type="text" name="longitude" value="{{ old('longitude', $pelanggan->longitude) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Simpan</button>
                            <a href="{{ route('administrasi.master.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
