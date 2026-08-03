<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto space-y-6">

            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pengaduan #{{ $pengaduan->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                        {{ $pengaduan->status_respon == 'Selesai' ? 'bg-green-100 text-green-800' : ($pengaduan->status_respon == 'Sedang Dikerjakan' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $pengaduan->status_respon }}
                    </span>
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Pelapor</span>
                        <p class="text-sm font-medium text-gray-900">{{ $pengaduan->pelanggan->user->name ?? 'Warga' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Tipe Kendala</span>
                        <p class="text-sm font-medium text-gray-900">{{ $pengaduan->tipe_kendala }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Lokasi</span>
                        <p class="text-sm font-medium text-gray-900">{{ $pengaduan->catatan_lokasi ?? '-' }}</p>
                        @if($pengaduan->latitude && $pengaduan->longitude)
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                <span class="font-mono">{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}</span>
                                <a href="https://maps.google.com/?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}" target="_blank" class="ml-2 text-emerald-600 hover:underline text-xs font-semibold">
                                    &#128506; Buka di Google Maps
                                </a>
                            </p>
                        @endif
                    </div>
                    @if($pengaduan->foto_bukti)
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Foto Bukti</span>
                        <img src="{{ asset('storage/pengaduan/' . $pengaduan->foto_bukti) }}" class="mt-1 w-full max-w-md rounded-lg border">
                    </div>
                    @endif
                    @if($pengaduan->catatan_petugas)
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Catatan Petugas</span>
                        <p class="text-sm text-gray-700 italic">"{{ $pengaduan->catatan_petugas }}"</p>
                    </div>
                    @endif
                </div>

                <form action="{{ route('petugas.pengaduan.update', $pengaduan->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4 border-t border-gray-200 pt-4">
                    @csrf
                    <div>
                        <x-input-label for="status_respon" value="Update Status Penanganan" />
                        <select id="status_respon" name="status_respon" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="Belum Dikerjakan" {{ $pengaduan->status_respon == 'Belum Dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                            <option value="Sedang Dikerjakan" {{ $pengaduan->status_respon == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                            <option value="Selesai" {{ $pengaduan->status_respon == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="catatan_petugas" value="Catatan Petugas" />
                        <textarea id="catatan_petugas" name="catatan_petugas" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Laporan hasil penanganan...">{{ $pengaduan->catatan_petugas }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="foto_penyelesaian" value="Foto Penyelesaian (Opsional)" />
                        <input type="file" id="foto_penyelesaian" name="foto_penyelesaian" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('petugas.pengaduan.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">Kembali</a>
                        <x-primary-button>Simpan Update</x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </main>
    <x-petugas-bottom-nav />
</x-app-layout>
