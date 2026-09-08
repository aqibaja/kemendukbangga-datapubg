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
@endphp

<!-- DASHBOARD CONTAINER -->
<div id="capaianProgramRoot" class="text-[var(--text)] w-full mx-auto relative pb-4">
    
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
                    <span style="vertical-align: middle; margin-left: 8px; display: inline-block;">Periode: {{ App\Models\LaporanCapaian::namaBulan($laporans['capaian_program']->bulan) }} {{ $laporans['capaian_program']->tahun }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-[var(--navy)] mb-1 uppercase tracking-tight export-fix-text leading-tight">LAPORAN CAPAIAN PROGRAM</h1>
                <h2 class="text-2xl sm:text-3xl font-black text-[var(--teal)] mb-3.5 export-fix-text">BANGGA KENCANA</h2>
                <div class="flex flex-wrap gap-2.5 text-xs sm:text-sm text-[var(--muted)]">
                    <div style="display: inline-block; background: #f1f5f9; padding: 6px 14px; border-radius: 8px; white-space: nowrap;">
                        <span style="display: inline-block; font-weight: 700; color: #475569;">Update data: {{ $laporans['capaian_program']->updated_at->format('d M Y') }}</span>
                    </div>
                    <div style="display: inline-block; background: #f1f5f9; padding: 6px 14px; border-radius: 8px; white-space: nowrap;">
                        <span style="display: inline-block; font-weight: 700; color: #475569;">Sumber: Perwakilan BKKBN Prov Aceh</span>
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-2 flex justify-center items-center">
                <img src="{{ $img('image/bangga_kencana_3d.jpg') }}" style="height: 240px; max-height: 240px;" class="object-contain drop-shadow-md rounded-2xl" alt="Ilustrasi Capaian">
            </div>
        </div>
    </div>

    <!-- SECTION: CAKUPAN FASYANKES -->
    <div class="mb-4 mt-8 flex items-center border-b-2 border-slate-200 pb-2.5 relative z-10">
        <div class="w-2.5 h-6 rounded-full bg-[var(--teal)] mr-3"></div>
        <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">CAKUPAN FASKES</h2>
    </div>
    
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8 relative z-10">
        @php
            $faskes = [
                ['name' => 'PEMERINTAH', 'color' => 'var(--teal)', 'icon' => $img('image/capaian-program/pemerintah.png'), 'data' => $d['cakupan_fasyankes']['pemerintah'] ?? []],
                ['name' => 'JARINGAN', 'color' => 'var(--cyan)', 'icon' => $img('image/capaian-program/jaringan.png'), 'data' => $d['cakupan_fasyankes']['jaringan'] ?? []],
                ['name' => 'SWASTA', 'color' => 'var(--orange)', 'icon' => $img('image/capaian-program/swasta.png'), 'data' => $d['cakupan_fasyankes']['swasta'] ?? []],
                ['name' => 'PMB SETARA', 'color' => 'var(--purple)', 'icon' => $img('image/capaian-program/pmb_setara.png'), 'data' => $d['cakupan_fasyankes']['pmb_setara'] ?? []],
                ['name' => 'PMB JEJARING', 'color' => 'var(--red)', 'icon' => $img('image/capaian-program/pmb_jejaring.png'), 'data' => $d['cakupan_fasyankes']['pmb_jejaring'] ?? []]
            ];
        @endphp
        
        @foreach($faskes as $f)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-all duration-300 flex flex-col justify-between p-4 sm:p-5">
                
                <div class="flex flex-col items-center">
                    <div class="w-28 h-28 flex items-center justify-center mb-2">
                        <img src="{{ $f['icon'] }}" style="max-height: 110px; max-width: 110px; object-fit: contain;" class="export-fix-icon drop-shadow-md hover:scale-105 transition-transform duration-300" alt="{{ $f['name'] }}">
                    </div>

                    <h3 class="font-black text-[var(--navy)] text-sm sm:text-base mb-2 text-center uppercase tracking-wide leading-tight min-h-[36px] flex items-center justify-center">{{ $f['name'] }}</h3>

                    <div class="text-5xl sm:text-6xl font-black export-fix-text leading-none mb-2" style="color: {{ $f['color'] }};">{{ $fmt($f['data']['persentase'] ?? 0) }}<span class="text-3xl">%</span></div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-3 overflow-hidden">
                        <div class="h-full rounded-full" style="background-color: {{ $f['color'] }}; width: {{ min($f['data']['persentase'] ?? 0, 100) }}%"></div>
                    </div>
                </div>

                <div class="w-full grid grid-cols-2 gap-2 bg-slate-50/90 rounded-xl py-2 px-3 border border-slate-100">
                    <div class="text-center">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Lapor</div>
                        <div class="font-black text-[var(--navy)] text-xl sm:text-2xl leading-tight">{{ number_format($f['data']['lapor'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-center border-l border-slate-200">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Ada</div>
                        <div class="font-black text-[var(--navy)] text-xl sm:text-2xl leading-tight">{{ number_format($f['data']['ada'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- SECTION: STOCK OPNAME -->
    <div class="mb-4 mt-8 flex items-center border-b-2 border-slate-200 pb-2.5 relative z-10">
        <div class="w-2.5 h-6 rounded-full bg-[var(--navy)] mr-3"></div>
        <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">STOCK OPNAME SIRIKA</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 relative z-10">
        @php
            $stocks = [
                ['title' => 'PROVINSI', 'bg' => 'from-[var(--navy)] to-blue-900', 'data' => $d['stock_opname']['gudang_provinsi'] ?? []],
                ['title' => 'KAB/KOTA', 'bg' => 'from-[var(--teal)] to-teal-700', 'data' => $d['stock_opname']['gudang_kabkota'] ?? []],
                ['title' => 'FASYANKES', 'bg' => 'from-[var(--cyan)] to-cyan-600', 'data' => $d['stock_opname']['gudang_fasyankes'] ?? []],
            ];
        @endphp
        @foreach($stocks as $s)
            @php $pVal = $fmt($s['data']['persentase'] ?? 0); @endphp
            <div class="rounded-2xl relative p-5 sm:p-6 flex flex-col justify-between text-white overflow-hidden shadow-[0_8px_24px_rgb(0,0,0,0.1)] bg-gradient-to-br {{ $s['bg'] }}">
                <div class="absolute -right-10 -top-10 w-44 h-44 bg-white opacity-10 rounded-full blur-2xl"></div>
                
                <h3 class="font-black text-xl sm:text-2xl mb-4 tracking-wider z-10 uppercase">{{ $s['title'] }}</h3>
                
                <div class="flex items-end justify-between z-10 gap-2">
                    <div class="flex flex-col gap-2 shrink-0">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl px-3 sm:px-3.5 py-1.5">
                            <div class="text-[10px] sm:text-[11px] font-bold text-white/80 uppercase tracking-wider">Ada</div>
                            <div class="text-xl sm:text-2xl font-black leading-tight">{{ number_format($s['data']['ada'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl px-3 sm:px-3.5 py-1.5">
                            <div class="text-[10px] sm:text-[11px] font-bold text-white/80 uppercase tracking-wider">Lapor</div>
                            <div class="text-xl sm:text-2xl font-black leading-tight">{{ number_format($s['data']['laporan'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <div class="text-right pb-1 flex-1 min-w-0 flex items-baseline justify-end">
                        <div class="font-black leading-none drop-shadow-md export-fix-text whitespace-nowrap {{ strlen($pVal) > 4 ? 'text-5xl sm:text-6xl xl:text-[62px]' : 'text-6xl sm:text-7xl xl:text-7xl' }}">
                            {{ $pVal }}<span class="text-2xl sm:text-3xl lg:text-4xl text-white/80 ml-0.5">%</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- SECTION: KB BARU & KB AKTIF -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 relative z-10">
        <!-- KB BARU -->
        <div>
            <div class="h-12 flex items-center justify-between border-b-2 border-slate-200 pb-2 mb-4">
                <div class="flex items-center">
                    <div class="w-2.5 h-6 rounded-full bg-[var(--teal)] mr-3"></div>
                    <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">KB BARU</h2>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-lg bg-teal-50 text-[var(--teal)] border border-teal-200/60">Peserta Baru</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @php
                    $kbBaru = [
                        ['title' => 'PB', 'sub' => 'Peserta Baru', 'data' => $d['kb_baru']['pb'] ?? [], 'color' => 'var(--teal)', 'image' => 'capaian-program/pb.png'],
                        ['title' => 'PB PASCA SALIN', 'sub' => 'Pasca Persalinan', 'data' => $d['kb_baru']['pb_pasca_persalinan'] ?? [], 'color' => 'var(--cyan)', 'image' => 'capaian-program/pb_pasca_persalinan.png'],
                        ['title' => 'PB MKJP', 'sub' => 'Jangka Panjang', 'data' => $d['kb_baru']['pb_mkjp'] ?? [], 'color' => 'var(--purple)', 'image' => 'capaian-program/pb_mkjp.png'],
                        ['title' => 'PB NON MKJP', 'sub' => 'Non Jangka Panjang', 'data' => $d['kb_baru']['pb_non_mkjp'] ?? [], 'color' => 'var(--orange)', 'image' => 'capaian-program/pb_non_mkjp.png'],
                    ];
                @endphp
                @foreach($kbBaru as $kb)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-shadow duration-300 p-4 sm:p-5 flex flex-col justify-between relative overflow-hidden group">
                    <!-- Top: Title & 3D Illustration -->
                    <div class="flex justify-between items-start gap-2 z-10 mb-2">
                        <div class="h-[46px] min-h-[46px] flex flex-col justify-center flex-1 pr-1">
                            <h4 class="font-black text-[var(--navy)] text-sm sm:text-[15px] uppercase tracking-tight leading-tight whitespace-nowrap">{{ $kb['title'] }}</h4>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">{{ $kb['sub'] }}</div>
                        </div>
                        <div class="flex items-center justify-center shrink-0" style="width: 96px; height: 96px;">
                            <img src="{{ $img('image/' . $kb['image']) }}" style="max-height: 96px; max-width: 96px; object-fit: contain;" class="drop-shadow-md transition-transform duration-300 group-hover:scale-110" alt="{{ $kb['title'] }}">
                        </div>
                    </div>
                    
                    <!-- Middle: Percentage & Progress bar -->
                    <div class="mb-3 z-10">
                        <div class="text-5xl sm:text-6xl font-black leading-none mb-2 export-fix-text" style="color: {{ $kb['color'] }}">{{ $fmt($kb['data']['persentase'] ?? 0) }}<span class="text-2xl sm:text-3xl">%</span></div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full" style="background-color: {{ $kb['color'] }}; width: {{ min($kb['data']['persentase'] ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Bottom: PPM & Capaian -->
                    <div class="grid grid-cols-2 gap-2 bg-slate-50/90 rounded-xl py-2 px-3 border border-slate-100 z-10 text-[var(--navy)] h-[62px] min-h-[62px] items-center">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">PPM</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($kb['data']['ppm'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="border-l border-slate-200 pl-2.5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Capaian</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($kb['data']['capaian'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full opacity-[0.06] pointer-events-none" style="background-color: {{ $kb['color'] }}"></div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- KB AKTIF -->
        <div>
            <div class="h-12 flex items-center justify-between border-b-2 border-slate-200 pb-2 mb-4">
                <div class="flex items-center">
                    <div class="w-2.5 h-6 rounded-full bg-[var(--navy)] mr-3"></div>
                    <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">KB AKTIF</h2>
                </div>
                <div class="flex items-center gap-2 bg-[var(--navy)] text-white px-3.5 py-1 rounded-xl shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-200">Total PA</span>
                    <span class="text-base sm:text-lg font-black text-white export-fix-text">{{ number_format($d['kb_aktif']['pa_keseluruhan'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <!-- 1. PA MKJP -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-shadow duration-300 p-4 sm:p-5 flex flex-col justify-between relative overflow-hidden group">
                    <div class="flex justify-between items-start gap-2 z-10 mb-2">
                        <div class="h-[46px] min-h-[46px] flex flex-col justify-center flex-1 pr-1">
                            <h4 class="font-black text-[var(--navy)] text-sm sm:text-[15px] uppercase tracking-tight leading-tight whitespace-nowrap">PA MKJP</h4>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">Jangka Panjang</div>
                        </div>
                        <div class="flex items-center justify-center shrink-0" style="width: 96px; height: 96px;">
                            <img src="{{ $img('image/capaian-program/pa_mkjp.png') }}" style="max-height: 96px; max-width: 96px; object-fit: contain;" class="drop-shadow-md transition-transform duration-300 group-hover:scale-110" alt="PA MKJP">
                        </div>
                    </div>
                    <div class="mb-3 z-10">
                        <div class="text-5xl sm:text-6xl font-black text-[var(--teal)] leading-none mb-2 export-fix-text">{{ $fmt($d['kb_aktif']['pa_mkjp']['persentase'] ?? 0) }}<span class="text-2xl sm:text-3xl">%</span></div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full bg-[var(--teal)]" style="width: {{ min($d['kb_aktif']['pa_mkjp']['persentase'] ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 bg-slate-50/90 rounded-xl py-2 px-3 border border-slate-100 z-10 text-[var(--navy)] h-[62px] min-h-[62px] items-center">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">PPM</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($d['kb_aktif']['pa_mkjp']['ppm'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="border-l border-slate-200 pl-2.5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Capaian</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($d['kb_aktif']['pa_mkjp']['capaian'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full opacity-[0.06] pointer-events-none bg-[var(--teal)]"></div>
                </div>

                <!-- 2. PA NON MKJP -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-shadow duration-300 p-4 sm:p-5 flex flex-col justify-between relative overflow-hidden group">
                    <div class="flex justify-between items-start gap-2 z-10 mb-2">
                        <div class="h-[46px] min-h-[46px] flex flex-col justify-center flex-1 pr-1">
                            <h4 class="font-black text-[var(--navy)] text-sm sm:text-[15px] uppercase tracking-tight leading-tight whitespace-nowrap">PA NON MKJP</h4>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">Non Jangka Panjang</div>
                        </div>
                        <div class="flex items-center justify-center shrink-0" style="width: 96px; height: 96px;">
                            <img src="{{ $img('image/capaian-program/pa_non_mkjp.png') }}" style="max-height: 96px; max-width: 96px; object-fit: contain;" class="drop-shadow-md transition-transform duration-300 group-hover:scale-110" alt="PA Non MKJP">
                        </div>
                    </div>
                    <div class="mb-3 z-10">
                        <div class="text-5xl sm:text-6xl font-black text-[var(--orange)] leading-none mb-2 export-fix-text">{{ $fmt($d['kb_aktif']['pa_non_mkjp']['persentase'] ?? 0) }}<span class="text-2xl sm:text-3xl">%</span></div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full bg-[var(--orange)]" style="width: {{ min($d['kb_aktif']['pa_non_mkjp']['persentase'] ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 bg-slate-50/90 rounded-xl py-2 px-3 border border-slate-100 z-10 text-[var(--navy)] h-[62px] min-h-[62px] items-center">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">PPM</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($d['kb_aktif']['pa_non_mkjp']['ppm'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="border-l border-slate-200 pl-2.5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Capaian</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($d['kb_aktif']['pa_non_mkjp']['capaian'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full opacity-[0.06] pointer-events-none bg-[var(--orange)]"></div>
                </div>

                <!-- 3. PA MODERN (New 3D Illustration) -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-shadow duration-300 p-4 sm:p-5 flex flex-col justify-between relative overflow-hidden group">
                    <div class="flex justify-between items-start gap-2 z-10 mb-2">
                        <div class="h-[46px] min-h-[46px] flex flex-col justify-center flex-1 pr-1">
                            <h4 class="font-black text-[var(--navy)] text-sm sm:text-[15px] uppercase tracking-tight leading-tight whitespace-nowrap">PA MODERN</h4>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">Metode Modern</div>
                        </div>
                        <div class="flex items-center justify-center shrink-0" style="width: 96px; height: 96px;">
                            <img src="{{ $img('image/capaian-program/pa_modern.png') }}" style="max-height: 96px; max-width: 96px; object-fit: contain;" class="drop-shadow-md transition-transform duration-300 group-hover:scale-110" alt="PA Modern">
                        </div>
                    </div>
                    <div class="mb-3 z-10">
                        <div class="text-5xl sm:text-6xl font-black text-[var(--teal)] leading-none mb-2 export-fix-text">{{ $fmt($d['kb_aktif']['pa_modern']['persentase'] ?? 0) }}<span class="text-2xl sm:text-3xl">%</span></div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full bg-[var(--teal)]" style="width: {{ min($d['kb_aktif']['pa_modern']['persentase'] ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 bg-slate-50/90 rounded-xl py-2 px-3 border border-slate-100 z-10 text-[var(--navy)] h-[62px] min-h-[62px] items-center">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">PPM</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($d['kb_aktif']['pa_modern']['ppm'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="border-l border-slate-200 pl-2.5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Capaian</div>
                            <div class="text-lg sm:text-xl font-black leading-tight stat-pill-text">{{ number_format($d['kb_aktif']['pa_modern']['capaian'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full opacity-[0.06] pointer-events-none bg-[var(--teal)]"></div>
                </div>

                <!-- 4. PA TRADISIONAL (New 3D Illustration) -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_16px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_24px_rgb(0,0,0,0.1)] transition-shadow duration-300 p-4 sm:p-5 flex flex-col justify-between relative overflow-hidden group">
                    <div class="flex justify-between items-start gap-2 z-10 mb-2">
                        <div class="h-[46px] min-h-[46px] flex flex-col justify-center flex-1 pr-1">
                            <h4 class="font-black text-[var(--navy)] text-sm sm:text-[15px] uppercase tracking-tight leading-tight whitespace-nowrap">PA TRADISIONAL</h4>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">Metode Alamiah</div>
                        </div>
                        <div class="flex items-center justify-center shrink-0" style="width: 96px; height: 96px;">
                            <img src="{{ $img('image/capaian-program/pa_tradisional.png') }}" style="max-height: 96px; max-width: 96px; object-fit: contain;" class="drop-shadow-md transition-transform duration-300 group-hover:scale-110" alt="PA Tradisional">
                        </div>
                    </div>
                    <div class="mb-3 z-10">
                        <div class="text-5xl sm:text-6xl font-black text-[var(--orange)] leading-none mb-2 export-fix-text">{{ number_format($d['kb_aktif']['pa_tradisional'] ?? 0, 0, ',', '.') }}<span class="text-xl sm:text-2xl text-slate-400 font-bold ml-1.5">Akseptor</span></div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full bg-[var(--orange)]" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 bg-slate-50/90 rounded-xl py-2 px-3 border border-slate-100 z-10 text-[var(--navy)] h-[62px] min-h-[62px] items-center overflow-visible">
                        <div class="overflow-visible">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Tipe</div>
                            <div class="text-xs sm:text-sm font-black text-slate-700 leading-tight whitespace-nowrap stat-pill-text">Tradisional</div>
                        </div>
                        <div class="border-l border-slate-200 pl-2.5 overflow-visible">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Kategori</div>
                            <div class="text-xs sm:text-sm font-black text-[var(--orange)] leading-tight whitespace-nowrap stat-pill-text">Non-Modern</div>
                        </div>
                    </div>
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full opacity-[0.06] pointer-events-none bg-[var(--orange)]"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: mCPR & UNMET NEED -->
    <div class="mb-4 mt-8 flex items-center border-b-2 border-slate-200 pb-2.5 relative z-10">
        <div class="w-2.5 h-6 rounded-full bg-[var(--teal)] mr-3"></div>
        <h2 class="text-xl md:text-2xl font-black text-[var(--navy)] uppercase tracking-tight export-fix-text">mCPR &amp; UNMET NEED</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
        <!-- mCPR -->
        <div class="bg-gradient-to-br from-[var(--teal)] to-teal-800 rounded-2xl p-6 sm:p-7 flex flex-col relative overflow-hidden shadow-[0_8px_24px_rgb(0,0,0,0.12)]">
            <div class="absolute -left-8 -bottom-8 w-44 h-44 bg-white opacity-[0.08] rounded-full blur-2xl"></div>
            <div class="relative z-10 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-white text-3xl sm:text-4xl tracking-wider uppercase">mCPR</h3>
                    <span class="text-xs font-black uppercase tracking-wider px-3 py-1 rounded-lg bg-white/20 text-white border border-white/25">Modern CPR</span>
                </div>
                
                <div class="grid grid-cols-2 gap-3.5 mb-4">
                    <div style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);" class="rounded-xl p-3.5">
                        <div class="text-xs font-bold text-white/90 uppercase tracking-wider mb-1">PUS</div>
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ number_format($d['mcpr_unmet']['mcpr']['pus'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);" class="rounded-xl p-3.5">
                        <div class="text-xs font-bold text-white/90 uppercase tracking-wider mb-1">PA Modern</div>
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ number_format($d['mcpr_unmet']['mcpr']['pa_modern'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Persentase Capaian</div>
                    <div class="text-6xl sm:text-7xl font-black text-[var(--teal)] leading-none export-fix-text">{{ $fmt($d['mcpr_unmet']['mcpr']['persentase'] ?? 0) }}<span class="text-3xl sm:text-4xl">%</span></div>
                </div>
            </div>
        </div>
        
        <!-- Unmet Need -->
        <div class="bg-gradient-to-br from-[var(--red)] to-red-800 rounded-2xl p-6 sm:p-7 flex flex-col relative overflow-hidden shadow-[0_8px_24px_rgb(0,0,0,0.12)]">
            <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white opacity-[0.08] rounded-full blur-2xl"></div>
            <div class="relative z-10 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-white text-3xl sm:text-4xl tracking-wider uppercase">UNMET NEED</h3>
                    <span class="text-xs font-black uppercase tracking-wider px-3 py-1 rounded-lg bg-white/20 text-white border border-white/25">Target Rendah</span>
                </div>
                
                <div class="grid grid-cols-2 gap-3.5 mb-4">
                    <div style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);" class="rounded-xl p-3.5">
                        <div class="text-xs font-bold text-white/90 uppercase tracking-wider mb-1">PUS</div>
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ number_format($d['mcpr_unmet']['unmet_need']['pus'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);" class="rounded-xl p-3.5">
                        <div class="text-xs font-bold text-white/90 uppercase tracking-wider mb-1">Unmet Need</div>
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ number_format($d['mcpr_unmet']['unmet_need']['un'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Persentase Capaian</div>
                    <div class="text-6xl sm:text-7xl font-black text-[var(--red)] leading-none export-fix-text">{{ $fmt($d['mcpr_unmet']['unmet_need']['persentase'] ?? 0) }}<span class="text-3xl sm:text-4xl">%</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. FOOTER RESMI -->
    <div class="bg-[var(--navy)] text-white rounded-2xl mt-8 shadow-sm px-6 py-5 sm:px-8 sm:py-6 flex items-center justify-between z-10 relative">
        <!-- KIRI: Logo -->
        <div class="flex-1 flex justify-start shrink-0">
            <img src="{{ $img('image/logo-putih.png') }}" class="h-14 lg:h-18 object-contain drop-shadow-sm" alt="BKKBN Logo Putih">
        </div>

        <!-- TENGAH: Judul -->
        <div class="flex-[1.5] flex flex-col items-center text-center px-4 border-x-2 border-white/20">
            <div class="font-black text-base lg:text-lg tracking-wide uppercase">LAPORAN CAPAIAN PROGRAM</div>
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
