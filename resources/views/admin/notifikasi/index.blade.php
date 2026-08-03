<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100 pb-24 md:pb-0">
        <x-admin-sidebar />
        <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'template', openAddModalTpl: false, openAddModalJadwal: false }">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- NAV TAB TEMPLATE VS JADWAL -->
            <div class="flex border-b border-gray-200 bg-white rounded-t-lg px-4 pt-4">
                <button @click="activeTab = 'template'" 
                        :class="activeTab === 'template' ? 'border-green-600 text-green-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-3 px-6 border-b-2 font-medium text-sm transition flex items-center gap-2">
                    💬 Template Pesan & Notifikasi
                </button>
                <button @click="activeTab = 'jadwal'" 
                        :class="activeTab === 'jadwal' ? 'border-green-600 text-green-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-3 px-6 border-b-2 font-medium text-sm transition flex items-center gap-2">
                    ⏰ Jadwal Pengiriman Otomatis
                </button>
            </div>

            <!-- TAB 1: KELOLA TEMPLATE NOTIFIKASI -->
            <div x-show="activeTab === 'template'" class="bg-white p-6 shadow rounded-b-lg space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Master Template Pesan</h3>
                        <p class="text-xs text-gray-500">Gunakan placeholder: <code class="bg-gray-100 px-1 py-0.5 text-red-600 rounded">{nama}</code>, <code class="bg-gray-100 px-1 py-0.5 text-red-600 rounded">{nominal}</code>, <code class="bg-gray-100 px-1 py-0.5 text-red-600 rounded">{bulan}</code>, <code class="bg-gray-100 px-1 py-0.5 text-red-600 rounded">{tanggal}</code></p>
                    </div>
                    <button @click="openAddModalTpl = true" class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition">
                        + Tambah Template
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                                <th class="p-3">Kode</th>
                                <th class="p-3">Judul Template</th>
                                <th class="p-3">Saluran</th>
                                <th class="p-3">Preview Isi Pesan</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($templates as $tpl)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-bold text-gray-700">{{ $tpl->kode_template }}</td>
                                    <td class="p-3 font-semibold">{{ $tpl->judul_template }}</td>
                                    <td class="p-3">
                                        @if($tpl->saluran == 'whatsapp')
                                            <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded">WhatsApp</span>
                                        @elseif($tpl->saluran == 'email')
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded">Email</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-bold rounded">Push App</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-xs text-gray-600 max-w-xs truncate">{{ $tpl->isi_pesan }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded {{ $tpl->is_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $tpl->is_aktif ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center flex justify-center gap-2">
                                        <form action="{{ route('admin.notifikasi.template.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada template notifikasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: KELOLA JADWAL PENGIRIMAN OTOMATIS -->
            <div x-show="activeTab === 'jadwal'" class="bg-white p-6 shadow rounded-b-lg space-y-4" x-cloak>
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Aturan Pengiriman Otomatis (Cron / Queue)</h3>
                        <p class="text-xs text-gray-500">Atur kapan notifikasi dikirimkan secara otomatis oleh sistem.</p>
                    </div>
                    <button @click="openAddModalJadwal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition">
                        + Tambah Jadwal Pengiriman
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                                <th class="p-3">Nama Aturan Jadwal</th>
                                <th class="p-3">Template Pesan Target</th>
                                <th class="p-3">Pemicu (Trigger)</th>
                                <th class="p-3">Waktu Eksekusi</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($jadwalList as $j)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-semibold text-gray-900">{{ $j->nama_jadwal }}</td>
                                    <td class="p-3 text-indigo-600 font-medium">{{ $j->template->judul_template ?? '-' }}</td>
                                    <td class="p-3 capitalize font-bold text-gray-700">{{ $j->pemicu }}</td>
                                    <td class="p-3 text-xs text-gray-600">
                                        Jam: {{ $j->waktu_kirim }} 
                                        @if($j->hari_ke)
                                            (Tgl/Hari ke-{{ $j->hari_ke }})
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded {{ $j->is_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $j->is_aktif ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <form action="{{ route('admin.notifikasi.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada jadwal pengiriman otomatis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

            <!-- MODAL TAMBAH TEMPLATE -->
            <div x-show="openAddModalTpl" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Buat Template Notifikasi</h3>
                    <form action="{{ route('admin.notifikasi.template.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-gray-700">Kode Template (unik)</label>
                            <input type="text" name="kode_template" required placeholder="TPL_TAGIHAN_WA" class="w-full text-xs rounded border-gray-300 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">Judul Template</label>
                            <input type="text" name="judul_template" required placeholder="Pengingat Iuran Bulanan" class="w-full text-xs rounded border-gray-300 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">Saluran Notifikasi</label>
                            <select name="saluran" class="w-full text-xs rounded border-gray-300 mt-1">
                                <option value="whatsapp">WhatsApp</option>
                                <option value="email">Email</option>
                                <option value="push">Push Notification</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">Subjek (Khusus Email)</label>
                            <input type="text" name="subjek" placeholder="Tagihan Iuran Sampah Bulan Ini" class="w-full text-xs rounded border-gray-300 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">Isi Pesan</label>
                            <textarea name="isi_pesan" rows="4" required placeholder="Halo {nama}, tagihan iuran sampah bulan {bulan} sebesar Rp {nominal} sudah terbit..." class="w-full text-xs rounded border-gray-300 mt-1"></textarea>
                        </div>
                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <button type="button" @click="openAddModalTpl = false" class="px-3 py-1.5 bg-gray-200 text-xs font-semibold rounded">Batal</button>
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700">Simpan Template</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL TAMBAH JADWAL -->
            <div x-show="openAddModalJadwal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Buat Jadwal Pengiriman Otomatis</h3>
                    <form action="{{ route('admin.notifikasi.jadwal.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-gray-700">Pilih Template Target</label>
                            <select name="template_id" required class="w-full text-xs rounded border-gray-300 mt-1">
                                @foreach($templates as $t)
                                    <option value="{{ $t->id }}">{{ $t->judul_template }} ({{ strtoupper($t->saluran) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">Nama Aturan Jadwal</label>
                            <input type="text" name="nama_jadwal" required placeholder="Kirim Tagihan Tiap Tgl 25" class="w-full text-xs rounded border-gray-300 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">Pemicu Eksekusi</label>
                            <select name="pemicu" class="w-full text-xs rounded border-gray-300 mt-1">
                                <option value="bulanan">Bulanan (Tanggal tertentu)</option>
                                <option value="mingguan">Mingguan (Hari tertentu)</option>
                                <option value="harian">Harian (Setiap hari)</option>
                                <option value="event">Event Trigger (Berdasarkan Kejadian)</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs font-bold text-gray-700">Jam Eksekusi</label>
                                <input type="time" name="waktu_kirim" value="08:00" required class="w-full text-xs rounded border-gray-300 mt-1">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-700">Hari / Tanggal Ke-</label>
                                <input type="number" name="hari_ke" placeholder="Contoh: 25" class="w-full text-xs rounded border-gray-300 mt-1">
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <button type="button" @click="openAddModalJadwal = false" class="px-3 py-1.5 bg-gray-200 text-xs font-semibold rounded">Batal</button>
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
    <x-admin-bottom-nav />
</x-app-layout>