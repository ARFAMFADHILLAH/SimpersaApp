<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form mengarah ke route update pengangkutan -->
            <form action="{{ route('petugas.pengangkutan.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input ID Tugas Pengangkutan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ID / Nomor Tugas Pengangkutan</label>
                    <input type="number" name="pengangkutan_id" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: 1, 2, dst">
                </div>

                <!-- Input Volume (m3) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Volume Aktual (m³)</label>
                    <input type="number" step="0.01" name="volume_m3" value="0.00" class="w-full text-sm border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Input Berat (Kg) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Berat Aktual (Kg)</label>
                    <input type="number" step="0.01" name="berat_kg" value="0.00" class="w-full text-sm border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Status Tugas -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Tugas</label>
                    <select name="status_tugas" class="w-full text-sm border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="Belum dikerjakan">Belum dikerjakan</option>
                        <option value="Sedang dikerjakan">Sedang dikerjakan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>

                <!-- Catatan Lapangan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Lapangan (Opsional)</label>
                    <textarea name="catatan" rows="3" class="w-full text-sm border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500" placeholder="Keterangan tambahan kendala..."></textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-xl text-sm transition shadow-sm">
                        Simpan Data Pengangkutan
                    </button>
                </div>
            </form>

        </div>
    </main>
    <x-petugas-bottom-nav />
</x-app-layout>