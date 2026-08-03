<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

            <!-- Informasi Rute -->
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
                            Jadwal: {{ $rute->hari_angkut }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mt-2">{{ $rute->nama_rute }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $rute->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
                    </div>
                    <a href="{{ route('petugas.rute.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Daftar Pelanggan / Titik di Rute Ini -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-md font-bold text-gray-900">Daftar Pelanggan pada Rute Ini</h3>
                    <p class="text-xs text-gray-500">Titik-titik tujuan pengangkutan sampah.</p>
                </div>

            <!-- Form Upload Foto Dokumentasi (Sebelum & Sesudah) -->
<div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 space-y-4">
    <h3 class="text-md font-bold text-gray-900">Upload Dokumentasi Pengangkutan</h3>
    <p class="text-xs text-gray-500">Unggah foto kondisi di lapangan sebelum dan sesudah proses pengangkutan sampah.</p>

<form action="{{ route('petugas.rute.upload', $rute->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Foto Sebelum -->
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
            <label class="block text-xs font-bold text-gray-700 mb-1">Foto Sebelum Pengangkutan</label>
            <input type="file" name="foto_sebelum" accept="image/*" required class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-gray-200 rounded-xl cursor-pointer">
        </div>

        <!-- Foto Sesudah -->
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
            <label class="block text-xs font-bold text-gray-700 mb-1">Foto Sesudah Pengangkutan</label>
            <input type="file" name="foto_sesudah" accept="image/*" required class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-xl cursor-pointer">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / Keterangan Lapangan</label>
        <textarea name="catatan" rows="2" class="w-full text-xs border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tuliskan kendala atau catatan kondisi area di sini..."></textarea>
    </div>

    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow transition">
        Simpan & Unggah Dokumentasi
    </button>
</form>
</div>

<div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 space-y-4 mt-6">
    <h3 class="text-md font-bold text-gray-900">Hasil Dokumentasi Pengangkutan</h3>
    
                @php
                    $firstPelanggan = $rute->pelanggan->first();
                    $pengangkutan = $firstPelanggan ? \DB::table('pengangkutan')->where('pelanggan_id', $firstPelanggan->id)->first() : null;
                @endphp

    @if($pengangkutan && ($pengangkutan->foto_sebelum || $pengangkutan->foto_sesudah))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Foto Sebelum -->
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">Foto Sebelum</span>
                @if($pengangkutan->foto_sebelum)
                    <div class="mt-2">
                        <img src="{{ asset('storage/dokumentasi/' . $pengangkutan->foto_sebelum) }}" alt="Foto Sebelum" class="w-full h-48 object-cover rounded-xl border border-gray-200 shadow-sm">
                    </div>
                @else
                    <p class="text-xs text-gray-400 mt-2">Belum ada foto.</p>
                @endif
            </div>

            <!-- Foto Sesudah -->
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">Foto Sesudah</span>
                @if($pengangkutan->foto_sesudah)
                    <div class="mt-2">
                        <img src="{{ asset('storage/dokumentasi/' . $pengangkutan->foto_sesudah) }}" alt="Foto Sesudah" class="w-full h-48 object-cover rounded-xl border border-gray-200 shadow-sm">
                    </div>
                @else
                    <p class="text-xs text-gray-400 mt-2">Belum ada foto.</p>
                @endif
            </div>
        </div>

        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 mt-4">
            <span class="text-xs font-bold text-gray-700">Catatan Lapangan:</span>
            <p class="text-xs text-gray-600 mt-1">{{ $pengangkutan->catatan ?? 'Tidak ada catatan.' }}</p>
        </div>
    @else
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs text-gray-400">Belum ada dokumentasi foto yang diunggah untuk titik ini.</p>
        </div>
    @endif
</div>

<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                <th class="p-4 font-bold">No</th>
                <th class="p-4 font-bold">Nama Pelanggan</th>
                <th class="p-4 font-bold">Alamat</th>
                <th class="p-4 font-bold text-center">Status & Dokumentasi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($rute->pelanggan ?? [] as $index => $pelanggan)
                @php
                    // Ambil data pengangkutan yang sesuai dengan pelanggan ini
                    $pengangkutan = \DB::table('pengangkutan')->where('pelanggan_id', $pelanggan->id)->first();
                @endphp
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-xs text-gray-500">{{ $index + 1 }}</td>
                    <td class="p-4 font-medium text-gray-900">{{ $pelanggan->user->name ?? 'Warga' }}</td>
                    <td class="p-4 text-xs text-gray-600">{{ $pelanggan->alamat_lengkap ?? '-' }}</td>
                    <td class="p-4 text-center space-y-2">
                        @if($pengangkutan && $pengangkutan->status_tugas == 'Selesai')
                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full inline-block">
                                Selesai
                            </span>
                            
                            <!-- Preview Thumbnail Foto Jika Ada -->
                            @if($pengangkutan->foto_sebelum || $pengangkutan->foto_sesudah)
                                <div class="flex justify-center gap-2 mt-2">
                                    @if($pengangkutan->foto_sebelum)
                                        <a href="{{ asset('storage/dokumentasi/' . $pengangkutan->foto_sebelum) }}" target="_blank" title="Lihat Foto Sebelum">
                                            <img src="{{ asset('storage/dokumentasi/' . $pengangkutan->foto_sebelum) }}" class="w-10 h-10 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
                                        </a>
                                    @endif
                                    @if($pengangkutan->foto_sesudah)
                                        <a href="{{ asset('storage/dokumentasi/' . $pengangkutan->foto_sesudah) }}" target="_blank" title="Lihat Foto Sesudah">
                                            <img src="{{ asset('storage/dokumentasi/' . $pengangkutan->foto_sesudah) }}" class="w-10 h-10 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
                                        </a>
                                    @endif
                                </div>
                                @if($pengangkutan->catatan)
                                    <p class="text-[10px] text-gray-500 italic mt-1">Catatan: "{{ $pengangkutan->catatan }}"</p>
                                @endif
                            @endif
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full inline-block">
                                Belum Diangkut
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400 text-sm">
                        Belum ada data pelanggan yang terdaftar pada rute ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
            </div>

        </div>
    </main>
    <x-petugas-bottom-nav />
</x-app-layout>