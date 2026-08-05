<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Armada: {{ $armada->nama_kendaraan }}</h3>

                    <form method="POST" action="{{ route('admin.master.armada.update', $armada->id) }}">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Kendaraan</label>
                                <input type="text" name="nama_kendaraan" value="{{ old('nama_kendaraan', $armada->nama_kendaraan) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Plat</label>
                                <input type="text" name="nomor_plat" value="{{ old('nomor_plat', $armada->nomor_plat) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                                <input type="text" name="jenis_kendaraan" value="{{ old('jenis_kendaraan', $armada->jenis_kendaraan) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Kondisi</label>
                                <select name="status_kondisi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                    <option value="aktif" {{ $armada->status_kondisi == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="rusak" {{ $armada->status_kondisi == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="servis" {{ $armada->status_kondisi == 'servis' ? 'selected' : '' }}>Servis</option>
                                </select>
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
