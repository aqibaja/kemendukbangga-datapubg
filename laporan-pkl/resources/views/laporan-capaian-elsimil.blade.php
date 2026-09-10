@php
    // Helper formatter
    $fmt = function($v) { 
        $v = floatval($v); 
        return (floor($v) == $v) ? number_format($v, 0, ',', '.') : number_format($v, 2, ',', '.'); 
    };

    // Helper asset path: di server production shared hosting aset berada di bawah /public/...
    $img = function($path) {
        $cleanPath = ltrim($path, '/');
        $host = request()->getHttpHost();
        if (!str_contains($host, '127.0.0.1') && !str_contains($host, 'localhost')) {
            return asset('public/' . $cleanPath);
        }
        return asset($cleanPath);
    };

    $catinData = $d['catin'] ?? [];
    $bumilData = $d['bumil'] ?? [];

    $bulanAktif = $laporans['elsimil']->bulan ?? 1;
    $tahunAktif = $laporans['elsimil']->tahun ?? date('Y');

    // Dapatkan data bulan aktif
    $catinCurrent = $catinData[(string)$bulanAktif] ?? $catinData[$bulanAktif] ?? (count($catinData) > 0 ? end($catinData) : 0);
    $bumilCurrent = $bumilData[(string)$bulanAktif] ?? $bumilData[$bulanAktif] ?? (count($bumilData) > 0 ? end($bumilData) : 0);
    
    // Bulan sebelumnya
    $bulanLalu = $bulanAktif - 1;
    $catinPrev = $bulanLalu >= 1 ? ($catinData[(string)$bulanLalu] ?? $catinData[$bulanLalu] ?? null) : null;
    $bumilPrev = $bulanLalu >= 1 ? ($bumilData[(string)$bulanLalu] ?? $bumilData[$bulanLalu] ?? null) : null;

    $catinGrowth = ($catinPrev !== null && $catinPrev > 0) ? (($catinCurrent - $catinPrev) / $catinPrev) * 100 : null;
    $bumilGrowth = ($bumilPrev !== null && $bumilPrev > 0) ? (($bumilCurrent - $bumilPrev) / $bumilPrev) * 100 : null;

    $totalTerdampingi = $catinCurrent + $bumilCurrent;
    $totalPrev = ($catinPrev !== null && $bumilPrev !== null) ? ($catinPrev + $bumilPrev) : null;
    $totalGrowth = ($totalPrev !== null && $totalPrev > 0) ? (($totalTerdampingi - $totalPrev) / $totalPrev) * 100 : null;

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp

