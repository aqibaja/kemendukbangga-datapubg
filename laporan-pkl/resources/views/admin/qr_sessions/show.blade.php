<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="max-w-[1600px] min-h-[80vh] flex flex-col lg:flex-row gap-8 items-start justify-center p-4 xl:p-8 mx-auto w-full transition-all duration-700 ease-in-out" x-data="qrProjector({{ $session->id }})">
        
        <!-- Kolom Kiri: QR Code -->
        <div class="flex-none w-full lg:w-[400px] flex flex-col items-center transition-all duration-500" x-show="!isEnded" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 w-0 overflow-hidden">
            <div class="mb-8 text-center w-full">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">{{ $session->title }}</h1>
                <p class="text-gray-500 text-lg">Silakan scan QR Code di bawah untuk melakukan presensi</p>
            </div>

            @if($session->is_active)
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl relative w-full">
                    
                    <!-- Countdown Progress Bar -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gray-100 rounded-t-3xl overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-1000 ease-linear" :style="`width: ${progress}%`"></div>
                    </div>

                    <div class="flex justify-center items-center p-4 bg-white rounded-xl w-full aspect-square">
                        <!-- Loading state -->
                        <div x-show="loading" class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fa-solid fa-spinner fa-spin text-4xl mb-3"></i>
                            <span class="text-sm font-medium">Memuat QR Code...</span>
                        </div>

                        <!-- Error state -->
                        <div x-show="error" class="flex flex-col items-center justify-center text-red-500 text-center" style="display: none;">
                            <i class="fa-solid fa-circle-exclamation text-4xl mb-3"></i>
                            <span class="text-sm font-medium" x-text="errorMsg"></span>
                        </div>

                        <!-- QR Code SVG injected here -->
                        <div x-show="!loading && !error" x-html="qrSvg" class="w-full h-full flex justify-center items-center"></div>
                    </div>
                </div>

                <div class="mt-8 text-center w-full">
                    <p class="text-sm text-gray-500 font-medium bg-blue-50 px-4 py-3 rounded-xl border border-blue-100">
                        <i class="fa-solid fa-shield-halved text-blue-500 mr-1"></i> QR dinamis berganti setiap {{ $session->refresh_time_seconds }} detik untuk mencegah kecurangan.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.qr_sessions.index') }}" class="text-gray-400 hover:text-gray-600 underline text-sm transition">Kembali ke Daftar Sesi</a>
                    </div>
                </div>
        </div>

        <!-- Kolom Kanan: Dashboard Apel Senin -->
        <div class="flex-grow w-full rounded-3xl overflow-hidden shadow-2xl bg-white border border-gray-100 flex flex-col transition-all duration-700 h-[70vh] lg:h-[85vh]">
            <div class="bg-gray-50 border-b border-gray-100 p-3 sm:p-4 flex flex-wrap justify-between items-center gap-3">
                <h2 class="font-bold text-gray-700 flex items-center gap-2 flex-wrap">
                    <i class="fa-solid fa-chart-pie text-blue-500"></i>
                    <span x-text="isEnded ? 'Sesi Presensi Selesai - Rekap Data' : 'Live Dashboard Presensi'"></span>
                    <button 
                        @click="triggerManualReload()" 
                        type="button" 
                        class="text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 px-3 py-1.5 rounded-full border border-blue-200 inline-flex items-center gap-1.5 shadow-sm transition active:scale-95 cursor-pointer group" 
                        title="Klik untuk memuat ulang data dari Google Sheets secara langsung"
                    >
                        <i class="fa-solid fa-rotate text-blue-500" :class="isManualReloading ? 'animate-spin' : ''"></i>
                        <span>Reload data: <strong class="font-mono font-bold text-blue-900" x-text="formattedAutoReloadTime">02:00</strong></span>
                        <span class="bg-blue-600 group-hover:bg-blue-700 text-white text-[10px] px-1.5 py-0.5 rounded-md font-bold uppercase tracking-wider ml-0.5 shadow-xs">Reload Sekarang</span>
                    </button>
                </h2>

                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Zoom In / Zoom Out Controls -->
                    <div class="inline-flex items-center bg-white border border-gray-200 rounded-xl p-1 shadow-sm text-xs gap-1">
                        <button @click="zoomOut()" type="button" class="px-2 py-1 hover:bg-gray-100 text-gray-700 rounded-lg font-bold transition flex items-center gap-1" title="Zoom Out (-10%)">
                            <i class="fa-solid fa-magnifying-glass-minus text-blue-600"></i>
                        </button>
                        <button @click="resetZoom()" type="button" class="px-2 py-1 hover:bg-blue-50 text-blue-700 font-mono font-bold transition min-w-[50px] text-center rounded-lg" title="Klik untuk reset zoom (70%)">
                            <span x-text="zoomLevel + '%'">70%</span>
                        </button>
                        <button @click="zoomIn()" type="button" class="px-2 py-1 hover:bg-gray-100 text-gray-700 rounded-lg font-bold transition flex items-center gap-1" title="Zoom In (+10%)">
                            <i class="fa-solid fa-magnifying-glass-plus text-blue-600"></i>
                        </button>
                    </div>

                    <a href="{{ url('/data/apel-senin') }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                        Buka layar penuh <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>

            <!-- Wrapper Container with Scale Control -->
            <div class="w-full flex-grow relative overflow-hidden bg-slate-50">
                <div 
                    class="w-full h-full origin-top-left transition-transform duration-300 ease-out"
                    :style="`width: ${10000 / zoomLevel}%; height: ${10000 / zoomLevel}%; transform: scale(${zoomLevel / 100}); transform-origin: 0 0;`"
                >
                    <iframe 
                        id="dashboard-iframe"
                        class="w-full h-full border-0" 
                        src="{{ url('/data/apel-senin?embed=1') }}" 
                        frameborder="0" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>

        @else
            <div class="bg-red-50 p-8 rounded-3xl border border-red-200 text-center max-w-lg">
                <i class="fa-solid fa-ban text-6xl text-red-400 mb-4"></i>
                <h2 class="text-2xl font-bold text-red-700 mb-2">Sesi Telah Ditutup</h2>
                <p class="text-red-600 mb-6">Presensi untuk sesi "{{ $session->title }}" sudah tidak dapat dilakukan lagi karena telah dinonaktifkan oleh Admin.</p>
                <a href="{{ route('admin.qr_sessions.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
                    Kembali
                </a>
            </div>
        @endif
    </div>

    @if($session->is_active)
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qrProjector', (sessionId) => ({
                sessionId: sessionId,
                qrSvg: '',
                loading: true,
                error: false,
                errorMsg: '',
                isEnded: false,
                isManualReloading: false,
                endTimeStamp: {{ $session->end_time ? $session->end_time->timestamp * 1000 : 'null' }},
                refreshTime: {{ $session->refresh_time_seconds }},
                timeLeft: {{ $session->refresh_time_seconds }},
                progress: 100,
                interval: null,
                endCheckInterval: null,
                autoReloadInterval: null,
                autoReloadSeconds: 120,
                zoomLevel: 70,

                get formattedAutoReloadTime() {
                    const mins = Math.floor(this.autoReloadSeconds / 60);
                    const secs = this.autoReloadSeconds % 60;
                    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                },

                triggerManualReload() {
                    this.isManualReloading = true;
                    this.autoReloadSeconds = 120;
                    const iframe = document.getElementById('dashboard-iframe');
                    if (iframe) {
                        let syncUrl = new URL(iframe.src || '{{ route("apel-senin") }}', window.location.origin);
                        syncUrl.searchParams.set('_sync', '1');
                        syncUrl.searchParams.set('t', Date.now());
                        iframe.src = syncUrl.toString();
                    }
                    setTimeout(() => {
                        this.isManualReloading = false;
                    }, 1500);
                },

                zoomIn() {
                    if (this.zoomLevel < 150) this.zoomLevel += 10;
                },
                zoomOut() {
                    if (this.zoomLevel > 30) this.zoomLevel -= 10;
                },
                resetZoom() {
                    this.zoomLevel = 70;
                },

                init() {
                    this.checkEndTime();
                    if (!this.isEnded) {
                        this.fetchQr();
                        this.startEndCheckInterval();
                    }
                    this.startAutoReloadTimer();
                },

                startAutoReloadTimer() {
                    this.autoReloadSeconds = 120;
                    clearInterval(this.autoReloadInterval);
                    this.autoReloadInterval = setInterval(() => {
                        this.autoReloadSeconds -= 1;
                        if (this.autoReloadSeconds <= 0) {
                            this.triggerManualReload();
                        }
                    }, 1000);
                },

                checkEndTime() {
                    if (this.endTimeStamp && Date.now() >= this.endTimeStamp) {
                        this.isEnded = true;
                        this.error = true;
                        this.errorMsg = 'Sesi telah berakhir secara otomatis.';
                        clearInterval(this.interval);
                        clearInterval(this.endCheckInterval);
                    }
                },

                startEndCheckInterval() {
                    if (this.endTimeStamp) {
                        this.endCheckInterval = setInterval(() => {
                            this.checkEndTime();
                        }, 5000);
                    }
                },

                fetchQr() {
                    if (this.isEnded) return;
                    this.loading = true;
                    this.error = false;
                    
                    fetch(`/admin/qr-sessions/${this.sessionId}/generate`)
                        .then(response => {
                            return response.text().then(text => {
                                let data;
                                try {
                                    data = JSON.parse(text);
                                } catch (e) {
                                    throw new Error('Terjadi kesalahan tidak terduga pada server.');
                                }
                                
                                if (!response.ok) {
                                    if (data.is_ended) {
                                        this.isEnded = true;
                                        clearInterval(this.interval);
                                        throw new Error('Sesi telah berakhir.');
                                    }
                                    throw new Error(data.error || 'Gagal memuat QR Code. Pastikan sesi masih aktif.');
                                }
                                return data;
                            });
                        })
                        .then(data => {
                            this.qrSvg = data.svg;
                            this.loading = false;
                            this.startTimer();
                        })
                        .catch(err => {
                            this.error = true;
                            this.loading = false;
                            this.errorMsg = err.message;
                        });
                },

                startTimer() {
                    clearInterval(this.interval);
                    this.timeLeft = this.refreshTime;
                    this.progress = 100;
                    
                    this.interval = setInterval(() => {
                        this.timeLeft -= 1;
                        this.progress = (this.timeLeft / this.refreshTime) * 100;
                        
                        if (this.timeLeft <= 0) {
                            clearInterval(this.interval);
                            this.fetchQr(); // Fetch next QR
                        }
                    }, 1000);
                }
            }));
        });
    </script>
    @endif
</x-layout>
