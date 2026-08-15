<div x-data="chatbot()">
    <!-- Tombol Melayang -->
    <button type="button"
            @click="buka = !buka"
            :aria-label="buka ? 'Tutup chatbot' : 'Buka chatbot'"
            class="fixed bottom-5 right-5 z-[60] h-14 w-14 rounded-full bg-green-600 hover:bg-green-700 text-white shadow-xl shadow-green-300/60 flex items-center justify-center transition">
        <svg x-show="!buka" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="buka" x-cloak class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Panel Chat -->
    <div x-show="buka" x-cloak x-transition
         class="fixed bottom-24 right-5 z-[60] w-[380px] max-w-[calc(100vw-2.5rem)] bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden"
         style="height: 500px; max-height: calc(100vh - 8rem);">

        <!-- Header -->
        <div class="bg-green-600 text-white px-4 py-3 flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm leading-tight">Asisten SIMPERSA</p>
                <p class="text-[11px] text-green-100 flex items-center gap-1">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-300 inline-block"></span>
                    Online
                </p>
            </div>
        </div>

        <!-- Pesan -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50" x-ref="kotakPesan">
            <template x-for="(item, i) in riwayat" :key="i">
                <div class="flex" :class="item.dari === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[85%] px-3 py-2 rounded-2xl text-sm whitespace-pre-line leading-relaxed"
                         :class="item.dari === 'user' ? 'bg-green-600 text-white rounded-br-sm' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-sm shadow-sm'"
                         x-text="item.teks"></div>
                </div>
            </template>
            <div x-show="mengetik" x-cloak class="flex justify-start">
                <div class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm shadow-sm px-4 py-2.5">
                    <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>
                </div>
            </div>
        </div>

        <!-- Chips Pertanyaan Cepat -->
        <div class="px-3 pt-2 flex flex-wrap gap-1.5 border-t border-gray-100 bg-white">
            <template x-for="chip in chips" :key="chip">
                <button type="button" @click="kirimCepat(chip)"
                        class="text-[11px] font-medium text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 rounded-full px-2.5 py-1 transition"
                        x-text="chip"></button>
            </template>
        </div>

        <!-- Input -->
        <form @submit.prevent="kirim()" class="p-3 bg-white flex items-center gap-2 border-t border-gray-100">
            <input type="text" x-model="pesan" placeholder="Ketik pertanyaan..." required maxlength="500"
                   class="flex-1 rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" />
            <button type="submit" :disabled="mengetik || !pesan.trim()"
                    class="h-10 w-10 rounded-xl bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white flex items-center justify-center transition shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>

    @push('scripts')
    <style>
        .typing-dot { display: inline-block; width: 6px; height: 6px; border-radius: 9999px; background: #9ca3af; animation: typingBlink 1.2s infinite; }
        .typing-dot:nth-child(2) { animation-delay: .2s; }
        .typing-dot:nth-child(3) { animation-delay: .4s; }
        @keyframes typingBlink { 0%, 60%, 100% { opacity: .25; } 30% { opacity: 1; } }
    </style>
    <script>
        function chatbot() {
            return {
                buka: false,
                pesan: '',
                mengetik: false,
                riwayat: [{
                    dari: 'bot',
                    teks: 'Halo! Saya asisten SIMPERSA. Tanya seputar stok sampah, saldo tabungan, transaksi, atau fitur aplikasi.'
                }],
                chips: ['Berapa stok sampah?', 'Saldo saya', 'Jumlah nasabah', 'Fitur apa saja?'],
                kirimCepat(chip) {
                    this.pesan = chip;
                    this.kirim();
                },
                kirim() {
                    const isi = this.pesan.trim();
                    if (!isi || this.mengetik) return;

                    this.riwayat.push({ dari: 'user', teks: isi });
                    this.pesan = '';
                    this.mengetik = true;
                    this.gulirBawah();

                    fetch('/chatbot/tanya', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ pesan: isi })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.riwayat.push({ dari: 'bot', teks: data.jawaban || 'Maaf, terjadi kendala. Coba lagi ya.' });
                    })
                    .catch(() => {
                        this.riwayat.push({ dari: 'bot', teks: 'Maaf, saya gagal terhubung. Coba lagi nanti.' });
                    })
                    .finally(() => {
                        this.mengetik = false;
                        this.gulirBawah();
                    });
                },
                gulirBawah() {
                    this.$nextTick(() => {
                        const el = this.$refs.kotakPesan;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            }
        }
    </script>
    @endpush
</div>