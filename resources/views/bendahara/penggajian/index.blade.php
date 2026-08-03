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
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Proses Penggajian Otomatis</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Sistem akan menghitung gaji berdasarkan absensi: Gaji Pokok (Rp1.500.000) + Insentif $25.000/hadir + Bonus $200.000 (jika hadir >= 20 hari) - Potongan Alpha $50.000/hari.
                    </p>
                    <form action="{{ route('bendahara.penggajian.proses') }}" method="POST" class="flex items-end gap-4">
                        @csrf
                        <div>
                            <x-input-label for="bulan_gaji" value="Pilih Bulan Gaji" />
                            <x-text-input id="bulan_gaji" name="bulan_gaji" type="month" class="mt-1 block" value="{{ $bulanFilter }}" required />
                        </div>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            Proses Gaji Otomatis
                        </x-primary-button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Riwayat Penggajian</h3>
                        <div class="flex gap-2">
                            <form method="GET" action="{{ route('bendahara.penggajian.index') }}">
                                <select name="bulan" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm">
                                    @foreach($daftarBulan as $b)
                                        <option value="{{ $b }}" {{ $bulanFilter == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <a href="{{ route('bendahara.penggajian.rekap', ['bulan' => $bulanFilter]) }}"
                               class="text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded hover:bg-gray-200 font-medium">
                                Lihat Rekap
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">No</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Nama Petugas</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Bulan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Gaji Pokok</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Insentif</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Potongan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 bg-indigo-50 text-indigo-700">Bersih</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataGaji as $key => $gaji)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm text-gray-700">{{ $key + 1 }}</td>
                                        <td class="p-3 text-sm text-gray-900 font-medium">{{ $gaji->petugas->name ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $gaji->bulan_gaji }}</td>
                                        <td class="p-3 text-sm text-gray-700">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm text-green-700">Rp {{ number_format($gaji->insentif_lembur, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm text-red-600">Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-bold bg-indigo-50 text-indigo-700">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $gaji->status_pembayaran == 'Dibayar' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $gaji->status_pembayaran }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm">
                                            <div class="flex gap-1">
                                                @if($gaji->status_pembayaran == 'Pending')
                                                    <form action="{{ route('bendahara.penggajian.bayar', $gaji->id) }}" method="POST" onsubmit="return confirm('Bayar gaji ini?')">
                                                        @csrf
                                                        <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Bayar</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('bendahara.penggajian.slip', $gaji->id) }}" target="_blank"
                                                   class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200">
                                                    Slip
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="p-4 text-center text-sm text-gray-500">
                                            Belum ada data penggajian untuk bulan ini. Proses gaji untuk memulai.
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
