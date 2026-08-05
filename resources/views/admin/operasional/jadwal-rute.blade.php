<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <a href="{{ route('admin.operasional.index') }}" class="text-cyan-600 hover:underline text-sm">&larr; Kembali</a>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Tugaskan Petugas ke Rute</h4>
                    <form method="POST" action="{{ route('admin.operasional.tugaskan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rute</label>
                            <select name="rute_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                <option value="">Pilih Rute</option>
                                @foreach($rutes as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_rute }} ({{ $r->warga->count() }} warga)</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Petugas</label>
                            <select name="petugas_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                <option value="">Pilih Petugas</option>
                                @foreach($petugasLapangan as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Tugas</label>
                            <input type="date" name="tanggal_tugas" required value="{{ old('tanggal_tugas', date('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Armada</label>
                            <select name="armada_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                <option value="">Pilih Armada</option>
                                @foreach(\App\Models\Armada::where('status_kondisi', 'aktif')->get() as $a)
                                    <option value="{{ $a->id }}">{{ $a->nama_kendaraan }} ({{ $a->nomor_plat }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Tugaskan</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Daftar Rute & Warga</h4>
                    <div class="space-y-4">
                        @forelse($rutes as $rute)
                            <details class="bg-gray-50 rounded-lg p-4">
                                <summary class="font-medium text-gray-900 cursor-pointer">{{ $rute->nama_rute }} ({{ $rute->warga->count() }} warga)</summary>
                                <ul class="mt-2 space-y-1 ml-4">
                                    @forelse($rute->warga as $pel)
                                        <li class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">#{{ $loop->iteration }}</span>
                                            <span class="font-medium text-gray-800">{{ $pel->user?->name ?? '-' }}</span>
                                            <span class="text-gray-400">&middot; {{ $pel->alamat_lengkap ?? '-' }}</span>
                                            <span class="flex items-center gap-1 ml-auto">
                                                <form method="POST" action="{{ route('admin.operasional.urut', $pel->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="arah" value="up">
                                                    <button type="submit" {{ $loop->first ? 'disabled' : '' }} class="px-2 py-1 text-xs font-semibold rounded border {{ $loop->first ? 'bg-gray-100 text-gray-300 border-gray-200 cursor-not-allowed' : 'bg-white text-indigo-600 border-indigo-200 hover:bg-indigo-50' }}">▲ Naik</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.operasional.urut', $pel->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="arah" value="down">
                                                    <button type="submit" {{ $loop->last ? 'disabled' : '' }} class="px-2 py-1 text-xs font-semibold rounded border {{ $loop->last ? 'bg-gray-100 text-gray-300 border-gray-200 cursor-not-allowed' : 'bg-white text-indigo-600 border-indigo-200 hover:bg-indigo-50' }}">▼ Turun</button>
                                                </form>
                                            </span>
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-400">Tidak ada warga.</li>
                                    @endforelse
                                </ul>
                            </details>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada rute.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
