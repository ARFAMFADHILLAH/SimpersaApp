<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">✏️ Edit Data Wilayah</h3>

                <form action="{{ route('admin.wilayah.update', $wilayah->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nama_wilayah" value="Nama Wilayah *" />
                        <x-text-input id="nama_wilayah" name="nama_wilayah" type="text" class="mt-1 block w-full" :value="old('nama_wilayah', $wilayah->nama_wilayah)" required />
                        <x-input-error :messages="$errors->get('nama_wilayah')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="cakupan_area" value="Cakupan Area / Keterangan" />
                        <x-text-input id="cakupan_area" name="cakupan_area" type="text" class="mt-1 block w-full" :value="old('cakupan_area', $wilayah->cakupan_area)" />
                        <x-input-error :messages="$errors->get('cakupan_area')" class="mt-1" />
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                        <a href="{{ route('admin.wilayah.index') }}" class="text-sm text-gray-600 hover:underline">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>