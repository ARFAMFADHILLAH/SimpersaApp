<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-bendahara-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Pengaturan Parameter Tarif & Denda</h3>
                        <p class="text-sm text-gray-500">Atur besaran tarif iuran dasar dan nominal denda keterlambatan.</p>
                    </div>

                    <form action="{{ route('admin.iuran.update-pengaturan') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="tarif_dasar_bulanan" value="Tarif Dasar Bulanan (Rp)" />
                                <x-text-input id="tarif_dasar_bulanan" name="tarif_dasar_bulanan" type="number" class="mt-1 block w-full" value="{{ old('tarif_dasar_bulanan', $pengaturan->tarif_dasar_bulanan) }}" required />
                            </div>
                            <div>
                                <x-input-label for="tgl_jatuh_tempo" value="Tgl Jatuh Tempo (1-31)" />
                                <x-text-input id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" type="number" min="1" max="31" class="mt-1 block w-full" value="{{ old('tgl_jatuh_tempo', $pengaturan->tgl_jatuh_tempo) }}" required />
                            </div>
                            <div>
                                <x-input-label for="nominal_denda_flat" value="Nominal Denda Flat (Rp)" />
                                <x-text-input id="nominal_denda_flat" name="nominal_denda_flat" type="number" class="mt-1 block w-full" value="{{ old('nominal_denda_flat', $pengaturan->nominal_denda_flat) }}" required />
                            </div>
                            <div>
                                <x-input-label for="persentase_denda_per_bulan" value="Denda Persentase (%)" />
                                <x-text-input id="persentase_denda_per_bulan" name="persentase_denda_per_bulan" type="number" step="0.1" class="mt-1 block w-full" value="{{ old('persentase_denda_per_bulan', $pengaturan->persentase_denda_per_bulan) }}" />
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                                Simpan Parameter
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Otomatisasi Tagihan Bulanan</h3>
                        <p class="text-sm text-gray-500">
                            Buat tagihan iuran serentak berdasarkan tarif dasar (<strong>Rp {{ number_format($pengaturan->tarif_dasar_bulanan, 0, ',', '.') }}/bulan</strong>).
                        </p>
                    </div>
                    <form action="{{ route('bendahara.iuran.generate') }}" method="POST">
                        @csrf
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            Generate Tagihan Bulan Ini
                        </x-primary-button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Tagihan & Status Pembayaran</h3>
                        <form method="GET" action="{{ route('bendahara.iuran.index') }}" class="flex gap-2">
                            <select name="status" class="text-sm border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="Belum Bayar" {{ $statusFilter == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="Sedang Diproses" {{ $statusFilter == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="Lunas" {{ $statusFilter == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                            <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">Filter</button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <div class="bg-gray-50 p-3 rounded-lg border">
                            <span class="text-xs text-gray-500">Total Tagihan</span>
                            <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                            <span class="text-xs text-green-600">Total Lunas</span>
                            <p class="text-lg font-bold text-green-700">Rp {{ number_format($totalLunas, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                            <span class="text-xs text-amber-600">Menunggu Verifikasi</span>
                            <p class="text-lg font-bold text-amber-700">{{ $jumlahDiproses }} Tagihan <span class="text-xs font-medium">(Rp {{ number_format($totalDiproses, 0, ',', '.') }})</span></p>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <span class="text-xs text-red-600">Total Tunggakan</span>
                            <p class="text-lg font-bold text-red-700">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <span class="text-xs text-blue-600">Warga Menunggak</span>
                            <p class="text-lg font-bold text-blue-700">{{ $jumlahMenunggak }} Orang</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Bulan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Warga</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Tagihan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Denda</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Total</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Bukti</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataIuran as $iur)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm font-bold text-gray-700">{{ $iur->bulan_tagihan }}</td>
                                        <td class="p-3 text-sm text-gray-900">
                                            {{ $iur->warga->user->name ?? 'Warga' }}
                                            <br>
                                            <span class="text-xs text-gray-400">No: {{ $iur->warga->no_warga ?? '-' }}</span>
                                        </td>
                                        <td class="p-3 text-sm text-gray-900 font-semibold">Rp {{ number_format($iur->jumlah_tagihan, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-medium {{ $iur->denda > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                            {{ $iur->denda > 0 ? 'Rp ' . number_format($iur->denda, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="p-3 text-sm text-gray-900 font-bold">Rp {{ number_format($iur->jumlah_tagihan + $iur->denda, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $iur->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : ($iur->status_pembayaran == 'Sedang Diproses' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $iur->status_pembayaran }}
                                            </span>
                                            @if($iur->status_pembayaran == 'Lunas')
                                                <div class="text-xs text-gray-400 mt-1">Metode: {{ $iur->metode_pembayaran }}</div>
                                            @elseif($iur->status_pembayaran == 'Sedang Diproses')
                                                <div class="text-xs text-gray-400 mt-1">Metode: {{ $iur->metode_pembayaran }}</div>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm">
                                            @if($iur->bukti_pembayaran)
                                                <a href="{{ \Storage::url($iur->bukti_pembayaran) }}" target="_blank" class="text-xs text-amber-700 hover:underline font-semibold">
                                                    Lihat Bukti &rarr;
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm">
                                            @if($iur->status_pembayaran == 'Sedang Diproses')
                                                <form action="{{ route('bendahara.iuran.bayar', $iur->id) }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini sebagai LUNAS?')" class="flex gap-1">
                                                    @csrf
                                                    <button type="submit" class="text-xs bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded font-medium">
                                                        Verifikasi & Lunas
                                                    </button>
                                                </form>
                                            @elseif($iur->status_pembayaran == 'Belum Bayar')
                                                <form action="{{ route('bendahara.iuran.bayar', $iur->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pelunasan iuran?')" class="flex gap-1">
                                                    @csrf
                                                    <select name="metode_pembayaran" class="text-xs border-gray-300 rounded-md shadow-sm py-1">
                                                        <option value="Tunai">Tunai</option>
                                                        <option value="Non-Tunai">Non-Tunai</option>
                                                    </select>
                                                    <button type="submit" class="text-xs bg-green-500 text-white px-3 py-1.5 rounded hover:bg-green-600 font-medium">
                                                        Lunas
                                                    </button>
                                                </form>
                                            @else
                                                <div class="flex gap-1">
                                                    <span class="text-xs text-gray-400 font-medium">
                                                        {{ $iur->tanggal_bayar ? date('d/m/Y', strtotime($iur->tanggal_bayar)) : '-' }}
                                                    </span>
                                                    <a href="{{ route('bendahara.iuran.kwitansi', $iur->id) }}" target="_blank" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 font-medium">
                                                        Cetak
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-4 text-center text-sm text-gray-500">
                                            Belum ada catatan iuran. Generate tagihan untuk memulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-bendahara-bottom-nav />
</x-app-layout>
