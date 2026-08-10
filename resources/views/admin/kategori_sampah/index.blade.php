<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8" x-data="{ openEditModal: false, editData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Tambah Kategori -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Kategori Sampah Baru</h3>
                <form action="{{ route('admin.kategori-sampah.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="nama_kategori" value="Nama Kategori (Contoh: Organik, Non-Organik, B3) *" />
                        <x-text-input id="nama_kategori" name="nama_kategori" type="text" class="mt-1 block w-full" value="{{ old('nama_kategori') }}" required />
                    </div>
                    <div>
                        <x-input-label for="keterangan" value="Keterangan (Opsional)" />
                        <x-text-input id="keterangan" name="keterangan" type="text" class="mt-1 block w-full" value="{{ old('keterangan') }}" />
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Simpan Data') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Tabel Kategori -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Kategori Sampah</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">No</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Kategori</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Keterangan</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-center">Jumlah Jenis</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataKategori as $key => $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-700">{{ $key + 1 }}</td>
                                    <td class="p-3 text-sm text-gray-900 font-medium">{{ $item->nama_kategori }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="p-3 text-sm text-gray-700 text-center">
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">{{ $item->jenis_sampah_count }} jenis</span>
                                    </td>
                                    <td class="p-3 text-sm text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <button type="button"
                                                @click="openEditModal = true; editData = {
                                                    id: '{{ $item->id }}',
                                                    nama_kategori: '{{ $item->nama_kategori }}',
                                                    keterangan: '{{ $item->keterangan }}'
                                                }"
                                                class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.kategori-sampah.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada kategori sampah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MODAL EDIT -->
        <div x-show="openEditModal"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
             x-cloak
             x-transition>
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 space-y-4" @click.away="openEditModal = false">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Edit Kategori Sampah</h3>
                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <form :action="'/admin/kategori-sampah/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="edit_nama_kategori" value="Nama Kategori *" />
                        <x-text-input id="edit_nama_kategori" name="nama_kategori" type="text" class="mt-1 block w-full" x-model="editData.nama_kategori" required />
                    </div>

                    <div>
                        <x-input-label for="edit_keterangan" value="Keterangan (Opsional)" />
                        <x-text-input id="edit_keterangan" name="keterangan" type="text" class="mt-1 block w-full" x-model="editData.keterangan" />
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">
                            Batal
                        </button>
                        <x-primary-button>
                            {{ __('Update Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>