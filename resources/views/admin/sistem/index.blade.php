<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- KOTAK NOTIFIKASI PENGINGAT (MODUL 13) -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">📢</span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Penyiaran Notifikasi Penagihan Bulanan</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Kirim pesan peringatan otomatis kepada pelanggan yang status tagihan iurannya masih "Belum Bayar".</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t flex items-center justify-between">
                    <span class="text-sm font-semibold text-amber-700 bg-amber-50 px-3 py-1 rounded">
                        ⚠️ Terdeteksi: {{ $totalTunggakan }} Tagihan Tertunggak
                    </span>
                    <form action="{{ route('admin.sistem.pengingat') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm px-4 py-2 rounded shadow transition-colors" {{ $totalTunggakan == 0 ? 'disabled' : '' }}>
                            Kirim Notifikasi Massal
                        </button>
                    </form>
                </div>
            </div>

            <!-- KOTAK KEAMANAN DATA & BACKUP (MODUL 14) -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">💾</span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pusat Cadangan Basis Data (Database Backup)</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Unduh berkas cadangan berekstensi .sql secara langsung untuk mengamankan data iuran, log pengangkutan, dan rute pelanggan.</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t flex justify-end">
                    <a href="{{ route('admin.sistem.backup') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-4 py-2 rounded shadow transition-colors">
                        Download File SQL Sekarang
                    </a>
                </div>
            </div>

        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
