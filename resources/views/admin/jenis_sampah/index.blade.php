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

            <!-- Form Tambah Data -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Jenis Sampah Baru</h3>
                <form action="{{ route('admin.jenis-sampah.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nama_jenis" value="Nama Jenis Sampah (Contoh: Sampah Organik Sisa Makanan, Plastik) *" />
                            <x-text-input id="nama_jenis" name="nama_jenis" type="text" class="mt-1 block w-full" value="{{ old('nama_jenis') }}" required />
                        </div>
                        <div>
                            <x-input-label for="kategori_sampah_id" value="Kategori Sampah *" />
                            <select id="kategori_sampah_id" name="kategori_sampah_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($dataKategori as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_sampah_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tarif_per_kg" value="Harga Beli dari Warga per Kg (Rp) *" />
                            <x-text-input id="tarif_per_kg" name="tarif_per_kg" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('tarif_per_kg', 0) }}" required />
                            <p class="mt-1 text-xs text-gray-400">Dibayarkan ke warga (dikredit ke saldo tabungan).</p>
                        </div>
                        <div>
                            <x-input-label for="tarif_jual_per_kg" value="Harga Jual ke Pengepul per Kg (Rp) *" />
                            <x-text-input id="tarif_jual_per_kg" name="tarif_jual_per_kg" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('tarif_jual_per_kg', 0) }}" required />
                            <p class="mt-1 text-xs text-gray-400">Dipakai sebagai patokan pencatatan penjualan ke pengepul.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Simpan Data') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Tabel Menampilkan Data -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Jenis Sampah & Tarif saat ini</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">No</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Jenis Sampah</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Kategori</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Harga Beli / Kg</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Harga Jual / Kg</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataSampah as $key => $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-700">{{ $key + 1 }}</td>
                                    <td class="p-3 text-sm text-gray-900 font-medium">{{ $item->nama_jenis }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $item->kategoriSampah->nama_kategori ?? '-' }}</td>
                                    <td class="p-3 text-sm text-gray-700">Rp {{ number_format($item->tarif_per_kg, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm text-gray-700">Rp {{ number_format($item->tarif_jual_per_kg ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-3 text-sm text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <button type="button"
                                                @click="openEditModal = true; editData = {
                                                    id: '{{ $item->id }}',
                                                    nama_jenis: '{{ $item->nama_jenis }}',
                                                    kategori_sampah_id: '{{ $item->kategori_sampah_id }}',
                                                    tarif_per_kg: '{{ $item->tarif_per_kg }}',
                                                    tarif_jual_per_kg: '{{ $item->tarif_jual_per_kg }}'
                                                }"
                                                class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.jenis-sampah.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis sampah ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-sm text-gray-500">Belum ada data jenis sampah. Silakan tambahkan lewat form di atas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MODAL POPUP EDIT DATA -->
        <div x-show="openEditModal"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
             x-cloak
             x-transition>
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 space-y-4" @click.away="openEditModal = false">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Edit Jenis Sampah & Tarif</h3>
                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <form :action="'/admin/jenis-sampah/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="edit_nama_jenis" value="Nama Jenis Sampah *" />
                        <x-text-input id="edit_nama_jenis" name="nama_jenis" type="text" class="mt-1 block w-full" x-model="editData.nama_jenis" required />
                    </div>

                    <div>
                        <x-input-label for="edit_kategori_sampah_id" value="Kategori Sampah *" />
                        <select id="edit_kategori_sampah_id" name="kategori_sampah_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @foreach($dataKategori as $kategori)
                                <option value="{{ $kategori->id }}" x-bind:selected="editData.kategori_sampah_id == '{{ $kategori->id }}'">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="edit_tarif_per_kg" value="Harga Beli dari Warga per Kg (Rp) *" />
                        <x-text-input id="edit_tarif_per_kg" name="tarif_per_kg" type="number" step="0.01" class="mt-1 block w-full" x-model="editData.tarif_per_kg" required />
                    </div>

                    <div>
                        <x-input-label for="edit_tarif_jual_per_kg" value="Harga Jual ke Pengepul per Kg (Rp) *" />
                        <x-text-input id="edit_tarif_jual_per_kg" name="tarif_jual_per_kg" type="number" step="0.01" class="mt-1 block w-full" x-model="editData.tarif_jual_per_kg" required />
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