<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pendaftaran Warga Baru (Walk-in)</h3>

                    <form method="POST" action="{{ route('admin.warga.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">{{ old('alamat_lengkap') }}</textarea>
                                @error('alamat_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <p class="text-xs text-gray-500">Password default: <code class="bg-gray-100 px-1 rounded">password123</code></p>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Daftarkan</button>
                            <a href="{{ route('admin.warga.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
