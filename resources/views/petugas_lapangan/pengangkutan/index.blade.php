<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 pb-24 md:pb-0">
        <x-petugas-sidebar />

        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Log Operasional & Input Sampah -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Input Tugas & Catatan Volume Sampah</h3>
                <form action="{{ route('petugas.pengangkutan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="pelanggan_id" value="Pelanggan" />
                            <select id="pelanggan_id" name="pelanggan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($dataPelanggan as $p)
                                    <option value="{{ $p->id }}">{{ $p->no_pelanggan }} - {{ $p->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="petugas_id" value="Petugas Lapangan" />
                            <select id="petugas_id" name="petugas_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Petugas --</option>
                                @foreach($dataPetugas as $petugas)
                                    <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="armada_id" value="Armada Kendaraan" />
                            <select id="armada_id" name="armada_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($dataArmada as $a)
                                    <option value="{{ $a->id }}">{{ $a->nama_kendaraan }} ({{ $a->nomor_plat }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="jenis_sampah_id" value="Jenis Sampah" />
                            <select id="jenis_sampah_id" name="jenis_sampah_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($dataJenisSampah as $js)
                                    <option value="{{ $js->id }}">{{ $js->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="tanggal_tugas" value="Tanggal Operasional" />
                            <x-text-input id="tanggal_tugas" name="tanggal_tugas" type="date" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="volume_m3" value="Volume Sampah (m³)" />
                            <x-text-input id="volume_m3" name="volume_m3" type="number" step="0.1" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="berat_kg" value="Berat Sampah (Kg)" />
                            <x-text-input id="berat_kg" name="berat_kg" type="number" step="0.1" class="mt-1 block w-full" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status_tugas" value="Status Pekerjaan" />
                        <select id="status_tugas" name="status_tugas" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="Belum dikerjakan">Belum dikerjakan</option>
                            <option value="Sedang dikerjakan">Sedang dikerjakan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Simpan Log Operasional') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Tabel Monitoring -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Log Monitoring Angkutan & Sampah</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Pelanggan</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Petugas & Armada</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Hasil Sampah</th>
                            <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPengangkutan as $p)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm text-gray-700">{{ $p->tanggal_tugas }}</td>
                                <td class="p-3 text-sm text-gray-900 font-medium">
                                    {{ $p->pelanggan->user->name }} <br>
                                    <span class="text-xs text-gray-400">({{ $p->pelanggan->no_pelanggan }})</span>
                                </td>
                                <td class="p-3 text-sm text-gray-700">
                                    Ptg: {{ $p->petugas->name }} <br>
                                    <span class="text-xs text-indigo-500">{{ $p->armada->nama_kendaraan }}</span>
                                </td>
                                <td class="p-3 text-sm text-gray-700">
                                    Jenis: <span class="font-medium text-gray-900">{{ $p->jenisSampah->nama_jenis }}</span><br>
                                    Vol: {{ $p->volume_m3 }} m³ | Brt: {{ $p->berat_kg }} Kg
                                </td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $p->status_tugas == 'Selesai' ? 'bg-green-100 text-green-800' : ($p->status_tugas == 'Sedang dikerjakan' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $p->status_tugas }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-sm text-gray-500">Belum ada log aktivitas pengangkutan sampah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>
    <x-petugas-bottom-nav />
</x-app-layout>