<!-- DASHBOARD ELSIMIL CONTAINER -->
<div id="capaianElsimilRoot" class="text-[var(--text)] w-full mx-auto relative pb-4">
    
    <!-- WATERMARK BACKGROUND -->
    <div class="absolute inset-0 pointer-events-none z-[0] flex items-center justify-center overflow-hidden">
        <img src="{{ $img('image/logoBKKBN.png') }}" class="w-[60%] max-w-[800px] object-contain opacity-[0.04]" alt="Watermark BKKBN">
    </div>

    <!-- 1. HERO SECTION -->
    <div class="bg-white rounded-[24px] shadow-sm border border-slate-200/80 overflow-hidden mb-7 relative z-10">
        <div class="relative z-10 px-6 py-7 md:px-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3.5 mb-3.5">
                    <img src="{{ $img('image/logoBKKBN.png') }}" style="height: 58px;" alt="Logo BKKBN">
                    <div class="leading-tight">
                        <div class="text-xs sm:text-sm font-bold text-slate-500">Kementerian Kependudukan dan Pembangunan Keluarga/BKKBN</div>
                        <div class="text-base sm:text-lg font-black text-[var(--teal)]">Perwakilan BKKBN Provinsi Aceh</div>
                    </div>
                </div>
                <div style="display: inline-block; padding: 6px 18px; background: #e6f4f4; color: var(--teal); font-size: 1.05rem; font-weight: 800; border-radius: 10px; border: 1.5px solid rgba(0,128,128,0.25); margin-bottom: 12px; white-space: nowrap;">
                    <i class="fa-solid fa-calendar-alt export-shift-icon" style="vertical-align: middle; margin-top: -2px;"></i>
                    <span style="vertical-align: middle; margin-left: 8px; display: inline-block;">Periode: {{ App\Models\LaporanCapaian::namaBulan($bulanAktif) }} {{ $tahunAktif }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-[var(--navy)] mb-1 uppercase tracking-tight export-fix-text leading-tight">LAPORAN CAPAIAN ELSIMIL</h1>
                <h2 class="text-2xl sm:text-3xl font-black text-[var(--teal)] mb-3.5 export-fix-text">APLIKASI ELEKTRONIK SIAP NIKAH &amp; HAMIL</h2>
                <div class="flex flex-wrap gap-2.5 text-xs sm:text-sm text-[var(--muted)]">
                    <div style="display: inline-block; background: #f1f5f9; padding: 6px 14px; border-radius: 8px; white-space: nowrap;">
                        <span style="display: inline-block; font-weight: 700; color: #475569;">Update data: {{ $laporans['elsimil']->updated_at->format('d M Y') }}</span>
                    </div>
                    <div style="display: inline-block; background: #f1f5f9; padding: 6px 14px; border-radius: 8px; white-space: nowrap;">
                        <span style="display: inline-block; font-weight: 700; color: #475569;">Sumber: Aplikasi ELSIMIL - BKKBN Prov Aceh</span>
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-2 flex justify-center items-center">
                <img src="{{ $img('image/elsimil_hero_3d.jpg') }}" style="height: 240px; max-height: 240px;" class="object-contain drop-shadow-md rounded-2xl" alt="Ilustrasi Capaian Elsimil">
            </div>
        </div>
    </div>

    <!-- 2. RINGKASAN CAPAIAN UTAMA (KPI CARDS) -->
    <div class="mb-4 mt-8 flex items-center border-b-2 border-slate-200 pb-2.5 relative z-10">
        <div class="w-2.5 h-6 rounded-full bg-[var(--teal)] mr-3"></div>
        <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">RINGKASAN PENDAMPINGAN HINGGA {{ strtoupper(App\Models\LaporanCapaian::namaBulan($bulanAktif)) }}</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 relative z-10">
        <!-- CARD 1: CATIN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-all duration-300 flex flex-col justify-between p-5 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#0284c7]"></div>
            <div>
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center p-1 shrink-0 shadow-sm">
                            <img src="{{ $img('image/catin_icon_3d.png') }}" class="w-full h-full object-contain drop-shadow-sm" alt="Catin Icon">
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Calon Pengantin</div>
                            <h3 class="font-black text-[var(--navy)] text-base sm:text-lg leading-tight uppercase">CATIN TERDAMPINGI</h3>
                        </div>
                    </div>
                </div>

                <div class="my-2">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Capaian Periode Ini</div>
                    <div class="text-4xl sm:text-5xl font-black text-[#0284c7] leading-none export-fix-text">
                        {{ number_format($catinCurrent, 0, ',', '.') }}
                        <span class="text-xl sm:text-2xl text-slate-500 font-bold">Jiwa</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                @if($catinGrowth !== null)
                    <div class="flex items-center gap-1.5 font-bold {{ $catinGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        <i class="fa-solid {{ $catinGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        <span>{{ $catinGrowth >= 0 ? '+' : '' }}{{ $fmt($catinGrowth) }}% MoM</span>
                    </div>
                    <span class="text-slate-400 font-medium">dibanding bulan lalu</span>
                @else
                    <span class="text-slate-400 font-medium">Bulan awal pelaporan</span>
                @endif
            </div>
        </div>

        <!-- CARD 2: BUMIL -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-all duration-300 flex flex-col justify-between p-5 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#e11d48]"></div>
            <div>
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center p-1 shrink-0 shadow-sm">
                            <img src="{{ $img('image/bumil_icon_3d.png') }}" class="w-full h-full object-contain drop-shadow-sm" alt="Bumil Icon">
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Ibu Hamil</div>
                            <h3 class="font-black text-[var(--navy)] text-base sm:text-lg leading-tight uppercase">BUMIL TERDAMPINGI</h3>
                        </div>
                    </div>
                </div>

                <div class="my-2">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Capaian Periode Ini</div>
                    <div class="text-4xl sm:text-5xl font-black text-[#e11d48] leading-none export-fix-text">
                        {{ number_format($bumilCurrent, 0, ',', '.') }}
                        <span class="text-xl sm:text-2xl text-slate-500 font-bold">Jiwa</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                @if($bumilGrowth !== null)
                    <div class="flex items-center gap-1.5 font-bold {{ $bumilGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        <i class="fa-solid {{ $bumilGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        <span>{{ $bumilGrowth >= 0 ? '+' : '' }}{{ $fmt($bumilGrowth) }}% MoM</span>
                    </div>
                    <span class="text-slate-400 font-medium">dibanding bulan lalu</span>
                @else
                    <span class="text-slate-400 font-medium">Bulan awal pelaporan</span>
                @endif
            </div>
        </div>

        <!-- CARD 3: TOTAL AKUMULASI (EXECUTIVE GRADIENT) -->
        <div class="rounded-2xl relative p-5 sm:p-6 flex flex-col justify-between text-white overflow-hidden shadow-[0_8px_24px_rgb(0,0,0,0.12)] bg-gradient-to-br from-[var(--navy)] via-[#083b6f] to-teal-800">
            <div class="absolute -right-10 -top-10 w-44 h-44 bg-white opacity-10 rounded-full blur-2xl"></div>
            
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-yellow-300 text-lg">
                            <i class="fa-solid fa-users-viewfinder"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-teal-200 uppercase tracking-wider">Akumulasi Sasaran</div>
                            <h3 class="font-black text-xl leading-none uppercase tracking-wide">TOTAL TERDAMPINGI</h3>
                        </div>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg bg-white/20 text-white border border-white/25">TPK Aceh</span>
                </div>

                <div class="my-2">
                    <div class="text-[11px] font-semibold text-blue-200 uppercase tracking-wider mb-1">Catin + Ibu Hamil</div>
                    <div class="text-4xl sm:text-5xl font-black text-white leading-none export-fix-text drop-shadow-md">
                        {{ number_format($totalTerdampingi, 0, ',', '.') }}
                        <span class="text-xl sm:text-2xl text-blue-200 font-bold">Sasaran</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-blue-100">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-white">Catin:</span> {{ number_format($catinCurrent, 0, ',', '.') }}
                    <span class="opacity-60">|</span>
                    <span class="font-bold text-white">Bumil:</span> {{ number_format($bumilCurrent, 0, ',', '.') }}
                </div>
                @if($totalGrowth !== null)
                    <div class="font-bold text-yellow-300">
                        {{ $totalGrowth >= 0 ? '+' : '' }}{{ $fmt($totalGrowth) }}%
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. GRAFIK TREN PENDAMPINGAN (2 CHARTS) -->
    <div class="mb-4 mt-8 flex items-center border-b-2 border-slate-200 pb-2.5 relative z-10">
        <div class="w-2.5 h-6 rounded-full bg-[var(--navy)] mr-3"></div>
        <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">TREN PENDAMPINGAN BULANAN TAHUN {{ $tahunAktif }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 relative z-10">
        <!-- CHART CARD 1: CATIN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.06)] p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center p-1.5 shadow-sm shrink-0">
                        <img src="{{ $img('image/catin_icon_3d.png') }}" class="w-full h-full object-contain" alt="Catin Icon">
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-sky-100 text-[#0284c7] font-extrabold text-[10px] uppercase tracking-wider mb-1">
                            <i class="fa-solid fa-chart-line"></i> Tren Bulanan
                        </div>
                        <h3 class="font-black text-[var(--navy)] text-lg sm:text-xl uppercase tracking-tight leading-tight">JUMLAH CATIN TERDAMPINGI</h3>
                        <p class="text-xs text-slate-500 font-medium">Monitoring Pendampingan Calon Pengantin</p>
                    </div>
                </div>
                <div class="sm:text-right shrink-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Capaian {{ App\Models\LaporanCapaian::namaBulan($bulanAktif) }}</div>
                    <div class="text-2xl sm:text-3xl font-black text-[#0284c7] leading-tight">{{ number_format($catinCurrent, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="w-full relative mt-4" style="height: 380px; min-height: 380px;">
                <canvas id="catinChart"></canvas>
            </div>
            
            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#0284c7] inline-block"></span>
                    <strong class="text-slate-700">Garis Biru:</strong> Realisasi Jiwa Catin Terdampingi
                </span>
                <span class="text-slate-400 font-semibold">Persentase di atas titik menunjukkan pertumbuhan MoM</span>
            </div>
        </div>

        <!-- CHART CARD 2: BUMIL -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.06)] p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center p-1.5 shadow-sm shrink-0">
                        <img src="{{ $img('image/bumil_icon_3d.png') }}" class="w-full h-full object-contain" alt="Bumil Icon">
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-rose-100 text-[#e11d48] font-extrabold text-[10px] uppercase tracking-wider mb-1">
                            <i class="fa-solid fa-chart-line"></i> Tren Bulanan
                        </div>
                        <h3 class="font-black text-[var(--navy)] text-lg sm:text-xl uppercase tracking-tight leading-tight">JUMLAH BUMIL TERDAMPINGI</h3>
                        <p class="text-xs text-slate-500 font-medium">Monitoring Pendampingan Ibu Hamil</p>
                    </div>
                </div>
                <div class="sm:text-right shrink-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Capaian {{ App\Models\LaporanCapaian::namaBulan($bulanAktif) }}</div>
                    <div class="text-2xl sm:text-3xl font-black text-[#e11d48] leading-tight">{{ number_format($bumilCurrent, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="w-full relative mt-4" style="height: 380px; min-height: 380px;">
                <canvas id="bumilChart"></canvas>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#e11d48] inline-block"></span>
                    <strong class="text-slate-700">Garis Merah:</strong> Realisasi Jiwa Bumil Terdampingi
                </span>
                <span class="text-slate-400 font-semibold">Persentase di atas titik menunjukkan pertumbuhan MoM</span>
            </div>
        </div>
    </div>

    <!-- 4. TABEL REKAPITULASI CAPAIAN PER BULAN -->
    <div class="mb-4 mt-8 flex items-center border-b-2 border-slate-200 pb-2.5 relative z-10">
        <div class="w-2.5 h-6 rounded-full bg-[var(--teal)] mr-3"></div>
        <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">REKAPITULASI DATA BULANAN ELSIMIL TAHUN {{ $tahunAktif }}</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden mb-8 relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-xs">
                        <th class="py-3.5 px-4 text-center w-16">No</th>
                        <th class="py-3.5 px-4">Bulan</th>
                        <th class="py-3.5 px-4 text-right">Catin Terdampingi</th>
                        <th class="py-3.5 px-4 text-center">Tren Catin</th>
                        <th class="py-3.5 px-4 text-right">Bumil Terdampingi</th>
                        <th class="py-3.5 px-4 text-center">Tren Bumil</th>
                        <th class="py-3.5 px-4 text-right">Total Terdampingi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        // Urutkan bulan yang ada data
                        $allMonths = array_unique(array_merge(array_keys($catinData), array_keys($bumilData)));
                        sort($allMonths, SORT_NUMERIC);
                        $no = 1;
                        $prevC = null;
                        $prevB = null;
                    @endphp

                    @forelse($allMonths as $m)
                        @php
                            $cVal = $catinData[(string)$m] ?? $catinData[$m] ?? 0;
                            $bVal = $bumilData[(string)$m] ?? $bumilData[$m] ?? 0;
                            $tot = $cVal + $bVal;

                            $cGrowth = ($prevC !== null && $prevC > 0) ? (($cVal - $prevC) / $prevC) * 100 : null;
                            $bGrowth = ($prevB !== null && $prevB > 0) ? (($bVal - $prevB) / $prevB) * 100 : null;

                            $isCurrentMonth = ($m == $bulanAktif);
                        @endphp
                        <tr class="{{ $isCurrentMonth ? 'bg-teal-50/60 font-semibold' : 'hover:bg-slate-50/80' }} transition-colors">
                            <td class="py-3.5 px-4 text-center text-slate-400 font-bold">{{ $no++ }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800">{{ $monthNames[$m] ?? "Bulan $m" }}</span>
                                    @if($isCurrentMonth)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-teal-100 text-teal-800 border border-teal-200">Periode Ini</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-[#0284c7]">
                                {{ number_format($cVal, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($cGrowth !== null)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold {{ $cGrowth >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $cGrowth >= 0 ? '+' : '' }}{{ $fmt($cGrowth) }}%
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-[#e11d48]">
                                {{ number_format($bVal, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($bGrowth !== null)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold {{ $bGrowth >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $bGrowth >= 0 ? '+' : '' }}{{ $fmt($bGrowth) }}%
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-[var(--navy)] text-base">
                                {{ number_format($tot, 0, ',', '.') }}
                            </td>
                        </tr>
                        @php
                            $prevC = $cVal;
                            $prevB = $bVal;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada data bulanan yang diinput untuk tahun {{ $tahunAktif }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. FOOTER RESMI (IDENTIK DENGAN CAPAIAN PROGRAM) -->
    <div class="bg-[var(--navy)] text-white rounded-2xl mt-8 shadow-sm px-6 py-5 sm:px-8 sm:py-6 flex items-center justify-between z-10 relative">
        <!-- KIRI: Logo -->
        <div class="flex-1 flex justify-start shrink-0">
            <img src="{{ $img('image/logo-putih.png') }}" class="h-14 lg:h-18 object-contain drop-shadow-sm" alt="BKKBN Logo Putih">
        </div>

        <!-- TENGAH: Judul -->
        <div class="flex-[1.5] flex flex-col items-center text-center px-4 border-x-2 border-white/20">
            <div class="font-black text-base lg:text-lg tracking-wide uppercase">LAPORAN CAPAIAN ELSIMIL</div>
            <div class="text-xs text-blue-200 opacity-90 mt-1">Dicetak pada {{ date('d/m/Y H:i') }}</div>
        </div>

        <!-- KANAN: Info Kontak -->
        <div class="flex-1 flex flex-col items-end gap-1 text-xs shrink-0">
            <div style="white-space: nowrap;">
                <i class="fa-solid fa-headset text-blue-400 export-shift-icon text-sm" style="vertical-align: middle; margin-right: 6px;"></i>
                <span style="vertical-align: middle;">Pengaduan <span class="text-yellow-400 font-bold">085361209387</span></span>
            </div>
            <div style="white-space: nowrap;">
                <i class="fa-solid fa-globe text-blue-400 export-shift-icon text-sm" style="vertical-align: middle; margin-right: 6px;"></i>
                <span class="text-blue-200" style="vertical-align: middle;">aceh.kemendukbangga.go.id</span>
            </div>
            <div style="white-space: nowrap;">
                <i class="fa-brands fa-instagram text-blue-400 export-shift-icon text-sm" style="vertical-align: middle; margin-right: 6px;"></i>
                <span class="text-blue-200" style="vertical-align: middle;">kemendukbangga_bkkbnaceh</span>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT INITIALIZATION UNTUK CHART ELSIMIL (LIGHT EXECUTIVE THEME) -->
<script>
    (function() {
        const catinRaw = @json($catinData);
        const bumilRaw = @json($bumilData);

        function initCharts() {
            if (typeof Chart === 'undefined') {
                setTimeout(initCharts, 100);
                return;
            }

            // Custom Plugin untuk Label Nilai & Persentase yang Bersih & Elegan
            const lightPointLabelsPlugin = {
                id: 'lightPointLabels',
                afterDatasetsDraw(chart) {
                    const { ctx } = chart;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((point, index) => {
                            const value = dataset.data[index];
                            const percentage = dataset.percentages ? dataset.percentages[index] : '';

                            // 1. Teks Angka Nilai (Dark Navy Bold)
                            ctx.font = 'bold 12px "Plus Jakarta Sans", sans-serif';
                            ctx.fillStyle = '#0f172a';
                            ctx.fillText(Number(value).toLocaleString('id-ID'), point.x, point.y - 10);

                            // 2. Teks Pertumbuhan MoM di Atasnya
                            if (percentage) {
                                const isPos = !percentage.includes('-');
                                ctx.font = 'bold 10px "Plus Jakarta Sans", sans-serif';
                                ctx.fillStyle = isPos ? '#16a34a' : '#dc2626';
                                const arrow = isPos ? '▲ ' : '▼ ';
                                ctx.fillText(arrow + percentage.replace('+', '').replace('-', ''), point.x, point.y - 24);
                            }
                        });
                    });
                }
            };

            // Register plugin if not registered
            if (!Chart.registry.plugins.get('lightPointLabels')) {
                Chart.register(lightPointLabelsPlugin);
            }

            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            const renderLineChart = (canvasId, dataObj, lineColor, startColor, endColor) => {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;
                const ctx = canvas.getContext('2d');

                // Urutkan data berdasarkan bulan
                const sortedKeys = Object.keys(dataObj).sort((a, b) => parseInt(a) - parseInt(b));
                const labels = sortedKeys.map(m => monthNames[parseInt(m) - 1]);
                const dataPoints = sortedKeys.map(m => dataObj[m]);

                const percentages = [];
                for (let i = 0; i < dataPoints.length; i++) {
                    if (i === 0) {
                        percentages.push('');
                    } else {
                        const prev = dataPoints[i - 1];
                        const curr = dataPoints[i];
                        if (prev === 0) {
                            percentages.push('');
                        } else {
                            const diff = ((curr - prev) / prev) * 100;
                            let formatted = diff.toFixed(1) + '%';
                            if (diff > 0) formatted = '+' + formatted;
                            percentages.push(formatted);
                        }
                    }
                }

                const maxVal = Math.max(...dataPoints.map(Number), 10);

                let gradient = ctx.createLinearGradient(0, 0, 0, 360);
                gradient.addColorStop(0, startColor);
                gradient.addColorStop(1, endColor);

                // Destroy previous instance if re-rendering
                const existingChart = Chart.getChart(canvas);
                if (existingChart) {
                    existingChart.destroy();
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataPoints,
                            percentages: percentages,
                            borderColor: lineColor,
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 5,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: lineColor,
                            pointBorderWidth: 3,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: { top: 40, right: 30, left: 30, bottom: 10 }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                                bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13, weight: 'bold' },
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.parsed.y.toLocaleString('id-ID') + ' Jiwa';
                                    }
                                }
                            },
                            lightPointLabels: true
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#64748b',
                                    font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' }
                                }
                            },
                            y: {
                                display: false,
                                min: 0,
                                max: maxVal + (maxVal * 0.45) // Beri 45% ruang di atas agar label tidak terpotong
                            }
                        }
                    }
                });
            };

            if (Object.keys(catinRaw).length > 0) {
                renderLineChart('catinChart', catinRaw, '#0284c7', 'rgba(2, 132, 199, 0.22)', 'rgba(2, 132, 199, 0.01)');
            }
            if (Object.keys(bumilRaw).length > 0) {
                renderLineChart('bumilChart', bumilRaw, '#e11d48', 'rgba(225, 29, 72, 0.22)', 'rgba(225, 29, 72, 0.01)');
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }
    })();
</script>
