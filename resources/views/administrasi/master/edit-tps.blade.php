<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit TPS: {{ $tps->nama_tps }}</h3>

                    <form method="POST" action="{{ route('administrasi.master.tps.update', $tps->id) }}">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama TPS</label>
                                <input type="text" name="nama_tps" value="{{ old('nama_tps', $tps->nama_tps) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lokasi Koordinat</label>
                                <input type="text" name="lokasi_koordinat" value="{{ old('lokasi_koordinat', $tps->lokasi_koordinat) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kapasitas Maksimal (m&sup3;)</label>
                                <input type="number" step="0.01" name="kapasitas_maksimal_m3" value="{{ old('kapasitas_maksimal_m3', $tps->kapasitas_maksimal_m3) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
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
