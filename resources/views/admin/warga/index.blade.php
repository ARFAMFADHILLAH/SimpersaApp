<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- NOTIFIKASI -->
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-xl text-sm" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- FORM PENDAFTARAN -->
            <div class="p-6 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">Registrasi Warga Baru</h3>
                
                <form action="{{ route('admin.warga.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="name" value="Nama Lengkap *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required placeholder="Contoh: Ahmad Subagja" />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email *" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required placeholder="ahmad@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="no_hp" value="Nomor HP / WhatsApp *" />
                            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp')" required placeholder="081234567890" />
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-1" />
                        </div>

                    
                        <div>
                            <x-input-label for="wilayah_pelayanan_id" value="Wilayah Pelayanan *" />
                            <select id="wilayah_pelayanan_id" name="wilayah_pelayanan_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="">-- Pilih Wilayah Pelayanan --</option>
                                @foreach($wilayahs as $wil)
                                    <option value="{{ $wil->id }}" {{ old('wilayah_pelayanan_id') == $wil->id ? 'selected' : '' }}>
                                        {{ $wil->nama_wilayah }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('wilayah_pelayanan_id')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="rute_id" value="Rute *" />
                            <select id="rute_id" name="rute_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="">-- Pilih Rute --</option>
                                @foreach(\App\Models\Rute::all() as $rute)
                                    <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                        {{ $rute->nama_rute }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rute_id')" class="mt-1" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="alamat_lengkap" value="Alamat Lengkap Rumah *" />
                        <textarea id="alamat_lengkap" name="alamat_lengkap" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required placeholder="Jl. Mawar No. 12, RT 01/02">{{ old('alamat_lengkap') }}</textarea>
                        <x-input-error :messages="$errors->get('alamat_lengkap')" class="mt-1" />
                    </div>

                    <!-- PETA & KOORDINAT LOKASI (otomatis dari alamat lengkap / klik peta / lokasi saya) -->
                    <div class="border-t pt-4 mt-2">
                        <x-lokasi-picker map-id="mapWargaIndex" address-input-id="alamat_lengkap" hint="Titik koordinat otomatis muncul dari alamat lengkap yang diisi, dari klik pada peta, atau dari lokasi Anda." />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Daftarkan Warga') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- TABEL DATA WARGA -->
            <div class="p-6 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">📋 Daftar Warga Terregistrasi</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                                <th class="p-3">No Warga</th>
                                <th class="p-3">Nama</th>
                                <th class="p-3">Kontak / Email</th>
                                <th class="p-3">Wilayah</th>
                                <th class="p-3">Alamat</th>
                                <th class="p-3">Koordinat GPS</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($dataWarga as $plg)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-bold text-indigo-600 font-mono">{{ $plg->no_warga }}</td>
                                    <td class="p-3 font-semibold text-gray-900">{{ $plg->user->name ?? '-' }}</td>
                                    <td class="p-3 text-xs">
                                        <div class="font-medium text-gray-900">{{ $plg->no_hp }}</div>
                                        <div class="text-gray-400">{{ $plg->user->email ?? '-' }}</div>
                                    </td>
                                    
                                    <!-- PERBAIKAN: MEMANGGIL NAMA WILAYAH DARI RELASI WILAYAH PELAYANAN -->
                                    <td class="p-3 text-xs">
                                        <span class="inline-block bg-indigo-50 text-indigo-700 border border-indigo-200 font-semibold px-2.5 py-1 rounded-full">
                                            {{ $plg->wilayahPelayanan->nama_wilayah ?? '-' }}
                                        </span>
                                    </td>
                                    
                                    <td class="p-3 text-xs text-gray-600 max-w-xs truncate">
                                        {{ $plg->alamat_lengkap }}
                                    </td>
                                    <td class="p-3 text-xs font-mono">
                                        @if($plg->latitude && $plg->longitude)
                                            <a href="https://www.google.com/maps?q={{ $plg->latitude }},{{ $plg->longitude }}" target="_blank" class="inline-flex items-center gap-1 text-indigo-600 hover:underline">
                                                🗺️ Lihat Maps
                                            </a>
                                        @else
                                            <span class="text-gray-400 border border-dashed border-gray-300 px-1.5 py-0.5 rounded">Belum Set</span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full {{ ($plg->user->status ?? 'aktif') === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ ucfirst($plg->user->status ?? 'aktif') }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.warga.edit', $plg->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 hover:underline font-semibold">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.warga.destroy', $plg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus warga ini beserta akun usernya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-rose-600 hover:underline font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-6 text-center text-gray-400">Belum ada data warga yang terdaftar.</td>
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