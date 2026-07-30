<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="w-full max-w-2xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6" x-data="qrLocationForm()">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Buat Sesi Presensi Baru</h2>
            <a href="{{ route('admin.qr_sessions.index') }}" class="text-blue-600 hover:underline">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 relative z-0">
            <form action="{{ route('admin.qr_sessions.store') }}" method="POST" @submit.prevent="submitForm">
                @csrf
                
                <div class="mb-5">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Nama/Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Rapat Sosialisasi Senin">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Lokasi Presensi (Koordinat GPS) <span class="text-red-500">*</span></label>
                    
                    <div class="mb-3">
                        <select x-model="presetLocation" @change="handlePresetChange()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="custom">-- Pilih Lokasi Preset atau Isi Manual --</option>
                            <option value="aula">Aula BKKBN</option>
                            <option value="lapangan">Lapangan Apel</option>
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mb-3">
                        <div class="flex-1">
                            <input type="number" step="any" id="latitude" name="latitude" x-model="latitude" @input="updateMap()" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Latitude">
                        </div>
                        <div class="flex-1">
                            <input type="number" step="any" id="longitude" name="longitude" x-model="longitude" @input="updateMap()" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Longitude">
                        </div>
                    </div>
                    
                    <button type="button" @click="getLocation()" class="mb-3 text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center gap-2 w-full sm:w-auto transition-colors" :disabled="loading">
                        <i class="fa-solid fa-location-dot" x-show="!loading"></i>
                        <i class="fa-solid fa-spinner fa-spin" x-show="loading"></i>
                        <span x-text="loading ? 'Mencari lokasi...' : 'Gunakan Lokasi Saat Ini (Deteksi GPS)'"></span>
                    </button>
                    <p class="mb-3 text-xs text-gray-500">Pastikan Anda berada di ruangan tempat peserta akan memindai QR Code saat membuat sesi ini, atau pilih dari daftar preset, atau klik pada peta di bawah ini.</p>

                    <!-- Peta Simulasi -->
                    <div id="map" class="w-full h-64 sm:h-80 rounded-lg border border-gray-300 mb-2 relative z-0"></div>
                    <p class="text-xs text-gray-500 mb-2">Peta di atas adalah simulasi jangkauan radius presensi. Peserta harus berada di dalam lingkaran biru.</p>

                    <p x-show="errorMsg" x-text="errorMsg" class="mt-2 text-sm text-red-600"></p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mb-5">
                    <div class="flex-1">
                        <div class="h-12 mb-2">
                            <label for="radius_meters" class="block text-sm font-medium text-gray-900 leading-tight">Batas Jarak (Radius)<br>dalam Meter <span class="text-red-500">*</span></label>
                        </div>
                        <input type="number" id="radius_meters" name="radius_meters" x-model="radius" @input="updateMap()" required min="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: 30">
                        <p class="mt-2 text-xs text-gray-500">Rekomendasi: 30-50 meter.</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start h-12 mb-2">
                            <label for="refresh_time_seconds" class="block text-sm font-medium text-gray-900 leading-tight pr-1">Waktu Refresh QR <span class="text-red-500">*</span></label>
                            <div class="flex items-center mt-0.5 shrink-0">
                                <input id="enable_refresh" type="checkbox" x-model="enableRefresh" class="w-3.5 h-3.5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="enable_refresh" class="ms-1.5 text-xs font-medium text-gray-700 cursor-pointer">Aktif</label>
                            </div>
                        </div>

                        <input type="number" id="refresh_time_seconds" name="refresh_time_seconds" x-model="refreshTime" :disabled="!enableRefresh" :required="enableRefresh" min="10" max="3600" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 disabled:opacity-50 disabled:bg-gray-200" placeholder="Contoh: 30">
                        <!-- Hidden input to submit 0 if disabled -->
                        <input type="hidden" name="refresh_time_seconds" x-bind:value="0" x-bind:disabled="enableRefresh">
                        <p class="mt-2 text-xs text-gray-500">Maksimal: 3600 detik (1 jam).</p>
                    </div>
                    <div class="flex-1">
                        <div class="h-12 mb-2">
                            <label for="end_time" class="block text-sm font-medium text-gray-900 leading-tight">Waktu Berakhir Sesi</label>
                        </div>
                        <input type="datetime-local" id="end_time" name="end_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <p class="mt-2 text-xs text-gray-500">Opsional. Sesi QR otomatis tertutup pada waktu ini.</p>
                    </div>
                </div>

                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-3 text-center transition-colors">Buat Sesi & Generate QR</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qrLocationForm', () => ({
                presetLocation: 'custom',
                latitude: '',
                longitude: '',
                radius: 30,
                loading: false,
                errorMsg: '',
                enableRefresh: true,
                refreshTime: 30,
                
                map: null,
                marker: null,
                circle: null,

                init() {
                    // Initialize Leaflet Map
                    let initialLat = 5.569886; // Default to Aula
                    let initialLng = 95.342183;

                    this.map = L.map('map').setView([initialLat, initialLng], 18);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    // Allow clicking on map to set coordinate
                    this.map.on('click', (e) => {
                        this.latitude = e.latlng.lat;
                        this.longitude = e.latlng.lng;
                        this.presetLocation = 'custom';
                        this.updateMap();
                    });
                    
                    // Delay update map to ensure container is fully rendered if needed
                    setTimeout(() => {
                        this.map.invalidateSize();
                        // If coordinates exist (e.g. back navigation), update map
                        if(this.latitude && this.longitude) {
                            this.updateMap();
                        }
                    }, 500);
                },

                handlePresetChange() {
                    if (this.presetLocation === 'aula') {
                        this.latitude = 5.569886;
                        this.longitude = 95.342183;
                    } else if (this.presetLocation === 'lapangan') {
                        this.latitude = 5.569968;
                        this.longitude = 95.342141;
                    }
                    this.updateMap();
                },

                updateMap() {
                    if (!this.latitude || !this.longitude) return;
                    
                    let lat = parseFloat(this.latitude);
                    let lng = parseFloat(this.longitude);
                    let r = parseFloat(this.radius) || 0;

                    if (isNaN(lat) || isNaN(lng)) return;

                    let latlng = [lat, lng];
                    
                    if (!this.marker) {
                        this.marker = L.marker(latlng).addTo(this.map);
                    } else {
                        this.marker.setLatLng(latlng);
                    }

                    if (!this.circle) {
                        this.circle = L.circle(latlng, {
                            color: 'blue',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.2,
                            radius: r
                        }).addTo(this.map);
                    } else {
                        this.circle.setLatLng(latlng);
                        this.circle.setRadius(r);
                    }

                    this.map.setView(latlng, 18);
                },

                submitForm(e) {
                    if (this.enableRefresh && this.refreshTime < 10) {
                        alert("Waktu refresh minimal 10 detik jika diaktifkan.");
                        return;
                    }
                    e.target.submit();
                },

                getLocation() {
                    if (!navigator.geolocation) {
                        this.errorMsg = 'Browser Anda tidak mendukung Geolocation API.';
                        return;
                    }

                    this.loading = true;
                    this.errorMsg = '';

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.presetLocation = 'custom';
                            this.loading = false;
                            this.updateMap();
                        },
                        (error) => {
                            this.loading = false;
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    this.errorMsg = "Anda menolak permintaan akses lokasi.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    this.errorMsg = "Informasi lokasi tidak tersedia.";
                                    break;
                                case error.TIMEOUT:
                                    this.errorMsg = "Waktu permintaan lokasi habis (timeout).";
                                    break;
                                case error.UNKNOWN_ERROR:
                                    this.errorMsg = "Terjadi kesalahan yang tidak diketahui.";
                                    break;
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                }
            }));
        });
    </script>
</x-layout>
