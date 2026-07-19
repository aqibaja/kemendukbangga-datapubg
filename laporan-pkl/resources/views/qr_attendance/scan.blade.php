<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi QR: {{ $session->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-control {
            padding: 12px 16px !important;
            border-radius: 0.5rem !important;
            border-color: #d1d5db !important;
        }
        .ts-control.focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" x-data="qrAttendanceForm()">
        <div class="bg-blue-600 p-6 text-center text-white">
            <h1 class="text-xl font-bold mb-1">Presensi Kehadiran</h1>
            <p class="text-blue-100 text-sm">{{ $session->title }}</p>
        </div>

        <div class="p-6">
            
            <div x-show="step === 1">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-location-dot text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Verifikasi Lokasi</h2>
                    <p class="text-sm text-gray-500 mt-2">Sistem membutuhkan akses lokasi untuk memastikan Anda berada di lokasi kegiatan.</p>
                </div>

                <button @click="getLocation()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl transition-colors flex justify-center items-center gap-2" :disabled="loading">
                    <i class="fa-solid fa-location-crosshairs" x-show="!loading"></i>
                    <i class="fa-solid fa-spinner fa-spin" x-show="loading"></i>
                    <span x-text="loading ? 'Memeriksa lokasi...' : 'Izinkan Akses Lokasi'"></span>
                </button>
                <p x-show="errorMsg" x-text="errorMsg" class="mt-3 text-sm text-red-600 text-center font-medium"></p>
            </div>

            <div x-show="step === 2" style="display: none;">
                <div class="mb-5 text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Nama Anda</label>
                    <div wire:ignore>
                        <select id="employee_select" class="w-full">
                            <option value="">-- Ketik untuk mencari nama --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->nama }} {{ $emp->unsur ? '('.$emp->unsur.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button @click="submitAttendance()" :disabled="!employeeId || submitting" class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium py-3 rounded-xl transition-colors flex justify-center items-center gap-2">
                    <i class="fa-solid fa-paper-plane" x-show="!submitting"></i>
                    <i class="fa-solid fa-spinner fa-spin" x-show="submitting"></i>
                    <span x-text="submitting ? 'Mengirim Data...' : 'Kirim Presensi'"></span>
                </button>
                <p x-show="submitError" x-text="submitError" class="mt-3 text-sm text-red-600 text-center font-medium"></p>
            </div>

            <div x-show="step === 3" style="display: none;" class="text-center py-6">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-check text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Berhasil!</h2>
                <p class="text-gray-600 mb-6">Presensi Anda telah berhasil dicatat ke dalam sistem.</p>
                <p class="text-xs text-gray-400">Anda sudah boleh menutup halaman ini.</p>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qrAttendanceForm', () => ({
                step: 1,
                loading: false,
                submitting: false,
                latitude: null,
                longitude: null,
                errorMsg: '',
                submitError: '',
                employeeId: '',
                token: '{{ $token }}',
                tomSelectInstance: null,

                init() {
                    this.tomSelectInstance = new TomSelect('#employee_select', {
                        create: false,
                        placeholder: '-- Ketik untuk mencari nama --',
                        onChange: (value) => {
                            this.employeeId = value;
                        }
                    });
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
                            this.loading = false;
                            this.step = 2; // Lanjut ke pilih nama
                        },
                        (error) => {
                            this.loading = false;
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    this.errorMsg = "Anda menolak permintaan akses lokasi. Mohon izinkan agar bisa absen.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    this.errorMsg = "Informasi lokasi tidak tersedia di HP Anda.";
                                    break;
                                case error.TIMEOUT:
                                    this.errorMsg = "Waktu permintaan lokasi habis (timeout).";
                                    break;
                                default:
                                    this.errorMsg = "Terjadi kesalahan saat mengambil lokasi GPS.";
                                    break;
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                    );
                },

                submitAttendance() {
                    if (!this.employeeId || !this.latitude || !this.longitude) return;

                    this.submitting = true;
                    this.submitError = '';

                    fetch('{{ route('qr_attendance.submit') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            token: this.token,
                            employee_id: this.employeeId,
                            latitude: this.latitude,
                            longitude: this.longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.submitting = false;
                        if (data.success) {
                            this.step = 3; // Berhasil
                        } else {
                            this.submitError = data.message;
                        }
                    })
                    .catch(error => {
                        this.submitting = false;
                        this.submitError = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                    });
                }
            }));
        });
    </script>
</body>
</html>
