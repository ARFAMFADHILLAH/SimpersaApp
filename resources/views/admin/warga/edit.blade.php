<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">

            <div class="max-w-4xl mx-auto">
            
            <!-- ALERT NOTIFIKASI SUKSES / ERROR -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-6 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="flex items-center justify-between mb-6 border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Form Edit Warga</h3>
                    <span class="text-xs font-mono font-bold bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full border border-indigo-200">
                        No. Warga: {{ $warga->no_warga }}
                    </span>
                </div>

                <form action="{{ route('admin.warga.update', $warga->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Warga -->
                        <div>
                            <x-input-label for="name" value="Nama Lengkap *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $warga->user->name ?? '') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" value="Email *" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $warga->user->email ?? '') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <!-- No. HP / WA -->
                        <div>
                            <x-input-label for="no_hp" value="Nomor HP / WhatsApp *" />
                            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" value="{{ old('no_hp', $warga->no_hp) }}" required />
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-1" />
                        </div>

                        <!-- Wilayah Pelayanan -->
                        <div>
                            <x-input-label for="wilayah_pelayanan_id" value="Wilayah Pelayanan *" />
                            <select id="wilayah_pelayanan_id" name="wilayah_pelayanan_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="">-- Pilih Wilayah Pelayanan --</option>
                                @foreach($wilayahList as $wilayah)
                                    <option value="{{ $wilayah->id }}" 
                                        {{ old('wilayah_pelayanan_id', $warga->wilayah_pelayanan_id) == $wilayah->id ? 'selected' : '' }}>
                                        {{ $wilayah->nama_wilayah }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('wilayah_pelayanan_id')" class="mt-1" />
                        </div>

                        <!-- Rute -->
                        <div>
                            <x-input-label for="rute_id" value="Rute *" />
                            <select id="rute_id" name="rute_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="">-- Pilih Rute --</option>
                                @foreach(\App\Models\Rute::all() as $rute)
                                    <option value="{{ $rute->id }}"
                                        {{ old('rute_id', $warga->rute_id) == $rute->id ? 'selected' : '' }}>
                                        {{ $rute->nama_rute }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rute_id')" class="mt-1" />
                        </div>

                        <!-- Status User -->
                        <div>
                            <x-input-label for="status" value="Status Akun *" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <option value="aktif" {{ old('status', $warga->user->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $warga->user->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-1" />
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <x-input-label for="password" value="Password Baru (Opsional)" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Biarkan kosong jika tak diubah" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <x-input-label for="alamat_lengkap" value="Alamat Lengkap Rumah *" />
                        <textarea id="alamat_lengkap" name="alamat_lengkap" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>{{ old('alamat_lengkap', $warga->alamat_lengkap) }}</textarea>
                        <x-input-error :messages="$errors->get('alamat_lengkap')" class="mt-1" />
                    </div>

                    <!-- PETA & KOORDINAT LOKASI (otomatis dari alamat lengkap / klik peta / lokasi saya) -->
                    <div class="border-t pt-4 mt-2">
                        <x-lokasi-picker map-id="mapWargaEdit" address-input-id="alamat_lengkap" :initial-lat="$warga->latitude" :initial-lng="$warga->longitude" hint="Klik peta untuk mengubah titik, cari dari alamat lengkap, atau pakai lokasi Anda." />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('admin.warga.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                            Batal
                        </a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <x-admin-bottom-nav />
</x-app-layout>