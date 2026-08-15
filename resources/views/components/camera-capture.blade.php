@props([
    'name' => 'foto',
    'label' => 'Ambil Foto',
    'facing' => 'environment',
    'required' => false,
    'hint' => '',
    'dark' => false,
])

<div x-data="cameraCapture('{{ $name }}', '{{ $facing }}')"
     class="space-y-3"
     x-init="init()">
    <div>
        <label class="block text-xs font-bold {{ $dark ? 'text-white' : 'text-gray-500' }} uppercase">{{ $label }}</label>
        <p class="text-[11px] {{ $dark ? 'text-white/70' : 'text-gray-400' }} mt-0.5">{{ $hint }}</p>
    </div>

    <input type="file" name="{{ $name }}" accept="image/*" x-ref="fileInput" {{ $required ? 'required' : '' }} class="hidden">

    <template x-if="!streamActive && !captured">
        <div class="flex flex-col items-center gap-2 p-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg">
            <p class="text-[11px] text-gray-500 text-center" x-show="!cameraError">Buka kamera untuk memotret langsung, atau gunakan file dari perangkat.</p>
            <p class="text-[11px] text-red-600 text-center" x-show="cameraError" x-text="cameraError"></p>
            <div class="flex flex-wrap gap-2 justify-center">
                <button type="button" @click="startCamera()" class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold rounded-md transition">
                    📷 Buka Kamera
                </button>
                {{-- <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-md transition">
                     Pilih File
                </button> --}}
            </div>
        </div>
    </template>

    <div x-show="streamActive" x-cloak>
        <div class="space-y-2">
            <div class="relative">
                <video x-ref="video" autoplay muted playsinline class="w-full rounded-lg border border-gray-200 bg-black" style="max-height: 320px; min-height: 180px;"></video>
                <p class="absolute top-2 left-2 bg-black/60 text-white text-[10px] font-semibold px-2 py-0.5 rounded" x-show="streamReady">📷 Kamera aktif — siap ambil foto</p>
                <p class="absolute top-2 left-2 bg-red-600/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded" x-show="streamStalled">Kamera tidak mengirim gambar. Pastikan tidak ada aplikasi lain yang memakainya (Zoom, camera app, dll).</p>
            </div>
            <div class="flex flex-wrap gap-2 justify-center">
                <button type="button" @click="capture()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-md transition">
                    📸 Ambil Foto
                </button>
                <button type="button" @click="toggleCamera()" class="px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-md transition" x-text="cameraLabel"></button>
                <button type="button" @click="stopCamera()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-md transition">
                    ✖ Batal
                </button>
            </div>
        </div>
    </div>

    <div x-show="captured" x-cloak>
        <div class="space-y-2">
            <img x-ref="preview" class="w-full rounded-lg border border-gray-200" style="max-height: 320px; object-fit: contain;">
            <div class="flex flex-wrap gap-2 justify-center">
                <button type="button" @click="retake()" class="px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-md transition">
                    🔄 Ambil Ulang
                </button>
                <button type="button" @click="clearPhoto()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-md transition">
                    🗑 Hapus Foto
                </button>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none !important;}</style>

