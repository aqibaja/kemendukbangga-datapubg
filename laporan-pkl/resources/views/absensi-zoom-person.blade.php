<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6 animate-[fadeIn_0.5s_ease-out]">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('absensi-zoom', ['tab' => 'peserta']) }}" class="hover:text-blue-600 transition-colors font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Peringkat Peserta
            </a>
        </div>

        <!-- PROFILE HEADER -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row gap-8 items-center md:items-start relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-60"></div>
            
            <div class="w-32 h-32 flex-shrink-0 relative z-10">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($details['name']) }}&background=EBF5FF&color=1E3A8A&rounded=true&size=128&bold=true" alt="Avatar {{ $details['name'] }}" class="w-full h-full rounded-full shadow-lg border-4 border-white">
            </div>
            
            <div class="flex-1 text-center md:text-left relative z-10 w-full">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $details['name'] }}</h1>
                <div class="flex flex-wrap gap-2 mb-6 justify-center md:justify-start">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i> {{ $details['city'] }}
                    </div>
                    @if(!empty($details['unsur']))
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-purple-100 text-purple-800 uppercase tracking-wider">
                        <i class="fas fa-id-badge mr-2 text-purple-600"></i> {{ $details['unsur'] }}
                    </div>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Total Kehadiran</div>
                        <div class="text-3xl font-black text-gray-800">{{ $details['attended_count'] }} <span class="text-sm font-medium text-gray-500">dari {{ $details['total_events'] }} kegiatan</span></div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100 flex flex-col justify-center">
                        <div class="text-xs text-green-700 font-bold uppercase tracking-wider mb-1">Tingkat Konsistensi</div>
                        <div class="flex items-center gap-2">
                            <div class="text-3xl font-black text-green-600">{{ $details['percentage'] }}%</div>
                            <div class="text-[10px] text-green-700/80 font-medium leading-tight flex-1">
                                Hadir di {{ $details['attended_count'] }} kegiatan dari total {{ $details['total_events'] }} kegiatan Zoom yang diselenggarakan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EVENTS HISTORY -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2 text-blue-600"></i> Rekam Jejak Kegiatan ({{ count($details['events']) }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($details['events'] as $event)
                    <div class="p-5 hover:bg-blue-50/50 transition-colors flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-video"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-base mb-1">{{ $event }}</h4>
                            <p class="text-xs text-gray-500 font-medium">Mewakili instansi dari wilayah {{ $details['city'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Peserta ini belum memiliki rekam jejak kegiatan.
                    </div>
                @endforelse
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
