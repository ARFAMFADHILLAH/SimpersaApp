<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-pelanggan-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Notifikasi</h3>

                <div class="space-y-3">
                    @forelse($notifikasi as $item)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-start gap-4">
                            <span class="text-xl">{{ $item['icon'] }}</span>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $item['judul'] }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $item['pesan'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $item['waktu'] instanceof \Carbon\Carbon ? $item['waktu']->diffForHumans() : '' }}</p>
                            </div>
                            <a href="{{ $item['tautan'] }}" class="text-xs text-indigo-600 hover:underline shrink-0">Lihat</a>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 text-center">
                            <p class="text-gray-400">Belum ada notifikasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
    <x-pelanggan-bottom-nav />
</x-app-layout>
