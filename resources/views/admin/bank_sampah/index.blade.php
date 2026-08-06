<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- Header -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Bank Sampah — Setoran Warga</h1>
                    <p class="text-sm text-gray-500 mt-1">Warga menyetorkan sampah, dibayar tunai langsung oleh mitra.</p>
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <!-- Statistik Ringkas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-emerald-600 text-white p-5 rounded-xl shadow">
                        <p class="text-sm font-medium opacity-80">Total Setoran</p>
                        <p class="text-3xl font-bold">{{ number_format($totalSetoran, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-teal-600 text-white p-5 rounded-xl shadow">
                        <p class="text-sm font-medium opacity-80">Total Berat</p>
                        <p class="text-3xl font-bold">{{ number_format($totalKg, 2, ',', '.') }} kg</p>
                    </div>
                    <div class="bg-orange-600 text-white p-5 rounded-xl shadow">
                        <p class="text-sm font-medium opacity-80">Total Dibayar Tunai</p>
                        <p class="text-3xl font-bold">Rp {{ number_format($totalBayar, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Form Setoran -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Catat Setoran Baru</h3>
                    <form action="{{ route('admin.bank-sampah.store') }}" method="POST" class="space-y-4" id="formSetoran">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="warga_id" value="Warga Penyetor *" />
                                <select id="warga_id" name="warga_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach($wargaList as $w)
                                        <option value="{{ $w->id }}" {{ old('warga_id') == $w->id ? 'selected' : '' }}>{{ $w->no_warga }} — {{ $w->user?->name ?? 'Tanpa Akun' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label value="Mitra" />
                                <p class="mt-2 text-sm font-semibold text-gray-700">{{ $mitra->nama_mitra }}</p>
                                <p class="text-xs text-gray-400">Setoran otomatis dibayar oleh mitra.</p>
                            </div>
                            <div>
                                <x-input-label for="tanggal_setoran" value="Tanggal Setoran *" />
                                <x-text-input id="tanggal_setoran" name="tanggal_setoran" type="date" class="mt-1 block w-full" value="{{ old('tanggal_setoran', now()->toDateString()) }}" required />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="jenis_sampah_id" value="Jenis Sampah *" />
                                <select id="jenis_sampah_id" name="jenis_sampah_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisList as $j)
                                        <option value="{{ $j->id }}" data-harga="{{ $j->tarif_per_kg }}" {{ old('jenis_sampah_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }} (Rp {{ number_format($j->tarif_per_kg, 0, ',', '.') }}/kg)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="berat_kg" value="Berat (kg) *" />
                                <x-text-input id="berat_kg" name="berat_kg" type="number" step="0.01" min="0.01" class="mt-1 block w-full" value="{{ old('berat_kg') }}" placeholder="Contoh: 2.5" required />
                            </div>
                            <div>
                                <x-input-label value="Total Bayar (Preview)" />
                                <p id="previewTotal" class="mt-2 text-lg font-bold text-emerald-700">Rp 0</p>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="keterangan" value="Keterangan (opsional)" />
                            <x-text-input id="keterangan" name="keterangan" type="text" class="mt-1 block w-full" value="{{ old('keterangan') }}" placeholder="Contoh: Setoran rutin mingguan" />
                        </div>
                        <div>
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">Catat Setoran & Bayar Tunai</x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Riwayat Setoran -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Setoran</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Warga</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Jenis</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Berat</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Harga/Kg</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Total Bayar</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Mitra</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($setoran as $s)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm text-gray-700">{{ $s->tanggal_setoran->format('d M Y') }}</td>
                                        <td class="p-3 text-sm text-gray-900 font-medium">{{ $s->warga?->user?->name ?? 'Warga' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $s->jenisSampah?->nama_jenis ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ number_format($s->berat_kg, 2, ',', '.') }} kg</td>
                                        <td class="p-3 text-sm text-gray-700">Rp {{ number_format($s->harga_per_kg, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm font-semibold text-emerald-700">Rp {{ number_format($s->total_bayar, 0, ',', '.') }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $s->mitra?->nama_mitra ?? '-' }}</td>
                                        <td class="p-3 text-sm text-center">
                                            <form action="{{ route('admin.bank-sampah.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus setoran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="p-4 text-center text-sm text-gray-400">Belum ada setoran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const jenisSelect = document.getElementById('jenis_sampah_id');
        const beratInput = document.getElementById('berat_kg');
        const preview = document.getElementById('previewTotal');

        function hitungPreview() {
            const harga = parseFloat(jenisSelect.selectedOptions[0]?.dataset.harga || 0);
            const berat = parseFloat(beratInput.value || 0);
            const total = harga * berat;
            preview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }
        jenisSelect.addEventListener('change', hitungPreview);
        beratInput.addEventListener('input', hitungPreview);
    </script>
</x-app-layout>