<script>
    function cameraCapture(inputName, defaultFacing) {
        return {
            stream: null,
            streamActive: false,
            streamReady: false,
            streamStalled: false,
            stalledTimer: null,
            captured: false,
            cameraError: '',
            facing: defaultFacing === 'user' ? 'user' : 'environment',
            devices: [],
            deviceIndex: -1,

            init() {
                const self = this;
                this.$refs.fileInput.addEventListener('change', function (e) {
                    if (this.files && this.files[0]) {
                        self.captured = true;
                        self.$refs.preview.src = URL.createObjectURL(this.files[0]);
                        self.cameraError = '';
                    }
                });
                this.$refs.video.addEventListener('loadeddata', () => {
                    this.streamReady = true;
                    this.streamStalled = false;
                    if (this.stalledTimer) clearTimeout(this.stalledTimer);
                });
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    navigator.mediaDevices.enumerateDevices().then((list) => {
                        this.devices = list.filter(d => d.kind === 'videoinput');
                    }).catch(() => {});
                }
            },

            get cameraLabel() {
                return this.facing === 'user' ? '🔄 Kamera Depan' : '🔄 Kamera Belakang';
            },

            async startCamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    this.cameraError = 'Browser tidak mendukung kamera. Gunakan "Pilih File".';
                    return;
                }
                this.cameraError = '';
                this.streamReady = false;
                this.streamStalled = false;
                if (this.stalledTimer) clearTimeout(this.stalledTimer);
                try {
                    let stream;
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: this.facing },
                            audio: false
                        });
                    } catch (err) {
                        if (err && (err.name === 'OverconstrainedError' || err.name === 'NotFoundError' || err.name === 'TypeError')) {
                            stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                        } else {
                            throw err;
                        }
                    }
                    this.stream = stream;
                    this.$refs.video.srcObject = stream;
                    this.streamActive = true;
                    await this.$refs.video.play().catch(() => {});
                    this.stalledTimer = setTimeout(() => {
                        if (!this.streamReady && this.streamActive) {
                            this.streamStalled = true;
                        }
                    }, 3000);
                } catch (err) {
                    this.cameraError = 'Izin kamera ditolak. Gunakan "Pilih File" sebagai gantinya.';
                }
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(t => t.stop());
                    this.stream = null;
                }
                this.streamActive = false;
                this.streamReady = false;
                this.streamStalled = false;
                if (this.stalledTimer) clearTimeout(this.stalledTimer);
            },

            capture() {
                const video = this.$refs.video;
                if (!video || video.videoWidth === 0) return;
                try {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    canvas.toBlob((blob) => {
                        if (!blob) {
                            this.cameraError = 'Gagal memproses foto. Coba lagi atau gunakan "Pilih File".';
                            return;
                        }
                        const file = new File([blob], inputName + '-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        this.$refs.fileInput.files = dt.files;
                        this.$refs.preview.src = URL.createObjectURL(blob);
                        this.captured = true;
                        this.stopCamera();
                    }, 'image/jpeg', 0.9);
                } catch (err) {
                    this.cameraError = 'Gagal mengambil foto. Coba lagi atau gunakan "Pilih File".';
                }
            },

            toggleCamera() {
                if (this.devices.length > 1) {
                    this.deviceIndex = (this.deviceIndex + 1) % this.devices.length;
                    const deviceId = this.devices[this.deviceIndex].deviceId;
                    this.facing = this.facing === 'user' ? 'environment' : 'user';
                    const wasActive = this.streamActive;
                    this.stopCamera();
                    if (wasActive) this.startCameraWithDevice(deviceId);
                    return;
                }
                this.facing = this.facing === 'user' ? 'environment' : 'user';
                const wasActive = this.streamActive;
                this.stopCamera();
                if (wasActive) this.startCamera();
            },

            async startCameraWithDevice(deviceId) {
                this.cameraError = '';
                this.streamReady = false;
                this.streamStalled = false;
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: { deviceId: { exact: deviceId } },
                        audio: false
                    });
                    this.stream = stream;
                    this.$refs.video.srcObject = stream;
                    this.streamActive = true;
                    await this.$refs.video.play().catch(() => {});
                    this.stalledTimer = setTimeout(() => {
                        if (!this.streamReady && this.streamActive) {
                            this.streamStalled = true;
                        }
                    }, 3000);
                } catch (err) {
                    this.cameraError = 'Gagal membuka kamera tersebut.';
                }
            },

            retake() {
                this.captured = false;
                this.clearPhoto();
                this.startCamera();
            },

            clearPhoto() {
                this.$refs.fileInput.value = '';
                this.captured = false;
            }
        };
    }
</script>
