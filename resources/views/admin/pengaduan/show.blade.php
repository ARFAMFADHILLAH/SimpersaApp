<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto space-y-6">

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengaduan #{{ $pengaduan->id }}</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500">Warga</dt><dd class="font-medium">{{ $pengaduan->warga?->user?->name ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Tipe Kendala</dt><dd>{{ $pengaduan->tipe_kendala }}</dd></div>
                        <div><dt class="text-gray-500">Status</dt>
                            <dd>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded
                                    {{ $pengaduan->status_respon == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $pengaduan->status_respon == 'Sedang Dikerjakan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $pengaduan->status_respon == 'Belum Dikerjakan' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $pengaduan->status_respon }}
                                </span>
                            </dd>
                        </div>
                        <div><dt class="text-gray-500">Petugas Ditugaskan</dt><dd>{{ $pengaduan->petugas?->name ?? '-' }}</dd></div>
                        <div class="col-span-2"><dt class="text-gray-500">Lokasi</dt><dd>{{ $pengaduan->catatan_lokasi ?? '-' }}</dd></div>
                        @if($pengaduan->latitude && $pengaduan->longitude)
                            <div class="col-span-2">
                                <dt class="text-gray-500">Titik Koordinat</dt>
                                <dd>
                                    <span class="font-mono">{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}</span>
                                    <a href="https://maps.google.com/?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}" target="_blank" class="ml-2 text-indigo-600 hover:underline text-xs font-semibold">
                                        &#128506; Buka di Google Maps
                                    </a>
                                </dd>
                            </div>
                        @endif
                        <div class="col-span-2"><dt class="text-gray-500">Catatan Petugas</dt><dd>{{ $pengaduan->catatan_petugas ?? '-' }}</dd></div>
                        <div class="col-span-2"><dt class="text-gray-500">Dibuat</dt><dd>{{ $pengaduan->created_at->format('d/m/Y H:i') }}</dd></div>
                    </dl>

                    @if($pengaduan->foto_bukti)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-2">Foto Bukti:</p>
                            <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}" class="max-w-sm rounded-lg border">
                        </div>
                    @endif
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif

                @if($pengaduan->status_respon != 'Selesai')
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Verifikasi Pengaduan</h4>
                        <form method="POST" action="{{ route('admin.pengaduan.verifikasi', $pengaduan->id) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan Verifikasi</label>
                                    <textarea name="catatan_verifikasi" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"></textarea>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Verifikasi & Proses</button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Dispatch ke Petugas Lapangan</h4>
                        <form method="POST" action="{{ route('admin.pengaduan.dispatch', $pengaduan->id) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tugaskan ke Petugas</label>
                                    <select name="petugas_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                        <option value="">Pilih Petugas</option>
                                        @foreach($petugasLapangan as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan Dispatch</label>
                                    <textarea name="catatan_dispatch" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"></textarea>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Dispatch ke Petugas</button>
                            </div>
                        </form>
                    </div>
                @endif

                <a href="{{ route('admin.pengaduan.index') }}" class="text-cyan-600 hover:underline text-sm">&larr; Kembali</a>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>