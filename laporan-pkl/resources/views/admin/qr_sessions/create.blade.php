<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full max-w-2xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6" x-data="qrLocationForm()">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Buat Sesi Presensi Baru</h2>
            <a href="{{ route('admin.qr_sessions.index') }}" class="text-blue-600 hover:underline">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6">
            <form action="{{ route('admin.qr_sessions.store') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Nama/Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Rapat Sosialisasi Senin">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Lokasi Presensi (Koordinat GPS) <span class="text-red-500">*</span></label>
                    
                    <div class="flex flex-col sm:flex-row gap-3 mb-3">
                        <div class="flex-1">
                            <input type="number" step="any" id="latitude" name="latitude" x-model="latitude" required readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Latitude">
                        </div>
                        <div class="flex-1">
                            <input type="number" step="any" id="longitude" name="longitude" x-model="longitude" required readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Longitude">
                        </div>
                    </div>
                    
                    <button type="button" @click="getLocation()" class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center gap-2 w-full sm:w-auto transition-colors" :disabled="loading">
                        <i class="fa-solid fa-location-dot" x-show="!loading"></i>
                        <i class="fa-solid fa-spinner fa-spin" x-show="loading"></i>
                        <span x-text="loading ? 'Mencari lokasi...' : 'Gunakan Lokasi Saat Ini (Deteksi GPS)'"></span>
                    </button>
                    <p x-show="errorMsg" x-text="errorMsg" class="mt-2 text-sm text-red-600"></p>
                    <p class="mt-2 text-xs text-gray-500">Pastikan Anda berada di ruangan tempat peserta akan memindai QR Code saat membuat sesi ini.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mb-5">
                    <div class="flex-1">
                        <label for="radius_meters" class="block mb-2 text-sm font-medium text-gray-900">Batas Jarak (Radius) dalam Meter <span class="text-red-500">*</span></label>
                        <input type="number" id="radius_meters" name="radius_meters" value="30" required min="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: 30">
                        <p class="mt-2 text-xs text-gray-500">Rekomendasi: 30-50 meter.</p>
                    </div>
                    <div class="flex-1">
                        <label for="refresh_time_seconds" class="block mb-2 text-sm font-medium text-gray-900">Waktu Refresh QR (Detik) <span class="text-red-500">*</span></label>
                        <input type="number" id="refresh_time_seconds" name="refresh_time_seconds" value="30" required min="10" max="300" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: 30">
                        <p class="mt-2 text-xs text-gray-500">Rekomendasi: 30-60 detik.</p>
                    </div>
                    <div class="flex-1">
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900">Waktu Berakhir Sesi</label>
                        <input type="datetime-local" id="end_time" name="end_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <p class="mt-2 text-xs text-gray-500">Opsional. Sesi QR otomatis tertutup pada waktu ini.</p>
                    </div>
                </div>

                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-3 text-center">Buat Sesi & Generate QR</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qrLocationForm', () => ({
                latitude: '',
                longitude: '',
                loading: false,
                errorMsg: '',

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
                            this.loading = false;
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
