<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- PANEL PENGATURAN PARAMETER GAJI -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">⚙️ Pengaturan Parameter Penggajian</h3>
                    <p class="text-sm text-gray-500">Atur gaji pokok petugas. Total gaji = Gaji Pokok + Bonus/Insentif (diinput Bendahara saat pembayaran).</p>
                </div>

                <form action="{{ route('admin.gaji.update-pengaturan') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="gaji_pokok" value="Gaji Pokok (Rp) *" />
                            <x-text-input id="gaji_pokok" name="gaji_pokok" type="number" min="0" class="mt-1 block w-full" value="{{ old('gaji_pokok', $pengaturan->gaji_pokok) }}" required />
                            <p class="text-xs text-gray-400 mt-1">Dibayarkan penuh setiap bulan.</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                            {{ __('Simpan Parameter') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
