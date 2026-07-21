<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Full Page Loading Overlay --}}
    <div x-data="{ loading: false }"
         @data-loading.window="loading = true"
         @pageshow.window="if ($event.persisted) loading = false"
         x-show="loading"
         x-transition.opacity.duration.300ms
         style="display: none;"
         class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/90 backdrop-blur-md">
        <div class="relative w-24 h-24 mb-6">
            <div class="absolute inset-0 rounded-full border-8 border-transparent border-t-emerald-500 border-b-emerald-500 animate-[spin_1.5s_linear_infinite]"></div>
            <div class="absolute inset-2 rounded-full border-8 border-transparent border-l-teal-400 border-r-teal-400 animate-[spin_1s_linear_infinite_reverse]"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-flag text-2xl text-emerald-600 animate-bounce"></i>
            </div>
        </div>
        <h2 class="text-xl font-black tracking-widest uppercase text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400 animate-pulse">Menarik Data</h2>
        <p class="text-gray-500 font-medium text-sm mt-2">Harap tunggu sebentar...</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeIn_0.5s_ease-out]">

        {{-- ===== HEADER ===== --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-teal-700 to-green-900 p-8 sm:p-12 text-white shadow-[0_20px_50px_rgba(16,185,129,0.4)] transition-transform hover:scale-[1.01] duration-500 group">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay group-hover:scale-105 transition-transform duration-700"></div>
            {{-- Glassmorphism Orbs --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 -right-20 w-72 h-72 bg-amber-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/30 text-amber-300 mb-4 uppercase tracking-widest shadow-inner">
                        <i class="fas fa-flag mr-2"></i> Apel Senin — Kantor
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 drop-shadow-xl text-transparent bg-clip-text bg-gradient-to-r from-white to-emerald-100">
                        Dashboard Apel Senin
                    </h1>
                    <p class="text-emerald-100/90 text-lg md:text-xl font-medium drop-shadow leading-relaxed">
                        Pantau tingkat kehadiran pegawai berdasarkan Tim Kerja secara visual dan komprehensif.
                    </p>
                </div>

                {{-- Stats mini --}}
                <div class="flex gap-4 flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 text-center min-w-[120px]">
                        <div class="text-3xl font-black text-amber-300">{{ count($apelDates) }}</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-emerald-100 mt-1">Total Apel</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 text-center min-w-[120px]">
                        <div class="text-3xl font-black text-white">{{ $totalSum }}</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-emerald-100 mt-1">Total Hadir</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TOMBOL SYNC ===== --}}
        <div class="flex justify-end -mt-4 relative z-40">
            <a href="{{ route('apel-senin', ['_sync' => 1]) }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transition-all flex items-center group">
                <i class="fas fa-sync-alt mr-2 group-hover:rotate-180 transition-transform duration-500"></i> Sinkronisasi Data (Hapus Cache)
            </a>
        </div>
        {{-- ===== FILTER TANGGAL APEL ===== --}}
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl shadow-gray-200/50 p-6 border border-gray-100 relative z-30">
            <form action="{{ route('apel-senin') }}" method="GET" id="dateForm">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">
                    <i class="fas fa-calendar-alt mr-2 text-emerald-500"></i> Pilih Tanggal Apel
                </label>

                <div x-data="{
                    open: false,
                    search: '',
                    selected: '{{ addslashes($selectedDate) }}',
                    dates: {{ json_encode($apelDates) }},
                    get filteredDates() {
                        if (this.search === '') return Object.entries(this.dates);
                        return Object.entries(this.dates).filter(([k, v]) =>
                            v.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },
                    selectDate(key) {
                        this.selected = key;
                        this.open = false;
                        document.getElementById('hiddenDateInput').value = key;
                        this.$dispatch('data-loading');
                        document.getElementById('dateForm').submit();
                    }
                }" class="relative">

                    <input type="hidden" name="date" id="hiddenDateInput" :value="selected">

                    <button type="button" @click="open = !open" @click.away="open = false"
                        class="w-full bg-slate-50 border-2 border-slate-200 text-gray-800 text-sm md:text-base font-semibold rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 p-4 shadow-sm transition-all text-left flex justify-between items-center hover:bg-slate-100">
                        <span x-text="selected === 'all' ? 'Semua Tanggal (Akumulasi)' : (selected && dates[selected] ? dates[selected] : 'Pilih Tanggal Apel...')" class="truncate pr-4"></span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-transition.opacity.duration.200ms
                        class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] overflow-hidden">
                        <div class="p-3 border-b border-gray-100 bg-gray-50">
                            <div class="relative">
                                <input x-model="search" type="text" placeholder="Ketik untuk mencari..."
                                    class="w-full bg-white border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-2 focus:ring-emerald-500 p-2.5 pl-10 shadow-inner" autofocus>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                        <div class="max-h-60 overflow-y-auto overscroll-contain">
                            <button type="button" @click="selectDate('all')"
                                class="w-full text-left px-4 py-3 text-sm font-semibold hover:bg-emerald-50 transition-colors flex items-center justify-between"
                                :class="selected === 'all' ? 'text-emerald-700 bg-emerald-50/50' : 'text-gray-700'">
                                <span><i class="fas fa-layer-group text-emerald-500 w-5"></i> Semua Tanggal (Akumulasi)</span>
                                <i x-show="selected === 'all'" class="fas fa-check text-emerald-500"></i>
                            </button>
                            <template x-for="[key, label] in filteredDates" :key="key">
                                <li @click="selectDate(key)"
                                    class="px-4 py-3 hover:bg-emerald-50 hover:text-emerald-700 cursor-pointer text-sm font-medium transition-colors border-b border-gray-50 last:border-0"
                                    :class="selected === key ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-700'">
                                    <span x-text="label"></span>
                                </li>
                            </template>
                            <li x-show="filteredDates.length === 0" class="px-4 py-4 text-center text-gray-500 text-sm">
                                Tidak ada tanggal yang cocok.
                            </li>
                        </div>
                    </div>
                </div>

                @if(empty($apelDates))
                    <div class="text-red-500 text-sm font-medium mt-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Belum ada data apel senin.
                    </div>
                @endif
            </form>
        </div>

        {{-- ===== CHART + STATS ===== --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 relative z-10">
            {{-- Bar Chart --}}
            <div class="xl:col-span-2 bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 hover:shadow-2xl transition-shadow duration-500">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold text-gray-800">
                        <i class="fas fa-chart-bar text-emerald-500 mr-2"></i>
                        Kehadiran per Tim Kerja
                        @if($selectedDate && $selectedDate !== 'all')
                            <span class="text-sm font-normal text-gray-500 ml-2">({{ $apelDates[$selectedDate] ?? $selectedDate }})</span>
                        @else
                            <span class="text-sm font-normal text-gray-500 ml-2">(Akumulasi Semua Apel)</span>
                        @endif
                    </h3>
                    <div class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ count($teamsStats ?? []) }} Tim Kerja
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="teamChart"></canvas>
                </div>
            </div>

            {{-- Summary Stats --}}
            <div class="bg-gradient-to-b from-emerald-700 to-teal-900 rounded-3xl shadow-xl p-6 flex flex-col items-center justify-center text-center text-white relative overflow-hidden group hover:scale-[1.02] transition-transform duration-500 min-h-[300px]">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')] opacity-10"></div>
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-amber-400/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-teal-400/20 rounded-full blur-3xl"></div>

                <div class="w-full h-full flex flex-col items-center justify-center bg-emerald-500/20 backdrop-blur-md rounded-2xl p-8 border border-emerald-400/30 shadow-inner transform group-hover:scale-105 transition-transform">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-4 shadow-inner border border-white/20">
                        <i class="fas fa-users text-3xl text-amber-400 drop-shadow-md"></i>
                    </div>
                    <div class="text-sm text-emerald-100 font-bold uppercase tracking-widest mb-2 drop-shadow-sm">Total Hadir</div>
                    <div class="text-xs text-emerald-200/80 mb-4">
                        @if($selectedDate && $selectedDate !== 'all')
                            {{ $apelDates[$selectedDate] ?? $selectedDate }}
                        @else
                            Semua Tanggal
                        @endif
                    </div>
                    <div class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-emerald-200 drop-shadow-lg"
                         x-data="{ count: 0 }"
                         x-init="
                            let target = {{ $totalToday }};
                            let duration = 1200;
                            let start = performance.now();
                            let animate = (t) => {
                                let elapsed = t - start;
                                let progress = Math.min(elapsed / duration, 1);
                                let ease = 1 - Math.pow(1 - progress, 4);
                                count = Math.floor(ease * target);
                                if (progress < 1) requestAnimationFrame(animate);
                                else count = target;
                            };
                            requestAnimationFrame(animate);
                         "
                         x-text="count">0</div>

                    {{-- Tim dengan persentase kehadiran tertinggi --}}
                    @php
                        $topTeam = null;
                        $topPercentage = null;
                        $topCount = 0;
                        $topTotal = 0;
                        
                        if (!empty($teamsStats)) {
                            $topTeam = array_key_first($teamsStats);
                            $topData = $teamsStats[$topTeam];
                            $topCount = $topData['count'];
                            $topTotal = $topData['total'];
                            $topPercentage = $topData['percentage'];
                        }
                    @endphp
                    @if($topTeam)
                    <div class="mt-5 w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2.5 text-xs">
                        <div class="text-emerald-200 uppercase tracking-wider mb-1">🏆 Tim Paling Aktif</div>
                        <div class="font-bold text-white text-sm leading-tight">{{ $topTeam }}</div>
                        <div class="text-amber-300 font-extrabold text-base mt-0.5">
                            @if($topPercentage !== null)
                                {{ number_format($topPercentage, 1, ',', '') }}% <span class="text-xs text-white/70 font-normal">({{ $topCount }} dari {{ $topData['denominator'] ?? $topTotal }})</span>
                            @else
                                {{ $topCount }} hadir
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== GRID TIM KERJA ===== --}}
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-8 flex items-center">
                <i class="fas fa-users-cog text-emerald-600 mr-3"></i> Sebaran Kehadiran Per Tim Kerja
            </h3>

            @if($selectedDate === 'all')
                <div class="flex items-start gap-3 text-amber-700 bg-amber-50 rounded-xl p-4 border border-amber-200 mb-8">
                    <i class="fas fa-info-circle mt-0.5 text-amber-500"></i>
                    <div>
                        <p class="text-sm font-bold">Sedang Menampilkan Akumulasi</p>
                        <p class="text-xs mt-1">Data yang ditampilkan di bawah adalah total akumulatif dari semua tanggal apel (satu orang dihitung 1x hadir walaupun ikut berkali-kali). Pilih tanggal spesifik untuk melihat rincian ketidakhadiran harian.</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-6 md:gap-8">
                @forelse($teamsStats ?? [] as $team => $data)
                    @php
                        $count = $data['count'];
                        $percentage = $data['percentage'];
                        $totalMembers = $data['total'];
                        $teamSlug = $timKerjaInfo[$team]['slug'] ?? Str::slug($team);
                        $teamKetua = $timKerjaInfo[$team]['ketua'] ?? 'Ketua Tim';
                        $photoUrl  = \App\Services\ApelSeninService::getPhotoUrl($team);
                    @endphp

                    <a href="{{ route('apel-senin.team', ['team' => urlencode($team), 'date' => $selectedDate]) }}"
                       @click="$dispatch('data-loading')"
                       class="group relative block bg-white/40 backdrop-blur-xl rounded-[2rem] p-1 transition-all duration-500 hover:-translate-y-3 hover:scale-[1.02]">

                        {{-- Animated Gradient Border & Glow --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 via-teal-300 to-green-500 rounded-[2rem] opacity-20 group-hover:opacity-100 group-hover:blur-md transition-all duration-500"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 via-teal-300 to-green-500 rounded-[2rem] opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>

                        {{-- Inner Card --}}
                        <div class="relative h-full bg-gradient-to-br from-white to-slate-50/90 rounded-[1.85rem] p-6 flex flex-col items-center text-center shadow-xl shadow-slate-200/50">

                            {{-- Foto Ketua Tim --}}
                            <div class="relative w-24 h-24 mb-5">
                                <div class="absolute inset-0 bg-emerald-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                                <div class="relative bg-white rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] w-full h-full flex items-center justify-center overflow-hidden border border-slate-100 transform group-hover:-translate-y-1 transition-transform duration-500">
                                    <img src="{{ $photoUrl }}"
                                         alt="Ketua {{ $team }}"
                                         class="w-full h-full object-cover filter drop-shadow-sm group-hover:scale-110 transition-transform duration-500"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(substr($team, 0, 2)) }}&background=10b981&color=fff&bold=true&size=160'">
                                </div>
                                {{-- Badge jumlah --}}
                                <div class="absolute -top-2 -right-3 bg-emerald-500 text-white text-[10px] font-black rounded-full px-2 py-1 flex items-center justify-center shadow-lg border-2 border-white">
                                    {{ number_format($percentage, 0) }}%
                                </div>
                            </div>

                            {{-- Nama Tim --}}
                            <h3 class="text-xs font-black text-slate-800 mb-3 line-clamp-3 min-h-[3.5rem] flex items-center justify-center group-hover:text-emerald-700 transition-colors tracking-tight leading-snug">
                                {{ $team }}
                            </h3>


                            {{-- Count Bar --}}
                            <div class="mt-auto bg-slate-100 text-slate-600 px-3 py-2.5 rounded-xl text-xs font-bold tracking-wide group-hover:bg-gradient-to-r group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:text-white group-hover:shadow-lg transition-all duration-300 w-full flex flex-col items-center justify-center leading-tight">
                                <div><span class="text-lg mr-1">{{ number_format($percentage, 1) }}%</span> <span class="uppercase tracking-widest text-[9px]">Kehadiran</span></div>
                                <div class="text-[9px] font-normal mt-0.5 opacity-80">({{ $count }} dari {{ $data['denominator'] }})</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-400 bg-white/80 backdrop-blur-md rounded-3xl border border-dashed border-gray-300 shadow-sm">
                        <i class="fas fa-calendar-times text-6xl mb-4 opacity-30"></i>
                        <p class="text-lg font-bold">Tidak Ada Data</p>
                        <p class="text-sm mt-1">Pilih tanggal apel yang tersedia di atas</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Chart Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const teamData = @json($teamsStats ?? []);
        const labels   = Object.keys(teamData).map(k => {
            // Singkat nama tim agar muat di label
            const words = k.split(' ');
            if (words.length > 4) return words.slice(0, 3).join(' ') + '...';
            return k;
        });
        const values   = Object.values(teamData).map(d => Number(d.percentage).toFixed(1));

        const colors = [
            '#10b981', '#14b8a6', '#06b6d4', '#3b82f6',
            '#8b5cf6', '#ec4899', '#f59e0b', '#ef4444',
            '#84cc16', '#f97316', '#6366f1', '#0ea5e9',
        ];

        const ctx = document.getElementById('teamChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kehadiran',
                    data: values,
                    backgroundColor: colors.slice(0, values.length),
                    borderColor: colors.slice(0, values.length).map(c => c),
                    borderWidth: 2,
                    borderRadius: 12,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: (items) => Object.keys(teamData)[items[0].dataIndex],
                            label: (item) => `  ${item.raw}% Kehadiran (${Object.values(teamData)[item.dataIndex].count} hadir)`
                        },
                        backgroundColor: '#1e293b',
                        titleFont: { weight: 'bold', size: 13 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, maxRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 11 }, callback: function(value) { return value + '%'; } }
                    }
                }
            }
        });
    });
    </script>
</x-layout>
