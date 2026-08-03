<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-pelanggan-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 text-sm p-4 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm p-4 rounded-lg">{{ session('error') }}</div>
                @endif

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                ID: {{ $pelanggan->no_pelanggan }}
                            </span>
                            <h3 class="text-xl font-bold text-gray-900 mt-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Rute: {{ $pelanggan->rute->nama_rute ?? 'Belum Ditentukan' }} &middot; Wilayah: {{ $pelanggan->wilayahPelayanan->nama_wilayah ?? '-' }}</p>
                        </div>
                        <div class="flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2 rounded-lg border border-green-200">
                            <span class="text-xs font-bold">{{ $pelanggan->no_hp ?? '-' }}</span>
                        </div>
                    </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tagihan Belum Lunas</div>
                            <div class="text-2xl font-black text-gray-900 mt-2">
                                Rp {{ number_format($tagihanBulanIni->jumlah_tagihan ?? 0, 0, ',', '.') }}
                            </div>
                            @if($tagihanBulanIni)
                                <p class="text-xs text-amber-600 font-medium mt-1">Bulan: {{ $tagihanBulanIni->bulan_tagihan }}</p>
                            @else
                                <p class="text-xs text-green-600 font-medium mt-1">Semua iuran sudah lunas.</p>
                            @endif
                        </div>
                        <a href="{{ route('pelanggan.iuran.index') }}" class="mt-4 block w-full text-center bg-green-600 hover:bg-green-700 disabled:bg-gray-200 text-white font-semibold text-xs py-2.5 px-4 rounded-lg transition shadow-sm">
                            Bayar Sekarang
                        </a>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Alamat Terdaftar</div>
                        <p class="text-xs font-medium text-gray-800 mt-3 leading-relaxed">{{ $pelanggan->alamat_lengkap ?? 'Belum diinput.' }}</p>
                        <div class="text-[10px] text-gray-400 mt-2 font-mono">
                            GPS: {{ $pelanggan->latitude ?? '-' }}, {{ $pelanggan->longitude ?? '-' }}
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaduan Aktif</div>
                        <p class="text-2xl font-black text-gray-900 mt-2">{{ $pengaduanTerbaru->where('status_respon', '!=', 'Selesai')->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pengaduan dalam proses</p>
                        <a href="{{ route('pelanggan.pengaduan.index') }}" class="text-xs text-green-600 hover:underline mt-2 inline-block">Lihat Semua &rarr;</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h4 class="font-bold text-gray-800 text-sm">Riwayat Pembayaran</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Periode</th>
                                        <th class="px-6 py-3 text-left">Metode</th>
                                        <th class="px-6 py-3 text-left">Jumlah</th>
                                        <th class="px-6 py-3 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @forelse($riwayatIuran as $iuran)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $iuran->bulan_tagihan }}</td>
                                            <td class="px-6 py-3">{{ $iuran->metode_pembayaran ?? '-' }}</td>
                                            <td class="px-6 py-3">Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td>
                                            <td class="px-6 py-3 font-bold {{ $iuran->status_pembayaran == 'Lunas' ? 'text-green-600' : 'text-amber-600' }}">
                                                {{ $iuran->status_pembayaran == 'Lunas' ? 'Lunas' : 'Belum Lunas' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-t bg-gray-50 text-right">
                            <a href="{{ route('pelanggan.iuran.index') }}" class="text-xs text-green-600 hover:underline">Lihat semua &rarr;</a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h4 class="font-bold text-gray-800 text-sm">Riwayat Pengangkutan</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Tanggal</th>
                                        <th class="px-6 py-3 text-left">Armada</th>
                                        <th class="px-6 py-3 text-left">Volume</th>
                                        <th class="px-6 py-3 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @forelse($riwayatPengangkutan as $angkut)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3">{{ \Carbon\Carbon::parse($angkut->tanggal_tugas)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-3">{{ $angkut->armada?->nama_kendaraan ?? '-' }}</td>
                                            <td class="px-6 py-3">{{ $angkut->volume_m3 }} m&sup3;</td>
                                            <td class="px-6 py-3 font-bold {{ $angkut->status_tugas == 'Selesai' ? 'text-green-600' : 'text-amber-600' }}">
                                                {{ $angkut->status_tugas }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-t bg-gray-50 text-right">
                            <a href="{{ route('pelanggan.profile.riwayat') }}" class="text-xs text-green-600 hover:underline">Lihat semua &rarr;</a>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <x-pelanggan-bottom-nav />
</x-app-layout>
