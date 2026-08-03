<x-app-layout>
    @php
        $role = strtolower(auth()->user()->role->name ?? '');
        $sidebar = match (true) {
            str_contains($role, 'admin') => 'admin',
            str_contains($role, 'manager') || str_contains($role, 'manajer') => 'manager',
            str_contains($role, 'bendahara') => 'bendahara',
            str_contains($role, 'administrasi') => 'administrasi',
            str_contains($role, 'petugas') || str_contains($role, 'supir') || str_contains($role, 'pengangkut') => 'petugas',
            default => 'pelanggan',
        };
    @endphp
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-dynamic-component :component="$sidebar . '-sidebar'" />

        <main class="flex-1 py-10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Notifikasi</h3>
                    @if($notifikasi->where('is_read', false)->count() > 0)
                        <form method="POST" action="{{ route('notifikasi.semua-baca') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Tandai Semua Dibaca</button>
                        </form>
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse($notifikasi as $item)
                        <div class="bg-white p-4 rounded-xl shadow-sm border {{ $item->is_read ? 'border-gray-200' : 'border-indigo-200 bg-indigo-50/30' }} flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->judul }}</p>
                                    @if(!$item->is_read)
                                        <span class="px-1.5 py-0.5 bg-indigo-100 text-indigo-700 text-[9px] font-bold rounded">BARU</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $item->pesan }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($item->tautan)
                                    <a href="{{ $item->tautan }}" class="text-xs text-indigo-600 hover:underline">Lihat</a>
                                @endif
                                @if(!$item->is_read)
                                    <form method="POST" action="{{ route('notifikasi.baca', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">Tandai baca</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 text-center">
                            <p class="text-gray-400">Belum ada notifikasi.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">{{ $notifikasi->links() }}</div>
            </div>
        </main>
    </div>
    <x-dynamic-component :component="$sidebar . '-bottom-nav'" />
</x-app-layout>
