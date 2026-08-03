<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Tambah Akun Staf / Admin Baru</h2>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Login</label>
                    <input type="email" name="email" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <!-- Role / Jabatan -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Role / Hak Akses</label>
                    <select name="role_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Role / Jabatan --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" name="password" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status Akun</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan Akun Baru</button>
                </div>
            </form>
        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>