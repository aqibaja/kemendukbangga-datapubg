<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="min-h-[80vh] flex flex-col lg:flex-row gap-8 items-start justify-center p-4 xl:p-8 mx-auto w-full transition-all duration-700 ease-in-out" :class="isEnded ? 'max-w-[100vw] h-screen !p-0' : 'max-w-[1600px]'" x-data="qrProjector({{ $session->id }})">
        
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

        <!-- Kolom Kanan: Dashboard Google Data Studio -->
        <div class="flex-grow w-full rounded-3xl overflow-hidden shadow-2xl bg-white border border-gray-100 flex flex-col transition-all duration-700" :class="isEnded ? 'h-[100vh] !rounded-none' : 'lg:w-auto h-[70vh] lg:h-[85vh]'">
            <div class="bg-gray-50 border-b border-gray-100 p-4 flex justify-between items-center">
                <h2 class="font-bold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-blue-500"></i>
                    <span x-text="isEnded ? 'Sesi Presensi Selesai - Rekap Data' : 'Live Dashboard Presensi'"></span>
                </h2>
                <a href="https://datastudio.google.com/embed/u/0/reporting/95857558-0c38-4f16-b37a-58df2c8eaba6/page/nqrlF" target="_blank" class="text-xs text-blue-600 hover:underline">
                    Buka layar penuh <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                </a>
            </div>
            <iframe 
                class="w-full flex-grow" 
                src="https://datastudio.google.com/embed/u/0/reporting/95857558-0c38-4f16-b37a-58df2c8eaba6/page/nqrlF" 
                frameborder="0" 
                style="border:0" 
                allowfullscreen 
                sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
            </iframe>
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
                isEnded: false,
                endTimeStamp: {{ $session->end_time ? $session->end_time->timestamp * 1000 : 'null' }},
                refreshTime: {{ $session->refresh_time_seconds }},
                timeLeft: {{ $session->refresh_time_seconds }},
                progress: 100,
                interval: null,
                endCheckInterval: null,

                init() {
                    this.checkEndTime();
                    if (!this.isEnded) {
                        this.fetchQr();
                        this.endCheckInterval = setInterval(() => this.checkEndTime(), 1000);
                    }
                },

                checkEndTime() {
                    if (this.endTimeStamp && Date.now() >= this.endTimeStamp) {
                        this.isEnded = true;
                        clearInterval(this.interval);
                        clearInterval(this.endCheckInterval);
                    }
                },

                fetchQr() {
                    if (this.isEnded) return;
                    this.loading = true;
                    this.error = false;
                    
                    fetch(`/admin/qr-sessions/${this.sessionId}/generate`)
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 403) {
                                    return response.json().then(data => {
                                        if (data.is_ended) {
                                            this.isEnded = true;
                                            clearInterval(this.interval);
                                            throw new Error('Sesi telah berakhir.');
                                        }
                                        throw new Error(data.error || 'Gagal memuat QR Code.');
                                    });
                                }
                                throw new Error('Gagal memuat QR Code. Pastikan sesi masih aktif.');
                            }
                            return response.json();
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
