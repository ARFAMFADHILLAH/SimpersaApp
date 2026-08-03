<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- NOTIFIKASI SUCCESS -->
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-xl text-sm" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- NOTIFIKASI ERROR -->
            @if(session('error'))
                <div class="bg-rose-100 border border-rose-400 text-rose-800 px-4 py-3 rounded-xl text-sm" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- FORM TAMBAH WILAYAH -->
            <div class="p-6 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">Tambah Wilayah Pelayanan Baru</h3>
                
                <form action="{{ route('admin.wilayah.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nama_wilayah" value="Nama Wilayah *" />
                            <x-text-input id="nama_wilayah" name="nama_wilayah" type="text" class="mt-1 block w-full" :value="old('nama_wilayah')" required placeholder="Contoh: Wilayah Cakupan Barat / RW 05" />
                            <x-input-error :messages="$errors->get('nama_wilayah')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="cakupan_area" value="Cakupan Area / Keterangan" />
                            <x-text-input id="cakupan_area" name="cakupan_area" type="text" class="mt-1 block w-full" :value="old('cakupan_area')" placeholder="Contoh: Meliputi RT 01 s/d RT 08 Kelurahan Mekar" />
                            <x-input-error :messages="$errors->get('cakupan_area')" class="mt-1" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Simpan Wilayah') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- TABEL DATA WILAYAH -->
            <div class="p-6 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">Daftar Wilayah Pelayanan</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Wilayah</th>
                                <th class="p-3">Cakupan Area</th>
                                <th class="p-3 text-center">Jumlah Pelanggan</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($wilayahs as $index => $w)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-semibold text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3 font-bold text-gray-900">{{ $w->nama_wilayah }}</td>
                                    <td class="p-3 text-xs text-gray-600">
                                        {{ $w->cakupan_area ?? '-' }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="inline-block bg-indigo-50 text-indigo-700 border border-indigo-200 font-semibold px-2.5 py-1 rounded-full text-xs">
                                            {{ $w->pelanggan_count }} Pelanggan
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.wilayah.edit', $w->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.wilayah.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus wilayah ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-400">Belum ada data wilayah pelayanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>