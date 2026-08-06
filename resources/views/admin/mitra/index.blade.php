<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto space-y-8">

                <!-- Header -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Profil Mitra (Bank Sampah)</h1>
                    <p class="text-sm text-gray-500 mt-1">Mitra adalah pembeli sampah yang membayar warga penyetor secara tunai (tanpa login). Setoran otomatis menggunakan profil mitra ini.</p>
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Profil Mitra -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Profil Mitra</h3>
                    <form action="{{ route('admin.mitra.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="nama_mitra" value="Nama Mitra *" />
                            <x-text-input id="nama_mitra" name="nama_mitra" type="text" class="mt-1 block w-full" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required />
                        </div>
                        <div>
                            <x-input-label for="no_hp" value="No. HP" />
                            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" value="{{ old('no_hp', $mitra->no_hp) }}" placeholder="08xxxxxxxxxx" />
                        </div>
                        <div>
                            <x-input-label for="alamat_kontak" value="Alamat / Kontak" />
                            <x-text-input id="alamat_kontak" name="alamat_kontak" type="text" class="mt-1 block w-full" value="{{ old('alamat_kontak', $mitra->alamat_kontak) }}" placeholder="Alamat atau keterangan kontak" />
                        </div>
                        <div>
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">Simpan Profil Mitra</x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Ringkasan -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Mitra</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">Total Setoran Dibayar</p>
                            <p class="text-xl font-bold text-gray-900">{{ $mitra->setoranSampahs()->count() }} setoran</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">Total Nilai Tunai Dibayarkan</p>
                            <p class="text-xl font-bold text-emerald-700">Rp {{ number_format($mitra->setoranSampahs()->sum('total_bayar'), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>