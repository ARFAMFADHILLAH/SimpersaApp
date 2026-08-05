<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-warga-sidebar />

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm p-4 rounded-lg">{{ session('error') }}</div>
                @endif

                @if($iuranDiproses)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-6 rounded-xl">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-amber-600 bg-amber-100 px-2.5 py-1 rounded-full">MENUNGGU VERIFIKASI</span>
                                <h4 class="text-lg font-bold text-gray-900 mt-2">Pembayaran {{ $iuranDiproses->bulan_tagihan }} sedang diproses</h4>
                                <p class="text-sm text-gray-600 mt-1">Bukti pembayaran Anda sedang diperiksa oleh bendahara. Kwitansi akan tersedia setelah diverifikasi.</p>
                                @if($iuranDiproses->bukti_pembayaran)
                                    <a href="{{ \Storage::url($iuranDiproses->bukti_pembayaran) }}" target="_blank" class="inline-block mt-2 text-xs font-semibold text-amber-700 hover:underline">Lihat bukti yang dikirim &rarr;</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($tagihanBulanIni)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-amber-200">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">TAGIHAN AKTIF</span>
                                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ $tagihanBulanIni->bulan_tagihan }}</h4>
                                <p class="text-3xl font-black text-gray-900 mt-1">Rp {{ number_format($tagihanBulanIni->jumlah_tagihan, 0, ',', '.') }}</p>
                                @if($tagihanBulanIni->denda > 0)
                                    <p class="text-xs text-red-600 mt-1">Denda: Rp {{ number_format($tagihanBulanIni->denda, 0, ',', '.') }}</p>
                                @endif
                                <p class="text-[11px] text-gray-400 mt-1">Pembayaran akan diverifikasi bendahara sebelum dinyatakan lunas.</p>
                            </div>
                            <div class="w-full md:w-80 bg-gray-50 p-4 rounded-lg border">
                                <p class="text-xs font-semibold text-gray-500 mb-2">Konfirmasi Pembayaran</p>
                                <form action="{{ route('warga.iuran.bayar', $tagihanBulanIni->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    <select name="metode_pembayaran" id="metode_pembayaran" required class="block w-full rounded-md border-gray-300 shadow-sm text-xs">
                                        <option value="">Pilih Metode</option>
                                        <option value="Non-Tunai">Transfer Bank / E-Wallet / VA</option>
                                        <option value="Tunai">Tunai (Bayar di Kantor)</option>
                                    </select>

                                    <div id="upload-bukti" class="hidden">
                                        <label for="foto_bukti" class="block text-[11px] font-semibold text-gray-500 mb-1">Upload Bukti Pembayaran (wajib untuk Non-Tunai)</label>
                                        <input type="file" name="foto_bukti" id="foto_bukti" accept="image/*" class="block w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <p class="text-[10px] text-gray-400 mt-1">Foto bukti transfer / e-wallet (JPG/PNG, maks 3MB)</p>
                                    </div>

                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2 px-4 rounded-md transition">
                                        Konfirmasi Pembayaran
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif(!$iuranDiproses)
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-6 rounded-xl">
                        <p class="font-semibold">Tidak ada tagihan yang perlu dibayar. Semua iuran Anda sudah lunas!</p>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h4 class="font-bold text-gray-800 text-sm">Riwayat Tagihan & Pembayaran</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-3 text-left">Periode</th>
                                    <th class="px-6 py-3 text-left">Tagihan</th>
                                    <th class="px-6 py-3 text-left">Denda</th>
                                    <th class="px-6 py-3 text-left">Total</th>
                                    <th class="px-6 py-3 text-left">Metode</th>
                                    <th class="px-6 py-3 text-left">Tgl Bayar</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($riwayatIuran as $iuran)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $iuran->bulan_tagihan }}</td>
                                        <td class="px-6 py-3">Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3">Rp {{ number_format($iuran->denda ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 font-semibold">Rp {{ number_format($iuran->jumlah_tagihan + ($iuran->denda ?? 0), 0, ',', '.') }}</td>
                                        <td class="px-6 py-3">{{ $iuran->metode_pembayaran ?? '-' }}</td>
                                        <td class="px-6 py-3">{{ $iuran->tanggal_bayar ? \Carbon\Carbon::parse($iuran->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-3">
                                            @if($iuran->status_pembayaran == 'Lunas')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">LUNAS</span>
                                            @elseif($iuran->status_pembayaran == 'Sedang Diproses')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">SEDANG DIPROSES</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-600">BELUM BAYAR</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3">
                                            @if($iuran->status_pembayaran == 'Lunas')
                                                <a href="{{ route('warga.iuran.kwitansi', $iuran->id) }}" class="text-indigo-600 hover:underline font-semibold" target="_blank">Kwitansi</a>
                                            @elseif($iuran->status_pembayaran == 'Sedang Diproses' && $iuran->bukti_pembayaran)
                                                <a href="{{ \Storage::url($iuran->bukti_pembayaran) }}" target="_blank" class="text-amber-600 hover:underline font-semibold">Lihat Bukti</a>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat tagihan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t">{{ $riwayatIuran->links() }}</div>
                </div>

            </div>
        </main>
    </div>
    <x-warga-bottom-nav />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('metode_pembayaran');
            const upload = document.getElementById('upload-bukti');
            if (select && upload) {
                const toggle = () => {
                    upload.classList.toggle('hidden', select.value !== 'Non-Tunai');
                    document.getElementById('foto_bukti').required = select.value === 'Non-Tunai';
                };
                select.addEventListener('change', toggle);
            }
        });
    </script>
</x-app-layout>
