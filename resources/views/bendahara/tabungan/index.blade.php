<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-bendahara-sidebar />
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">{{ session('error') }}</div>
                @endif
                @if(session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">{{ session('info') }}</div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tabungan Warga &amp; Penarikan Dana</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitoring saldo tabungan nasabah dan proses penarikan dana.</p>
                </div>

                <!-- REKAP -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo Diproses (Belum Cair)</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($rekapDiproses, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Sudah Ditarik</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($rekapDitarik, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo yang Wajib Dijaga</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($dataWarga->sum('saldo'), 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- FORM PENARIKAN -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Catat Penarikan Dana Warga</h3>
                    <form action="{{ route('bendahara.tabungan.penarikan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <x-input-label for="warga_id" value="Warga *" />
                            <select id="warga_id" name="warga_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">-- Pilih Warga --</option>
                                @foreach($dataWarga as $warga)
                                    <option value="{{ $warga->id }}">{{ $warga->nama_warga }} (Saldo: Rp {{ number_format($warga->saldo, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="nominal" value="Nominal (Rp) *" />
                            <x-text-input id="nominal" name="nominal" type="number" min="1000" step="1000" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="tanggal_penarikan" value="Tanggal *" />
                            <x-text-input id="tanggal_penarikan" name="tanggal_penarikan" type="date" class="mt-1 block w-full" value="{{ now()->toDateString() }}" required />
                        </div>
                        <div>
                            <x-input-label for="catatan" value="Catatan" />
                            <x-text-input id="catatan" name="catatan" type="text" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-4">
                            <x-primary-button type="submit">{{ __('Catat Penarikan (Status: Diproses)') }}</x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- SALDO WARGA -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Saldo Tabungan Warga</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b bg-gray-50">
                                        <th class="p-3 font-semibold text-gray-600">Warga</th>
                                        <th class="p-3 font-semibold text-gray-600 text-right">Total Setor</th>
                                        <th class="p-3 font-semibold text-gray-600 text-right">Total Tarik</th>
                                        <th class="p-3 font-semibold text-gray-600 text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataWarga as $warga)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-3 font-medium">{{ $warga->nama_warga }} <span class="text-xs text-gray-400">{{ $warga->no_warga }}</span></td>
                                            <td class="p-3 text-right text-green-600">Rp {{ number_format($warga->total_beli, 0, ',', '.') }}</td>
                                            <td class="p-3 text-right text-red-500">Rp {{ number_format($warga->total_ambil, 0, ',', '.') }}</td>
                                            <td class="p-3 text-right font-semibold">Rp {{ number_format($warga->saldo, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada warga terdaftar.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- RIWAYAT PENARIKAN -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Penarikan &amp; Konfirmasi</h3>
                            <form action="{{ route('bendahara.tabungan.index') }}" method="GET" class="flex items-center gap-2">
                                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="Diproses" {{ $status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="Ditarik" {{ $status === 'Ditarik' ? 'selected' : '' }}>Ditarik</option>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg">Filter</button>
                                @if($status !== '')
                                    <a href="{{ route('bendahara.tabungan.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700">Reset</a>
                                @endif
                            </form>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b bg-gray-50">
                                        <th class="p-3 font-semibold text-gray-600">Warga</th>
                                        <th class="p-3 font-semibold text-gray-600">Tanggal</th>
                                        <th class="p-3 font-semibold text-gray-600 text-right">Nominal</th>
                                        <th class="p-3 font-semibold text-gray-600 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatPenarikan as $penarikan)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-3 font-medium">{{ $penarikan->warga->user->name ?? 'Warga' }}</td>
                                            <td class="p-3 text-xs">{{ \Carbon\Carbon::parse($penarikan->tanggal_penarikan)->format('d/m/Y') }}</td>
                                            <td class="p-3 text-right">Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</td>
                                            <td class="p-3 text-center">
                                                @if($penarikan->status == 'Diproses')
                                                    <form action="{{ route('bendahara.tabungan.penarikan.ditarik', $penarikan->id) }}" method="POST" onsubmit="return confirm('Konfirmasi dana sudah ditarik warga? Saldo akan dikurangi.')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-xs font-semibold text-amber-600 hover:text-amber-800 bg-amber-50 px-2 py-1 rounded">Diproses — Konfirmasi Cair</button>
                                                    </form>
                                                @else
                                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Ditarik</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada riwayat penarikan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>