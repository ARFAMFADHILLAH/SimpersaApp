<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="{ openAddModal: false, editData: {}, openEditModal: false }">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ALERT TOTAL BOBOT -->
            @if($totalBobot != 1 && $kriteria->count() > 0)
                <div class="bg-amber-100 border border-amber-400 text-amber-800 px-4 py-3 rounded-lg text-sm">
                    ⚠️ <strong>Perhatian:</strong> Total bobot kriteria saat ini adalah <strong>{{ $totalBobot * 100 }}%</strong> ({{ $totalBobot }}). Disarankan total akumulasi bobot bernilai tepat <strong>1.00 (100%)</strong> agar hasil perhitungan presisi.
                </div>
            @endif

            <!-- PANEL 1: KELOLA KRITERIA & BOBOT -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">⚙️ Konfigurasi Kriteria & Bobot Penilaian</h3>
                        <p class="text-sm text-gray-500">Tentukan kriteria, sifat kriteria (Benefit/Cost), dan bobot persentase keputusannya.</p>
                    </div>
                    <x-primary-button @click="openAddModal = true" class="bg-indigo-600 hover:bg-indigo-700">
                        + Tambah Kriteria
                    </x-primary-button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                <th class="p-3">Kode</th>
                                <th class="p-3">Nama Kriteria</th>
                                <th class="p-3">Sifat (Jenis)</th>
                                <th class="p-3">Bobot (Desimal)</th>
                                <th class="p-3">Bobot (%)</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($kriteria as $k)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-bold text-gray-700">{{ $k->kode_kriteria }}</td>
                                    <td class="p-3 text-gray-900">{{ $k->nama_kriteria }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded {{ $k->jenis == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ strtoupper($k->jenis) }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-semibold">{{ $k->bobot }}</td>
                                    <td class="p-3 font-bold text-indigo-600">{{ $k->bobot * 100 }}%</td>
                                    <td class="p-3 text-center flex justify-center gap-2">
                                        <button @click="openEditModal = true; editData = { id: '{{ $k->id }}', kode_kriteria: '{{ $k->kode_kriteria }}', nama_kriteria: '{{ $k->nama_kriteria }}', bobot: '{{ $k->bobot }}', jenis: '{{ $k->jenis }}' }" 
                                                class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.keputusan.kriteria.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">Belum ada kriteria yang dikonfigurasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PANEL 2: HASIL REKOMENDASI KEPUTUSAN -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-2">🏆 Hasil Rekomendasi Prioritas Pengambilan Keputusan</h3>
                <p class="text-sm text-gray-500 mb-4">Peringkat ini dihitung otomatis menggunakan metode SAW berdasarkan skor TPS dan kriteria bobot di atas.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                <th class="p-3">Rank</th>
                                <th class="p-3">Nama Objek (TPS)</th>
                                <th class="p-3">Skor Akhir (V)</th>
                                <th class="p-3">Rekomendasi Keputusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($hasilKeputusan as $index => $res)
                                <tr class="hover:bg-gray-50 {{ $index == 0 ? 'bg-amber-50/50' : '' }}">
                                    <td class="p-3 font-bold text-gray-700">
                                        @if($index == 0)
                                            🥇 Rank 1
                                        @elseif($index == 1)
                                            🥈 Rank 2
                                        @elseif($index == 2)
                                            🥉 Rank 3
                                        @else
                                            #{{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="p-3 font-semibold text-gray-900">{{ $res['tps']->nama_tps }}</td>
                                    <td class="p-3 font-bold text-indigo-600">{{ $res['skor_akhir'] }}</td>
                                    <td class="p-3">
                                        @if($index == 0)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">Prioritas Utama Pengangkutan</span>
                                        @else
                                            <span class="text-xs text-gray-500">Antrean Normal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500">Data belum cukup untuk melakukan kalkulasi keputusan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PANEL 3: INPUT SKOR ALTERNATIF (TPS PER KRITERIA) -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-1">🎯 Input Skor Alternatif (TPS)</h3>
                <p class="text-sm text-gray-500 mb-4">Isi nilai skor setiap TPS untuk setiap kriteria, lalu simpan. Hasil peringkat SAW diperbarui otomatis.</p>

                @if($kriteria->count() == 0)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg text-sm">
                        Tambahkan kriteria terlebih dahulu sebelum mengisi skor alternatif.
                    </div>
                @elseif($tpsList->count() == 0)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg text-sm">
                        Data TPS belum tersedia. Tambahkan TPS pada menu Master Data &rarr; TPS terlebih dahulu.
                    </div>
                @else
                    <form action="{{ route('admin.keputusan.skor.store') }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                        <th class="p-3">TPS / Alternatif</th>
                                        @foreach($kriteria as $k)
                                            <th class="p-3 text-center">{{ $k->kode_kriteria }}<br>
                                                <span class="text-[10px] font-normal text-gray-400">{{ $k->nama_kriteria }} ({{ strtoupper($k->jenis) }})</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y text-sm">
                                    @foreach($tpsList as $tps)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-3 font-semibold text-gray-900">{{ $tps->nama_tps }}</td>
                                            @foreach($kriteria as $k)
                                                <td class="p-3 text-center">
                                                    <input type="number" step="0.01" min="0" name="skor[{{ $tps->id }}][{{ $k->id }}]"
                                                           value="{{ $skorMap[$tps->id][$k->id] ?? '' }}"
                                                           placeholder="0"
                                                           class="w-24 text-center border-gray-300 rounded-md shadow-sm text-sm">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">💾 Simpan Skor Alternatif</x-primary-button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- MODAL TAMBAH KRITERIA -->
            <div x-show="openAddModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Tambah Kriteria DSS</h3>
                    <form action="{{ route('admin.keputusan.kriteria.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label value="Kode Kriteria (e.g. C1, C2)" />
                            <x-text-input name="kode_kriteria" class="w-full mt-1" required placeholder="C1" />
                        </div>
                        <div>
                            <x-input-label value="Nama Kriteria" />
                            <x-text-input name="nama_kriteria" class="w-full mt-1" required placeholder="Volume Sampah" />
                        </div>
                        <div>
                            <x-input-label value="Bobot (Contoh: 0.25 untuk 25%)" />
                            <x-text-input name="bobot" type="number" step="0.01" class="w-full mt-1" required placeholder="0.25" />
                        </div>
                        <div>
                            <x-input-label value="Jenis Kriteria" />
                            <select name="jenis" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                                <option value="benefit">Benefit (Semakin tinggi nilai, semakin bagus)</option>
                                <option value="cost">Cost (Semakin rendah nilai, semakin bagus)</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">Batal</button>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL EDIT KRITERIA -->
            <div x-show="openEditModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Edit Kriteria & Bobot</h3>
                    <form :action="'{{ url('/admin/keputusan/kriteria') }}/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label value="Kode Kriteria" />
                            <x-text-input name="kode_kriteria" class="w-full mt-1" x-model="editData.kode_kriteria" required />
                        </div>
                        <div>
                            <x-input-label value="Nama Kriteria" />
                            <x-text-input name="nama_kriteria" class="w-full mt-1" x-model="editData.nama_kriteria" required />
                        </div>
                        <div>
                            <x-input-label value="Bobot (Desimal)" />
                            <x-text-input name="bobot" type="number" step="0.01" class="w-full mt-1" x-model="editData.bobot" required />
                        </div>
                        <div>
                            <x-input-label value="Jenis Kriteria" />
                            <select name="jenis" x-model="editData.jenis" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">Batal</button>
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">Update</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>