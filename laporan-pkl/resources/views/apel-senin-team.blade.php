<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-5xl mx-auto space-y-6 animate-[fadeIn_0.5s_ease-out]">

        {{-- ===== BREADCRUMB & BACK ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-sm text-gray-500">
            <a href="{{ route('apel-senin', ['date' => $selectedDate]) }}"
               class="hover:text-emerald-600 transition-colors font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard Apel Senin
            </a>
            <div class="flex items-center gap-2 text-gray-400">
                <i class="fas fa-home text-xs"></i>
                <span>/</span>
                <span class="text-gray-500 font-medium">Apel Senin</span>
                <span>/</span>
                <span class="text-emerald-600 font-bold truncate max-w-[200px]">{{ $team }}</span>
            </div>
        </div>

        {{-- ===== HERO HEADER TIM ===== --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-teal-700 to-green-900 text-white shadow-[0_20px_50px_rgba(16,185,129,0.35)]">
            {{-- Background decoration --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-amber-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-teal-400/20 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 p-8 md:p-12">
                {{-- Foto Ketua --}}
                <div class="relative flex-shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-3xl overflow-hidden border-4 border-white/30 shadow-2xl">
                        <img src="{{ $photoUrl }}"
                             alt="Ketua {{ $team }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(substr($team, 0, 2)) }}&background=10b981&color=fff&bold=true&size=160'">
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-amber-400 text-white text-xs font-black rounded-full px-3 py-1 shadow-lg border-2 border-white">
                        Ketua
                    </div>
                </div>

                {{-- Info Tim --}}
                <div class="text-center md:text-left flex-1">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/30 text-amber-300 mb-3 uppercase tracking-widest">
                        <i class="fas fa-users mr-2"></i> Tim Kerja
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 drop-shadow-xl leading-tight">
                        {{ $team }}
                    </h1>
                    @if($teamInfo)
                    <p class="text-emerald-100 font-medium text-base mb-4">{{ $teamInfo['ketua'] }}</p>
                    @endif

                    {{-- Stats Row --}}
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <div class="bg-white/15 backdrop-blur border border-white/20 rounded-xl px-4 py-2 text-center">
                            <div class="text-2xl font-black text-amber-300">{{ count($rankings) }}</div>
                            <div class="text-xs text-emerald-100 font-medium uppercase tracking-wider">Anggota</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur border border-white/20 rounded-xl px-4 py-2 text-center">
                            <div class="text-2xl font-black text-white">{{ $totalApel }}</div>
                            <div class="text-xs text-emerald-100 font-medium uppercase tracking-wider">Total Apel</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur border border-white/20 rounded-xl px-4 py-2 text-center">
                            <div class="text-2xl font-black text-white">{{ $totalAttended }}</div>
                            <div class="text-xs text-emerald-100 font-medium uppercase tracking-wider">Total Hadir</div>
                        </div>
                        @if($selectedDate && isset($apelDates[$selectedDate]))
                        <div class="bg-amber-400/30 backdrop-blur border border-amber-400/40 rounded-xl px-4 py-2 text-center">
                            <div class="text-xs text-amber-200 font-bold uppercase tracking-wider">Filter Aktif</div>
                            <div class="text-xs text-white font-semibold mt-0.5">{{ $apelDates[$selectedDate] }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== FILTER TANGGAL (di halaman detail) ===== --}}
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-100 p-5 relative z-20">
            <form action="{{ route('apel-senin.team', ['team' => urlencode($team)]) }}" method="GET" id="teamDateForm">
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                    <i class="fas fa-filter mr-2 text-emerald-500"></i> Filter Per Tanggal Apel
                </label>
                <div class="flex flex-wrap gap-2 items-center">
                    <a href="{{ route('apel-senin.team', ['team' => urlencode($team)]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ !$selectedDate ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                        Semua Tanggal
                    </a>
                    @foreach($apelDates as $dateKey => $dateLabel)
                        <a href="{{ route('apel-senin.team', ['team' => urlencode($team), 'date' => $dateKey]) }}"
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $selectedDate === $dateKey ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                            {{ \Carbon\Carbon::parse($dateKey)->isoFormat('D MMM') }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- ===== CHART TREND ===== --}}
        @if(!$selectedDate && count($trend) > 0)
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 p-8">
            <h3 class="text-xl font-extrabold text-gray-800 mb-6">
                <i class="fas fa-chart-line text-emerald-500 mr-2"></i>
                Trend Kehadiran per Tanggal Apel
            </h3>
            <div class="relative h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
        @endif

        {{-- ===== RANKING KEHADIRAN ===== --}}
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-extrabold text-gray-800">
                    <i class="fas fa-trophy text-amber-500 mr-2"></i>
                    Peringkat Kehadiran
                    @if(!$selectedDate)
                        <span class="text-sm font-normal text-gray-500 ml-2">— Akumulasi Semua Apel</span>
                    @else
                        <span class="text-sm font-normal text-gray-500 ml-2">— {{ $apelDates[$selectedDate] ?? $selectedDate }}</span>
                    @endif
                </h3>
                <div class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">
                    {{ count($rankings) }} Peserta Tercatat
                </div>
            </div>

            @if(!$selectedDate)
            {{-- Mode akumulasi: ranking dengan progress bar --}}
            <div class="space-y-3">
                @forelse($rankings as $index => $person)
                    @php
                        $rank      = $index + 1;
                        $pct       = $person['percentage'];
                        $colors    = ['from-amber-500 to-yellow-400', 'from-slate-400 to-gray-300', 'from-orange-600 to-red-500'];
                        $rankColor = $rank <= 3 ? $colors[$rank - 1] : 'from-emerald-500 to-teal-400';
                        $icon      = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : "#{$rank}"));
                    @endphp
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-emerald-50 transition-all duration-200 group cursor-default border border-transparent hover:border-emerald-100">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center text-lg font-black">{{ $icon }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-800 truncate group-hover:text-emerald-700 transition-colors">{{ $person['nama'] }}</div>
                            <div class="flex items-center gap-2 mt-1.5">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r {{ $rankColor }} rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-500 flex-shrink-0 w-10 text-right">{{ $pct }}%</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-2xl font-black text-emerald-600">{{ $person['attended_count'] }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-wider">dari {{ $person['total_apel'] }} apel</div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400">
                        <i class="fas fa-user-slash text-5xl mb-3 opacity-30"></i>
                        <p class="font-bold">Belum ada data kehadiran</p>
                    </div>
                @endforelse
            </div>

            @else
            {{-- Mode filter tanggal: tampilkan seluruh anggota tim + status kehadiran hari itu --}}
            @php
                // Daftar nama yang hadir pada tanggal ini (untuk lookup cepat)
                $hadirSet = collect($attendees)->pluck('nama')->map(fn($n) => strtoupper(trim($n)))->flip()->toArray();

                // Gabungkan: anggota dari CSV + yang hadir tapi tidak di CSV (baru/tamu)
                $csvSet = collect($csvMembers)->map(fn($n) => strtoupper(trim($n)))->flip()->toArray();
                $tambahanHadir = collect($attendees)
                    ->filter(fn($a) => !isset($csvSet[strtoupper(trim($a['nama']))]))
                    ->values();

                $totalHadir    = count($attendees);
                $totalAnggota  = count($csvMembers);
            @endphp

            {{-- Summary badge --}}
            <div class="flex flex-wrap gap-3 mb-6">
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span class="font-bold text-emerald-700 text-sm">{{ $totalHadir }} Hadir</span>
                </div>
                @if($totalAnggota > 0)
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-2">
                    <i class="fas fa-times-circle text-red-400"></i>
                    <span class="font-bold text-red-600 text-sm">{{ $totalAnggota - $totalHadir > 0 ? $totalAnggota - $totalHadir : 0 }} Tidak Hadir</span>
                </div>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2">
                    <i class="fas fa-users text-slate-500"></i>
                    <span class="font-bold text-slate-600 text-sm">{{ $totalAnggota }} Total Anggota</span>
                </div>
                @endif
            </div>

            @if($totalAnggota > 0)
            {{-- Tabel daftar anggota resmi + status --}}
            <div class="overflow-hidden rounded-2xl border border-gray-100 mb-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-emerald-50 text-emerald-700">
                            <th class="px-4 py-3 text-left font-bold text-xs uppercase tracking-wider w-10">No</th>
                            <th class="px-4 py-3 text-left font-bold text-xs uppercase tracking-wider">Nama Anggota</th>
                            <th class="px-4 py-3 text-center font-bold text-xs uppercase tracking-wider w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($csvMembers as $i => $member)
                            @php $hadir = isset($hadirSet[strtoupper(trim($member))]); @endphp
                            <tr class="hover:bg-slate-50 transition-colors {{ $hadir ? '' : 'opacity-60' }}">
                                <td class="px-4 py-3 text-gray-400 font-medium">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-semibold {{ $hadir ? 'text-gray-800' : 'text-gray-500' }}">
                                    {{ $member }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($hadir)
                                        <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-bold">
                                            <i class="fas fa-check text-[10px]"></i> Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-500 px-2.5 py-1 rounded-lg text-xs font-bold">
                                            <i class="fas fa-times text-[10px]"></i> Tidak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Hadir tapi tidak ada di daftar anggota CSV (pegawai baru / tamu) --}}
            @if(count($tambahanHadir) > 0)
            <div class="mt-4">
                <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">
                    @if($totalAnggota > 0)
                        <i class="fas fa-user-plus mr-1"></i> Hadir (di luar daftar anggota)
                    @else
                        <i class="fas fa-users mr-1"></i> Daftar Kehadiran
                    @endif
                </div>
                <div class="space-y-1.5">
                    @foreach($tambahanHadir as $a)
                    <div class="flex items-center gap-3 px-4 py-2.5 bg-amber-50 rounded-xl border border-amber-100">
                        <i class="fas fa-user text-amber-400 text-xs"></i>
                        <span class="text-sm font-semibold text-gray-700">{{ $a['nama'] }}</span>
                        @if($totalAnggota > 0)
                            <span class="ml-auto text-[10px] text-amber-600 font-bold uppercase tracking-wider">Tamu / Baru</span>
                        @else
                            <span class="ml-auto text-[10px] text-emerald-600 font-bold uppercase tracking-wider"><i class="fas fa-check"></i> Hadir</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($attendees) === 0 && $totalAnggota === 0)
            <div class="py-12 text-center text-gray-400">
                <i class="fas fa-calendar-times text-5xl mb-3 opacity-30"></i>
                <p class="font-bold">Tidak ada kehadiran pada tanggal ini</p>
            </div>
            @endif
            @endif
        </div>

    </div>


    {{-- ===== CHART SCRIPT ===== --}}
    @if(!$selectedDate && count($trend) > 0)
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendData   = @json($trend);
        const dateLabels  = Object.keys(trendData).map(d => {
            const dt = new Date(d);
            return dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const countValues = Object.values(trendData);

        const ctx = document.getElementById('trendChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: 'Hadir',
                    data: countValues,
                    fill: true,
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    borderColor: '#10b981',
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (item) => `  ${item.formattedValue} orang hadir`
                        },
                        backgroundColor: '#1e293b',
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 10,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: 'bold' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 11 }, stepSize: 1 }
                    }
                }
            }
        });
    });
    </script>
    @endif
</x-layout>
