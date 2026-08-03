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

            <!-- PANEL SETTING PARAMETER TARIF & DENDA -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">⚙️ Pengaturan Parameter Tarif & Denda</h3>
                    <p class="text-sm text-gray-500">Atur besaran tarif iuran dasar dan nominal denda keterlambatan pembayaran.</p>
                </div>

                <form action="{{ route('admin.iuran.update-pengaturan') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="tarif_dasar_bulanan" value="Tarif Dasar Bulanan (Rp) *" />
                            <x-text-input id="tarif_dasar_bulanan" name="tarif_dasar_bulanan" type="number" class="mt-1 block w-full" value="{{ old('tarif_dasar_bulanan', $pengaturan->tarif_dasar_bulanan) }}" required />
                        </div>

                        <div>
                            <x-input-label for="tgl_jatuh_tempo" value="Tgl Jatuh Tempo (1-31) *" />
                            <x-text-input id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" type="number" min="1" max="31" class="mt-1 block w-full" value="{{ old('tgl_jatuh_tempo', $pengaturan->tgl_jatuh_tempo) }}" required />
                        </div>

                        <div>
                            <x-input-label for="nominal_denda_flat" value="Nominal Denda Flat (Rp) *" />
                            <x-text-input id="nominal_denda_flat" name="nominal_denda_flat" type="number" class="mt-1 block w-full" value="{{ old('nominal_denda_flat', $pengaturan->nominal_denda_flat) }}" required />
                        </div>

                        <div>
                            <x-input-label for="persentase_denda_per_bulan" value="Denda Persentase (%)" />
                            <x-text-input id="persentase_denda_per_bulan" name="persentase_denda_per_bulan" type="number" step="0.1" class="mt-1 block w-full" value="{{ old('persentase_denda_per_bulan', $pengaturan->persentase_denda_per_bulan) }}" />
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