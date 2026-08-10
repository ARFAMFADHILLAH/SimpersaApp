<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-owner-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pengguna Sistem</h1>
                        <p class="text-sm text-gray-500 mt-1">Daftar pengguna aktif (admin, bendahara, petugas) — read-only.</p>
                    </div>
                    <form action="{{ route('owner.pengguna.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari nama / email..." class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <select name="role" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ $roleFilter == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="bendahara" {{ $roleFilter == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                            <option value="petugas" {{ $roleFilter == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ $roleFilter == 'owner' ? 'selected' : '' }}>Owner</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg">Filter</button>
                    </form>
                </div>

                <!-- STATISTIK -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow p-4">
                        <p class="text-xs text-gray-500">Admin</p>
                        <p class="text-xl font-bold text-gray-900">{{ $jumlahPerRole['admin'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4">
                        <p class="text-xs text-gray-500">Bendahara</p>
                        <p class="text-xl font-bold text-gray-900">{{ $jumlahPerRole['bendahara'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4">
                        <p class="text-xs text-gray-500">Petugas</p>
                        <p class="text-xl font-bold text-gray-900">{{ $jumlahPerRole['petugas'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4">
                        <p class="text-xs text-gray-500">Owner</p>
                        <p class="text-xl font-bold text-gray-900">{{ $jumlahPerRole['owner'] }}</p>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 font-semibold text-gray-600">Nama</th>
                                    <th class="p-3 font-semibold text-gray-600">Email</th>
                                    <th class="p-3 font-semibold text-gray-600">Role</th>
                                    <th class="p-3 font-semibold text-gray-600 text-center">Status</th>
                                    <th class="p-3 font-semibold text-gray-600">Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-medium">{{ $user->nama }}</td>
                                        <td class="p-3 text-xs text-gray-500">{{ $user->email }}</td>
                                        <td class="p-3">
                                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded capitalize">{{ $user->role }}</span>
                                        </td>
                                        <td class="p-3 text-center">
                                            @if($user->aktif)
                                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Aktif</span>
                                            @else
                                                <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-xs text-gray-500">{{ $user->terdaftar }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-4 text-center text-gray-500">Tidak ada pengguna.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-owner-bottom-nav />
</x-app-layout>