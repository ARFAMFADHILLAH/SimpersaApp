<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Detail Warga</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500">No Warga</dt><dd class="font-medium">{{ $warga->no_warga }}</dd></div>
                        <div><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $warga->user?->name ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Email</dt><dd>{{ $warga->user?->email ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">No HP</dt><dd>{{ $warga->no_hp ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Alamat</dt><dd class="col-span-2">{{ $warga->alamat_lengkap ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Rute</dt><dd>{{ $warga->rute?->nama_rute ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Wilayah</dt><dd>{{ $warga->wilayah?->nama_wilayah ?? '-' }}</dd></div>
                    </dl>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Riwayat Pengangkutan</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Armada</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Volume</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($riwayatPengangkutan as $angkut)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($angkut->tanggal_tugas)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $angkut->armada?->nama_kendaraan ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $angkut->volume_m3 }} m&sup3;</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded
                                                {{ $angkut->status_tugas == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $angkut->status_tugas == 'Sedang dikerjakan' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $angkut->status_tugas == 'Belum dikerjakan' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                {{ $angkut->status_tugas }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">Belum ada riwayat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ $riwayatPengangkutan->links() }}</div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Riwayat Pembayaran Iuran</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Bulan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tagihan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($riwayatPembayaran as $iuran)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($iuran->bulan_tagihan)->format('F Y') }}</td>
                                        <td class="px-4 py-2 text-sm">Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded
                                                {{ $iuran->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $iuran->status_pembayaran }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $iuran->tanggal_bayar ? \Carbon\Carbon::parse($iuran->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">Belum ada riwayat pembayaran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ $riwayatPembayaran->links() }}</div>
                </div>
            </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>
