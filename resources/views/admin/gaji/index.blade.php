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
                    <p class="text-sm text-gray-500">Atur besaran gaji pokok, insentif kehadiran, bonus, dan potongan absensi. Bendahara hanya tinggal memproses gaji otomatis memakai nilai ini.</p>
                </div>

                <form action="{{ route('admin.gaji.update-pengaturan') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="gaji_pokok" value="Gaji Pokok (Rp) *" />
                            <x-text-input id="gaji_pokok" name="gaji_pokok" type="number" min="0" class="mt-1 block w-full" value="{{ old('gaji_pokok', $pengaturan->gaji_pokok) }}" required />
                            <p class="text-xs text-gray-400 mt-1">Dibayarkan penuh setiap bulan.</p>
                        </div>

                        <div>
                            <x-input-label for="insentif_per_hadir" value="Insentif Per Kehadiran (Rp) *" />
                            <x-text-input id="insentif_per_hadir" name="insentif_per_hadir" type="number" min="0" class="mt-1 block w-full" value="{{ old('insentif_per_hadir', $pengaturan->insentif_per_hadir) }}" required />
                            <p class="text-xs text-gray-400 mt-1">Dikalikan jumlah hari hadir.</p>
                        </div>

                        <div>
                            <x-input-label for="bonus_amount" value="Bonus Kehadiran (Rp) *" />
                            <x-text-input id="bonus_amount" name="bonus_amount" type="number" min="0" class="mt-1 block w-full" value="{{ old('bonus_amount', $pengaturan->bonus_amount) }}" required />
                        </div>

                        <div>
                            <x-input-label for="minimal_hadir_bonus" value="Minimal Hadir untuk Bonus (hari) *" />
                            <x-text-input id="minimal_hadir_bonus" name="minimal_hadir_bonus" type="number" min="0" class="mt-1 block w-full" value="{{ old('minimal_hadir_bonus', $pengaturan->minimal_hadir_bonus) }}" required />
                            <p class="text-xs text-gray-400 mt-1">Bonus diberikan jika hadir ≥ jumlah ini.</p>
                        </div>

                        <div>
                            <x-input-label for="potongan_alpha_per_hari" value="Potongan Alpha / Hari (Rp) *" />
                            <x-text-input id="potongan_alpha_per_hari" name="potongan_alpha_per_hari" type="number" min="0" class="mt-1 block w-full" value="{{ old('potongan_alpha_per_hari', $pengaturan->potongan_alpha_per_hari) }}" required />
                        </div>

                        <div>
                            <x-input-label for="potongan_izin_per_hari" value="Potongan Izin / Hari (Rp) *" />
                            <x-text-input id="potongan_izin_per_hari" name="potongan_izin_per_hari" type="number" min="0" class="mt-1 block w-full" value="{{ old('potongan_izin_per_hari', $pengaturan->potongan_izin_per_hari) }}" required />
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
