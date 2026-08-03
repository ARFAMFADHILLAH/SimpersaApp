<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-administrasi-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Catat Pengeluaran Operasional Armada</h3>

                    <form method="POST" action="{{ route('administrasi.logistik.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Armada</label>
                                <select name="armada_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                    <option value="">Pilih Armada</option>
                                    @foreach($armada as $a)
                                        <option value="{{ $a->id }}">{{ $a->nama_kendaraan }} ({{ $a->nomor_plat }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                                <input type="date" name="tanggal_pengeluaran" required value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori Biaya</label>
                                <select name="kategori_biaya" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                    <option value="">Pilih Kategori</option>
                                    <option value="BBM">BBM</option>
                                    <option value="Servis">Servis</option>
                                    <option value="Ban">Ban</option>
                                    <option value="Alat">Alat</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Biaya (Rp)</label>
                                <input type="number" name="jumlah_biaya" required min="0" value="{{ old('jumlah_biaya') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea name="keterangan" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">{{ old('keterangan') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bukti Foto (opsional)</label>
                                <input type="file" name="bukti_foto" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Simpan</button>
                            <a href="{{ route('administrasi.logistik.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <x-administrasi-bottom-nav />
</x-app-layout>
