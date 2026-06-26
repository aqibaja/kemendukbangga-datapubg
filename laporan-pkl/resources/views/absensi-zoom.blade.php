<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <!-- Full Page Loading Overlay -->
    <div x-data="{ loading: false }" 
         @data-loading.window="loading = true"
         x-show="loading" 
         x-transition.opacity.duration.300ms
         style="display: none;"
         class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/90 backdrop-blur-md">
        
        <!-- Custom Blue & Gold Spinner -->
        <div class="relative w-24 h-24 mb-6">
            <!-- Outer Ring (Blue) -->
            <div class="absolute inset-0 rounded-full border-8 border-transparent border-t-[#66b2e8] border-b-[#66b2e8] animate-[spin_1.5s_linear_infinite]"></div>
            <!-- Inner Ring (Gold) -->
            <div class="absolute inset-2 rounded-full border-8 border-transparent border-l-[#d4af37] border-r-[#d4af37] animate-[spin_1s_linear_infinite_reverse]"></div>
            <!-- Center Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="flex items-center justify-center">
                    <i class="fas fa-cloud-download-alt text-2xl text-[#66b2e8] animate-bounce"></i>
                </div>
            </div>
        </div>
        
        <h2 class="text-xl font-black tracking-widest uppercase text-transparent bg-clip-text bg-gradient-to-r from-[#66b2e8] to-[#d4af37] animate-pulse">
            Menarik Data
        </h2>
        <p class="text-gray-500 font-medium text-sm mt-2">Harap tunggu sebentar...</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeIn_0.5s_ease-out]">
        
        <!-- HEADER SECTION -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-600 via-blue-700 to-blue-900 p-8 sm:p-12 text-white shadow-[0_20px_50px_rgba(14,165,233,0.4)] transition-transform hover:scale-[1.01] duration-500 group">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay group-hover:scale-105 transition-transform duration-700"></div>
            <!-- Glassmorphism Orbs -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-sky-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 -right-20 w-72 h-72 bg-amber-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/30 text-amber-300 mb-4 uppercase tracking-widest shadow-inner">
                        <i class="fas fa-chart-line mr-2"></i> Analitik Real-time
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 drop-shadow-xl text-transparent bg-clip-text bg-gradient-to-r from-white to-sky-100">
                        Dashboard Presensi Zoom
                    </h1>
                    <p class="text-sky-100/90 text-lg md:text-xl font-medium drop-shadow leading-relaxed">
                        Pantau tingkat partisipasi peserta dari berbagai Kabupaten/Kota dengan data visual yang komprehensif.
                    </p>
                </div>
            </div>
        </div>

        <!-- TABS NAVIGATION -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100/50 p-2 flex gap-2 overflow-x-auto w-full relative z-20">
            <a href="{{ route('absensi-zoom', ['tab' => 'kegiatan', 'event' => request('event')]) }}" @click="$dispatch('data-loading')" class="flex-1 flex items-center justify-center px-8 py-3.5 rounded-xl text-sm font-extrabold transition-all duration-300 whitespace-nowrap {{ $tab === 'kegiatan' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/30' : 'bg-transparent text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-chart-pie mr-2 {{ $tab === 'kegiatan' ? 'text-amber-300' : '' }}"></i> Ringkasan Kegiatan
            </a>
            <a href="{{ route('absensi-zoom', ['tab' => 'peserta']) }}" @click="$dispatch('data-loading')" class="flex-1 flex items-center justify-center px-8 py-3.5 rounded-xl text-sm font-extrabold transition-all duration-300 whitespace-nowrap {{ $tab === 'peserta' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/30' : 'bg-transparent text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-trophy mr-2 {{ $tab === 'peserta' ? 'text-amber-300' : '' }}"></i> Peringkat Peserta
            </a>
        </div>

        @if($tab === 'kegiatan')
            <!-- FILTER SECTION (Custom Alpine Dropdown) -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl shadow-gray-200/50 p-6 border border-gray-100 relative z-30">
                <form action="{{ route('absensi-zoom') }}" method="GET" id="eventForm">
                    <input type="hidden" name="tab" value="kegiatan">
                    <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider"><i class="fas fa-filter mr-2 text-blue-500"></i> Pilih Kegiatan Zoom</label>
                    
                    <div x-data="{ 
                        open: false, 
                        search: '', 
                        selected: '{{ addslashes($selectedEvent) }}',
                        get filteredEvents() {
                            const events = {{ json_encode($events) }};
                            if (this.search === '') return events;
                            return events.filter(e => e.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        selectEvent(evt) {
                            this.selected = evt;
                            this.open = false;
                            document.getElementById('hiddenEventInput').value = evt;
                            this.$dispatch('data-loading');
                            document.getElementById('eventForm').submit();
                        }
                    }" class="relative">
                        
                        <input type="hidden" name="event" id="hiddenEventInput" :value="selected">
                        
                        <button type="button" @click="open = !open" @click.away="open = false" class="w-full bg-slate-50 border-2 border-slate-200 text-gray-800 text-sm md:text-base font-semibold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 p-4 shadow-sm transition-all text-left flex justify-between items-center hover:bg-slate-100">
                            <span x-text="selected || 'Pilih Kegiatan...'" class="truncate pr-4"></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] overflow-hidden">
                            <div class="p-3 border-b border-gray-100 bg-gray-50">
                                <div class="relative">
                                    <input x-model="search" type="text" placeholder="Ketik untuk mencari..." class="w-full bg-white border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 p-2.5 pl-10 shadow-inner" autofocus>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                            </div>
                            <ul class="max-h-60 overflow-y-auto py-2">
                                <template x-for="evt in filteredEvents" :key="evt">
                                    <li @click="selectEvent(evt)" class="px-4 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-sm font-medium transition-colors border-b border-gray-50 last:border-0" :class="selected === evt ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                        <span x-text="evt"></span>
                                    </li>
                                </template>
                                <li x-show="filteredEvents.length === 0" class="px-4 py-4 text-center text-gray-500 text-sm">
                                    Tidak ada kegiatan yang cocok.
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if(empty($events))
                        <div class="text-red-500 text-sm font-medium mt-2"><i class="fas fa-exclamation-triangle mr-1"></i> Belum ada data kegiatan.</div>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 relative z-10">
                <!-- CHART -->
                <div class="xl:col-span-2 bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 hover:shadow-2xl transition-shadow duration-500">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-extrabold text-gray-800"><i class="fas fa-chart-bar text-blue-500 mr-2"></i> Trend Kehadiran Top 10 Kota</h3>
                        <div class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">{{ count($citiesCount) }} Kota Tercatat</div>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="cityChart"></canvas>
                    </div>
                </div>
                
                <!-- SUMMARY STATS -->
                <div class="bg-gradient-to-b from-blue-700 to-indigo-900 rounded-3xl shadow-xl p-6 flex flex-col items-center justify-center text-center text-white relative overflow-hidden group hover:scale-[1.02] transition-transform duration-500 min-h-[300px]">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')] opacity-10"></div>
                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-amber-400/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-sky-400/20 rounded-full blur-3xl"></div>
                    
                    <div class="w-full h-full flex flex-col items-center justify-center bg-sky-500/20 backdrop-blur-md rounded-2xl p-8 border border-sky-400/30 shadow-inner transform group-hover:scale-105 transition-transform">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-4 shadow-inner border border-white/20">
                            <i class="fas fa-users text-3xl text-amber-400 drop-shadow-md"></i>
                        </div>
                        <div class="text-sm text-sky-100 font-bold uppercase tracking-widest mb-2 drop-shadow-sm">Total Kehadiran</div>
                        <div class="text-xs text-sky-200/80 mb-4">{{ $selectedEvent }}</div>
                        <div class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-sky-200 drop-shadow-lg" x-data="{ count: 0 }" x-init="
                            let target = {{ array_sum($citiesCount) }};
                            let duration = 1500;
                            let start = performance.now();
                            let animate = (currentTime) => {
                                let elapsed = currentTime - start;
                                let progress = Math.min(elapsed / duration, 1);
                                // easeOutQuart
                                let easeProgress = 1 - Math.pow(1 - progress, 4);
                                count = Math.floor(easeProgress * target);
                                if (progress < 1) requestAnimationFrame(animate);
                                else count = target;
                            };
                            requestAnimationFrame(animate);
                        " x-text="count">0</div>
                    </div>
                </div>
            </div>

            <!-- GRID SECTION -->
            <div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-8 flex items-center">
                    <i class="fas fa-map-marked-alt text-blue-600 mr-3"></i> Sebaran Peserta Per Daerah
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                    @forelse($citiesCount as $city => $count)
                        <!-- Wrap card in anchor tag linking to city detail -->
                        <a href="{{ route('absensi-zoom.city', ['city' => $city, 'event' => $selectedEvent]) }}" @click="$dispatch('data-loading')" class="group relative block bg-white/40 backdrop-blur-xl rounded-[2rem] p-1 transition-all duration-500 hover:-translate-y-3 hover:scale-[1.02]">
                            <!-- Animated Gradient Border & Glow -->
                            <div class="absolute inset-0 bg-gradient-to-br from-sky-400 via-amber-300 to-blue-500 rounded-[2rem] opacity-20 group-hover:opacity-100 group-hover:blur-md transition-all duration-500"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-sky-400 via-amber-300 to-blue-500 rounded-[2rem] opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Inner Card Content -->
                            <div class="relative h-full bg-gradient-to-br from-white to-slate-50/90 rounded-[1.85rem] p-6 flex flex-col items-center text-center shadow-xl shadow-slate-200/50">
                                
                                @php
                                    $logoFileName = $city . '.png';
                                    if (!file_exists(public_path('image/logo-kab-kota/' . $logoFileName))) {
                                        $logoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($city) . '&background=0D8ABC&color=fff&rounded=true&bold=true&size=80';
                                    } else {
                                        $logoUrl = asset('image/logo-kab-kota/' . rawurlencode($logoFileName));
                                    }
                                @endphp
                                
                                <!-- Floating Logo Container -->
                                <div class="relative w-24 h-24 mb-6">
                                    <div class="absolute inset-0 bg-blue-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                                    <div class="relative bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] w-full h-full flex items-center justify-center p-3 border border-slate-100 transform group-hover:-translate-y-1 transition-transform duration-500">
                                        <img src="{{ $logoUrl }}" alt="Logo {{ $city }}" class="w-full h-full object-contain filter drop-shadow-sm group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                </div>
                                
                                <h3 class="text-base font-black text-slate-800 mb-4 line-clamp-2 h-12 flex items-center justify-center group-hover:text-blue-700 transition-colors tracking-tight">{{ $city }}</h3>
                                
                                <div class="mt-auto bg-slate-100 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-bold tracking-wide group-hover:bg-gradient-to-r group-hover:from-sky-500 group-hover:to-blue-600 group-hover:text-white group-hover:shadow-lg transition-all duration-300 w-full">
                                    <span class="text-xl mr-1">{{ $count }}</span> <span class="uppercase tracking-widest text-[10px]">Hadir</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-400 bg-white/80 backdrop-blur-md rounded-3xl border border-dashed border-gray-300 shadow-sm">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                <i class="fas fa-inbox text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Peserta</h3>
                            <p class="text-base font-medium text-gray-500">Tidak ada data kehadiran yang tercatat untuk kegiatan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('cityChart').getContext('2d');
                    
                    const labels = {!! json_encode(array_keys($citiesCount)) !!};
                    const data = {!! json_encode(array_values($citiesCount)) !!};
                    
                    const topLabels = labels.slice(0, 10);
                    const topData = data.slice(0, 10);
                    
                    // Create gradient for bars
                    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.9)'); // sky-500
                    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.8)');  // blue-600

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: topLabels,
                            datasets: [{
                                label: 'Jumlah Kehadiran',
                                data: topData,
                                backgroundColor: gradient,
                                borderRadius: 8,
                                borderSkipped: false,
                                hoverBackgroundColor: 'rgba(37, 99, 235, 1)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleFont: { size: 14, family: "'Inter', sans-serif" },
                                    bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: false
                                }
                            },
                            scales: {
                                y: { 
                                    beginAtZero: true, 
                                    grid: { color: 'rgba(0, 0, 0, 0.05)', borderDash: [5, 5] },
                                    ticks: { font: { family: "'Inter', sans-serif" } }
                                },
                                x: { 
                                    grid: { display: false },
                                    ticks: { font: { family: "'Inter', sans-serif", weight: '600' }, color: '#475569' }
                                }
                            }
                        }
                    });
                });
            </script>
        @else
            <!-- TAB PESERTA -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 md:p-8 bg-gradient-to-r from-sky-50 to-amber-50/30 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-6">
                    <div>
                        <h3 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-medal text-amber-500 mr-2"></i> Peringkat & Konsistensi</h3>
                        <p class="text-sm text-gray-500 font-medium mt-1">Siapa yang paling rajin hadir di setiap kegiatan?</p>
                    </div>
                    
                    <form action="{{ route('absensi-zoom') }}" method="GET" class="w-full sm:w-80" @submit="$dispatch('data-loading')">
                        <input type="hidden" name="tab" value="peserta">
                        <div class="relative group">
                            <input type="text" name="search_person" value="{{ request('search_person') }}" placeholder="Cari nama peserta..." class="w-full bg-white border-2 border-gray-200 text-gray-800 text-sm font-medium rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block p-3.5 pl-12 transition-all shadow-sm group-hover:border-blue-300">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fas fa-search text-lg"></i>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white text-gray-800 uppercase font-extrabold text-xs border-b-2 border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Peringkat</th>
                                <th class="px-6 py-5">Nama Peserta</th>
                                <th class="px-6 py-5">Asal Kabupaten/Kota</th>
                                <th class="px-6 py-5 text-center">Total Event</th>
                                <th class="px-6 py-5 text-center">Tingkat Konsistensi</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($rankings as $index => $person)
                                <tr class="hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-6 py-5 font-black text-gray-400">
                                        @if($index === 0 && empty(request('search_person')))
                                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-lg shadow-sm">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                        @elseif($index === 1 && empty(request('search_person')))
                                            <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-lg shadow-sm">
                                                <i class="fas fa-medal"></i>
                                            </div>
                                        @elseif($index === 2 && empty(request('search_person')))
                                            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center text-lg shadow-sm">
                                                <i class="fas fa-award"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center">
                                                #{{ $index + 1 }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-extrabold text-gray-900 text-base group-hover:text-blue-700 transition-colors">{{ $person['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            <i class="fas fa-map-marker-alt mr-1.5"></i> {{ $person['city'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-700 font-bold border border-blue-100">
                                            {{ $person['attended_count'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="w-full bg-gray-100 rounded-full h-3 max-w-[120px] overflow-hidden shadow-inner">
                                                <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-full rounded-full relative" style="width: {{ min(100, $person['percentage']) }}%">
                                                    <div class="absolute inset-0 bg-white/20 w-full animate-[shimmer_2s_infinite]"></div>
                                                </div>
                                            </div>
                                            <span class="text-sm font-black text-gray-700 w-10 text-right">{{ $person['percentage'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('absensi-zoom.person', ['name' => $person['name']]) }}" @click="$dispatch('data-loading')" class="text-blue-600 hover:text-white font-bold text-sm inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-600 px-4 py-2 rounded-xl transition-all shadow-sm hover:shadow-md">
                                            Detail <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                            <i class="fas fa-search text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-600 mb-1">Pencarian Tidak Ditemukan</h3>
                                        <p class="text-gray-400 text-sm">Coba gunakan kata kunci nama yang berbeda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(empty($searchPerson) && count($rankings) == 200)
                    <div class="p-6 text-center text-sm font-semibold text-gray-500 border-t border-gray-100 bg-gray-50">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i> Menampilkan top 200 peserta. Gunakan fitur pencarian untuk menemukan peserta spesifik lainnya.
                    </div>
                @endif
            </div>
        @endif
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
</x-layout>
