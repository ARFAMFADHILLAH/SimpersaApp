<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-pelanggan-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="font-bold text-gray-800 text-sm border-b pb-3 mb-4">Profil Pelanggan</h4>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500 text-xs">No Pelanggan</dt><dd class="font-semibold">{{ $pelanggan->no_pelanggan }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">Nama</dt><dd class="font-semibold">{{ Auth::user()->name }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">Email</dt><dd>{{ Auth::user()->email }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">No HP</dt><dd>{{ $pelanggan->no_hp ?? '-' }}</dd></div>
                        <div class="md:col-span-2"><dt class="text-gray-500 text-xs">Alamat</dt><dd>{{ $pelanggan->alamat_lengkap ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">Rute</dt><dd>{{ $pelanggan->rute?->nama_rute ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">Wilayah</dt><dd>{{ $pelanggan->wilayahPelayanan?->nama_wilayah ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">Latitude</dt><dd class="font-mono">{{ $pelanggan->latitude ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500 text-xs">Longitude</dt><dd class="font-mono">{{ $pelanggan->longitude ?? '-' }}</dd></div>
                    </dl>
                </div>

                <div class="text-right">
                    <a href="{{ route('pelanggan.profile.riwayat') }}" class="text-sm text-indigo-600 hover:underline">Lihat Riwayat Pengangkutan &rarr;</a>
                </div>
            </div>
        </main>
    </div>
    <x-pelanggan-bottom-nav />
</x-app-layout>
