<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 p-4 sm:p-6">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-6">

            <div>
                <h3 class="text-base font-bold text-gray-800">Ada Masalah di Jalur Tugas?</h3>
                <p class="text-xs text-gray-400 mt-0.5">Laporkan segera ke admin jika ada kendala armada, cuaca, atau hambatan jalan.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Laporan Kendala -->
            <form action="{{ route('petugas.laporan.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Jenis Kendala -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jenis Kendala</label>
                    <select name="tipe_kendala" required class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl px-3 py-2.5 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 font-medium">
                        <option value="" disabled selected>-- Pilih Jenis Masalah --</option>
                        <option value="Armada Rusak">Armada Rusak / Ban Bocor</option>
                        <option value="Jalan Ditutup">Akses Jalan Ditutup / Macet Total</option>
                        <option value="Cuaca Buruk">Cuaca Buruk / Banjir</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Deskripsi Detail -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi/Keterangan</label>
                    <textarea name="deskripsi" rows="4" required placeholder="Ceritakan detail kendala di lapangan agar admin bisa langsung merespons..." class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl px-3 py-2.5 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-sm py-3 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim Laporan Masalah
                </button>
            </form>

        </main>
    </div>

    <x-petugas-bottom-nav />
</x-app-layout>
