<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="max-w-5xl mx-auto space-y-6 animate-[fadeIn_0.5s_ease-out]">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('absensi-zoom', ['tab' => 'kegiatan', 'event' => $eventName]) }}" class="hover:text-blue-600 transition-colors font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Ringkasan Kegiatan
            </a>
        </div>

        <!-- HEADER SECTION -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-800 to-cyan-600 p-8 sm:p-12 text-white shadow-2xl transition-transform duration-500 group">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 group-hover:scale-105 transition-transform duration-1000"></div>
            <!-- Glassmorphism decorative circles -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <!-- Avatar -->
                @php
                    $logoFileName = $city . '.png';
                    if (!file_exists(public_path('image/logo-kab-kota/' . $logoFileName))) {
                        $logoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($city) . '&background=0D8ABC&color=fff&rounded=true&bold=true&size=100';
                    } else {
                        $logoUrl = asset('public/image/logo-kab-kota/' . rawurlencode($logoFileName));
                    }
                @endphp
                <div class="w-32 h-32 rounded-[2rem] bg-white shadow-[0_15px_40px_rgba(0,0,0,0.25)] p-4 flex items-center justify-center border border-white/20 transform hover:scale-105 transition-transform duration-500 relative group-hover:shadow-[0_20px_50px_rgba(255,255,255,0.2)]">
                    <img src="{{ $logoUrl }}" alt="Logo {{ $city }}" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
                
                <div class="text-center md:text-left flex-1">
                    <div class="text-cyan-200 font-bold uppercase tracking-widest text-sm mb-2 drop-shadow-sm"><i class="fas fa-map-marker-alt mr-1"></i> Daftar Hadir Peserta</div>
                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-lg text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-100">
                        {{ $city }}
                    </h1>
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-md rounded-xl px-4 py-2 border border-white/30 shadow-inner">
                        <i class="fas fa-video text-yellow-300 mr-2 text-lg"></i>
                        <span class="text-sm font-medium">{{ $eventName }}</span>
                    </div>
                </div>
                
                <!-- Stat Box -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-xl min-w-[150px] text-center transform hover:scale-105 transition-transform">
                    <div class="text-xs text-blue-200 font-bold uppercase tracking-wider mb-2">Total Peserta</div>
                    <div class="text-5xl font-black text-white drop-shadow-md">{{ count($attendees) }}</div>
                </div>
            </div>
        </div>

        <!-- LIST SECTION -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 overflow-hidden relative">
            <div class="p-6 sm:p-8 bg-gray-50/50 border-b border-gray-100">
                <h3 class="text-xl font-extrabold text-gray-800 flex items-center">
                    <i class="fas fa-users mr-3 text-blue-600"></i> Nama-Nama Peserta
                </h3>
            </div>
            
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($attendees as $index => $attendee)
                        <div class="group bg-white p-4 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 flex items-center gap-4 transition-all hover:-translate-y-1 hover:border-blue-200 cursor-default relative overflow-hidden">
                            <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-blue-400 to-indigo-600 rounded-l-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 flex items-center justify-center flex-shrink-0 font-bold text-sm shadow-inner relative z-10">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-800 text-sm truncate group-hover:text-blue-700 transition-colors" title="{{ $attendee['name'] }}">{{ $attendee['name'] }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800 border border-gray-200 uppercase tracking-wider group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                        {{ $attendee['unsur'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5 flex items-center"><i class="fas fa-check-circle text-green-500 mr-1.5 opacity-70"></i> Hadir di event ini</p>
                            </div>
                            <a href="{{ route('absensi-zoom.person', ['name' => $attendee['name']]) }}" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors flex-shrink-0 relative z-10 shadow-sm border border-gray-100" title="Lihat Profil">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fas fa-user-slash text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-600 mb-1">Tidak ada data peserta</h3>
                            <p class="text-gray-400 text-sm">Belum ada peserta yang tercatat dari kota ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-layout>
