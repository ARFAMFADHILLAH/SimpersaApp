<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Detail Warga</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500">No Warga</dt><dd class="font-medium">{{ $warga->no_warga }}</dd></div>
                        <div><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $warga->user?->name ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Email</dt><dd>{{ $warga->user?->email ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">No HP</dt><dd>{{ $warga->no_hp ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Saldo Tabungan</dt><dd class="font-semibold">Rp {{ number_format($warga->saldo_tabungan ?? 0, 0, ',', '.') }}</dd></div>
                        <div><dt class="text-gray-500">Alamat</dt><dd class="col-span-2">{{ $warga->alamat_lengkap ?? '-' }}</dd></div>
                    </dl>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
