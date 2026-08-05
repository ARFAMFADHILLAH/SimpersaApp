<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Warga: {{ $warga->user?->name }}</h3>

                    <form method="POST" action="{{ route('admin.master.warga.update', $warga->id) }}">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp', $warga->no_hp) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">{{ old('alamat_lengkap', $warga->alamat_lengkap) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lokasi Rumah Warga</label>
                                <x-lokasi-picker
                                    map-id="mapWargaEdit"
                                    address-input-id="alamat_lengkap"
                                    :initial-lat="$warga->latitude"
                                    :initial-lng="$warga->longitude"
                                    hint="Klik pada peta untuk menandai lokasi rumah, atau ketik alamat lalu tekan &quot;Cari dari Alamat&quot;."
                                />
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Simpan</button>
                            <a href="{{ route('admin.master.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
