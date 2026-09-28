<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
        :root {
            --navy: #063B76;
            --navy-dark: #022A59;
            --teal: #008B76;
            --green: #15803D;
            --orange: #F57C00;
            --red: #D7193F;
            --purple: #5B2BBE;
            --cyan: #078DCB;
            --surface: #FFFFFF;
            --canvas: #F1F7FF;
            --line: #CFDDEC;
            --text: #152238;
            --muted: #627086;
        }

        .bg-main { 
            background-color: var(--canvas);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Used only by capaian_program, elsimil, quick_win sections */
        .bg-main-dark {
            background: linear-gradient(135deg, #1fa2a8 0%, #0d5f5a 50%, #d48e15 100%);
            font-family: 'Poppins', sans-serif;
        }
        .gold-card { 
            background: linear-gradient(to bottom, #ffeca1, #d4a017); 
            border: 3px solid #ffdf00;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.2), 0 10px 15px rgba(0,0,0,0.5);
            border-radius: 12px 12px 24px 24px;
        }
        .dark-green-card { 
            background: linear-gradient(to bottom, #0a3a35, #041f1c);
            border: 2px solid #ffd700;
            box-shadow: 0 8px 20px rgba(0,0,0,0.6);
        }
        /* Infographic card: white bg, thick colored left border */
        .info-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.10);
            overflow: hidden;
        }
        /* Section full-width bar header */
        .section-bar {
            display: flex;
            align-items: center;
            gap: 16px;
            border-radius: 14px;
            padding: 12px 20px;
            margin-bottom: 20px;
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #fff;
        }
        .section-bar img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.15);
            flex-shrink: 0;
        }
        /* Big stat label and number */
        .stat-label { font-size: 1rem; font-weight: 600; color: #64748b; }
        .stat-value { font-size: 1.875rem; font-weight: 900; line-height: 1; }
        .stat-pct   { font-size: 2.5rem;  font-weight: 900; line-height: 1; }
        /* Card header strip */
        .card-hdr {
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #fff;
            padding: 10px 16px;
            text-align: center;
        }
        .card-body { padding: 18px 20px; }
        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .pct-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #f1f5f9;
        }

        body.exporting .export-fix-text {
            margin-top: -3px !important;
            padding-bottom: 3px !important;
            display: inline-block;
        }
        body.exporting .export-fix-icon {
            margin-top: -4px !important;
        }
        body.exporting .shift-up-export {
            margin-top: -3px !important;
        }
        body.exporting .pill-text {
            display: inline-block;
            margin-top: -2px !important;
        }
        body.exporting * {
            text-rendering: auto !important;
        }
        /* Fix html2canvas font baseline clipping */
        body.exporting p, 
        body.exporting h1, 
        body.exporting h2, 
        body.exporting h3, 
        body.exporting h4, 
        body.exporting span,
        body.exporting .export-fix-text,
        body.exporting .leading-none {
            transform: translateY(-3px) !important;
        }
        
        body.exporting .global-header-export {
            display: none !important;
        }
        
        body.exporting .export-shift-icon {
            transform: translateY(-3px) !important;
        }

        
        body.exporting .pill-text-fix,
        body.exporting .pill-icon-fix,
        body.exporting .stat-pill-text {
            transform: none !important;
            vertical-align: middle !important;
            line-height: 1.25 !important;
        }

        /* Capaian Program & Elsimil Export Enhancements */
        body.exporting #capaianProgramRoot,
        body.exporting #capaianElsimilRoot {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
        }
    </style>

    <!-- Zoom Controls -->
    <div id="zoomControls" class="fixed bottom-8 left-8 z-50 flex flex-col gap-2 print:hidden">
        <button onclick="zoomIn()" class="w-10 h-10 rounded-xl bg-white shadow-xl border-2 border-slate-300 flex items-center justify-center hover:bg-slate-50 transition-all duration-200 text-slate-700 font-bold text-lg"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
        <button onclick="zoomReset()" class="w-10 h-10 rounded-xl bg-white shadow-xl border-2 border-slate-300 flex items-center justify-center hover:bg-slate-50 transition-all duration-200 text-slate-700 font-bold text-[10px]" id="zoomLevel">100%</button>
        <button onclick="zoomOut()" class="w-10 h-10 rounded-xl bg-white shadow-xl border-2 border-slate-300 flex items-center justify-center hover:bg-slate-50 transition-all duration-200 text-slate-700 font-bold text-lg"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
    </div>

    <div id="shareBtns" class="fixed bottom-8 right-8 z-50 flex gap-4 print:hidden">
        <button onclick="d('png')" class="px-3 py-2 rounded-xl bg-white shadow-xl border-2 border-sky-500 flex items-center gap-2 hover:bg-sky-50 transition-all duration-200 text-sky-700 font-bold text-xs sm:text-sm"><i class="fa-solid fa-image text-base"></i> PNG</button>
        <button onclick="d('pdf')" class="px-3 py-2 rounded-xl bg-white shadow-xl border-2 border-red-500 flex items-center gap-2 hover:bg-red-50 transition-all duration-200 text-red-700 font-bold text-xs sm:text-sm"><i class="fa-solid fa-file-pdf text-base"></i> PDF</button>
    </div>

    @php
        $logoPath = public_path('image/logoBKKBN.png');
        if (!file_exists($logoPath)) {
            $logoPath = base_path('../public/image/logoBKKBN.png');
        }
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        } else {
            $logoSrc = asset('public/image/logoBKKBN.png');
        }
    @endphp

    <div class="bg-main flex flex-col relative z-0 mt-4 sm:mx-4 rounded-3xl overflow-hidden shadow-2xl" id="posterContent">
        <div class="flex-grow flex flex-col items-center p-2 sm:p-4">
            <div class="max-w-7xl w-full relative z-10" style="color:#1e293b;">
                
                <!-- ===== HEADER ===== -->
                <div class="flex flex-col items-center mb-6 mt-4 relative global-header-export">
                    {{-- Logo + nama instansi --}}
                    <div class="flex flex-col sm:flex-row items-center gap-4 mb-3 text-center sm:text-left">
                        <img src="{{ $logoSrc }}" alt="Logo BKKBN" class="w-20 h-20 sm:w-24 sm:h-24 object-contain drop-shadow-md">
                        <div class="leading-snug">
                            <div class="text-sm sm:text-base font-semibold text-slate-500">Kementerian Kependudukan dan Pembangunan Keluarga/BKKBN</div>
                            <div class="text-base sm:text-lg font-extrabold text-teal-700">Perwakilan BKKBN Provinsi Aceh</div>
                        </div>
                    </div>
                    {{-- Judul Laporan --}}
                    <div class="text-xl sm:text-3xl md:text-4xl font-black text-slate-800 text-center leading-tight px-2 drop-shadow-sm">
                        @if(request('tipe', 'pengendalian_lapangan') == 'pengendalian_lapangan')
                            LAPORAN CAPAIAN PROGRAM PENGENDALIAN LAPANGAN<br>
                        @elseif(request('tipe') == 'capaian_program')
                            LAPORAN CAPAIAN PROGRAM<br>
                        @elseif(request('tipe') == 'elsimil')
                            LAPORAN CAPAIAN ELSIMIL<br>
                        @elseif(request('tipe') == 'quick_win')
                            LAPORAN QUICK WIN<br>
                        @endif
                        <span class="text-slate-700">KEMENDUKBANGGA (BKKBN) PROV ACEH</span><br>
                        <span class="text-teal-600 text-2xl sm:text-4xl uppercase">{{ \App\Models\LaporanCapaian::namaBulan($bulan) }} TAHUN {{ $tahun }}</span>
                    </div>

                    <!-- Form Filter -->
                    <form method="GET" action="/laporan-capaian" class="print:hidden mt-6 bg-white/80 backdrop-blur-md p-3 rounded-2xl flex flex-wrap justify-center items-center gap-2 border border-slate-200 shadow-lg">
                        <select name="tipe" class="bg-white text-slate-800 border border-slate-300 rounded-lg px-3 py-1.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-teal-400">
                            <option value="pengendalian_lapangan" {{ request('tipe', 'pengendalian_lapangan') == 'pengendalian_lapangan' ? 'selected' : '' }}>Pengendalian Lapangan</option>
                            <option value="capaian_program" {{ request('tipe') == 'capaian_program' ? 'selected' : '' }}>Capaian Program</option>
                            <option value="elsimil" {{ request('tipe') == 'elsimil' ? 'selected' : '' }}>Capaian Elsimil</option>
                            <option value="quick_win" {{ request('tipe') == 'quick_win' ? 'selected' : '' }}>Laporan Quick Win</option>
                        </select>
                        <select name="bulan" class="bg-white text-slate-800 border border-slate-300 rounded-lg px-3 py-1.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-teal-400">
                            @for($m=1;$m<=12;$m++)
                                <option value="{{$m}}" {{$bulan==$m?'selected':''}}>{{App\Models\LaporanCapaian::namaBulan($m)}}</option>
                            @endfor
                        </select>
                        <select name="tahun" class="bg-white text-slate-800 border border-slate-300 rounded-lg px-3 py-1.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-teal-400">
                            @for($y=2024;$y<=now()->year+1;$y++)
                                <option value="{{$y}}" {{$tahun==$y?'selected':''}}>{{$y}}</option>
                            @endfor
                        </select>
                        <button type="submit" class="bg-teal-600 text-white border-none rounded-lg px-5 py-1.5 text-sm font-bold hover:bg-teal-700 transition-colors shadow-md">Tampilkan</button>
                    </form>
                </div>

                @if(request('tipe', 'pengendalian_lapangan') == 'pengendalian_lapangan')
                    @if(isset($laporans['pengendalian_lapangan']))
                        @php $d = $laporans['pengendalian_lapangan']->data; @endphp
                        @include('laporan-capaian-pengendalian')

                    @else
                        <div class="text-center py-10 text-slate-500">Data tidak ditemukan.</div>
                    @endif
                    @elseif(request('tipe') == 'capaian_program')
                    @if(isset($laporans['capaian_program']))
                        @php $d = $laporans['capaian_program']->data; @endphp

                        @include('laporan-capaian-program')

                    @else
                        <div class="text-center py-32 text-yellow-300 font-bold text-2xl drop-shadow-md">Data Capaian Program tidak tersedia untuk periode ini</div>
                    @endif
                @elseif(request('tipe') == 'elsimil')
                    @if(!isset($laporans['elsimil']))
                        <div class="text-center py-32 text-slate-400 font-bold text-2xl drop-shadow-sm">Data Capaian Elsimil tidak tersedia untuk periode ini</div>
                    @else
                        @php
                            $d = $laporans['elsimil']->data;
                        @endphp
                        
                        @include('laporan-capaian-elsimil')
                    @endif
                @elseif(request('tipe') == 'quick_win')
                    <!-- Laporan Quick Win -->
                    @php
                        $qw = $laporans['quick_win'] ?? null;
                        $d = $qw ? $qw->data : [];
                        
                        // Helper formatter
                        $fmt = function($v) { 
                            $v = floatval($v); 
                            return (floor($v) == $v) ? number_format($v, 0, ',', '.') : number_format($v, 2, ',', '.'); 
                        };
                    @endphp

                    <!-- DASHBOARD CONTAINER -->
                    <div class="mb-2 text-[var(--text)] w-full max-w-[1440px] mx-auto px-4 sm:px-6 relative">
                        
                        <!-- WATERMARK BACKGROUND -->
                        <div class="absolute inset-0 pointer-events-none z-[0] flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('public/image/logoBKKBN.png') }}" class="w-[60%] max-w-[800px] object-contain opacity-[0.05]" alt="Watermark BKKBN">
                        </div>

                        <!-- 1. HERO SECTION -->
                        <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] overflow-hidden mb-6 relative z-10">
                            <div class="relative z-10 px-6 py-8 md:px-10 flex flex-col md:flex-row items-center justify-between gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-4">
                                         <img src="{{ asset('public/image/logoBKKBN.png') }}" style="height: 56px;" alt="Watermark BKKBN">
                                        <div class="leading-snug">
                                                <div class="text-sm sm:text-base font-semibold text-slate-500 export-fix-text">Kementerian Kependudukan dan Pembangunan Keluarga/BKKBN</div>
                                                <div class="text-base sm:text-lg font-extrabold text-teal-700 export-fix-text">Perwakilan BKKBN Provinsi Aceh</div>
                                            </div>
                                    </div>
                                    <div style="display: inline-block; padding: 8px 16px; background: var(--canvas); color: var(--teal); font-size: 1.125rem; font-weight: 600; border-radius: 6px; border: 1px solid var(--line); margin-bottom: 16px; white-space: nowrap;">
                                        <i class="fa-solid fa-calendar-alt export-shift-icon" style="vertical-align: middle; margin-top: -2px;"></i>
                                        <span class="export-fix-text" style="vertical-align: middle; margin-left: 6px; display: inline-block;">Periode: {{ App\Models\LaporanCapaian::namaBulan($qw ? $qw->bulan : $bulan) }} {{ $qw ? $qw->tahun : $tahun }}</span>
                                    </div>
                                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-[var(--navy)] mb-2 uppercase tracking-tight export-fix-text">LAPORAN QUICK WIN</h1>
                                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[var(--teal)] mb-4 export-fix-text">KEMENDUKBANGGA (BKKBN)</h2>
                                    <div class="flex flex-wrap gap-3 text-base text-[var(--muted)]">
                                        <div style="display: inline-block; background: #f1f5f9; padding: 8px 14px; border-radius: 6px; white-space: nowrap;">
                                            <span class="export-fix-text" style="display: inline-block;">Update data: {{ $qw ? $qw->updated_at->format('d M Y') : '-' }}</span>
                                        </div>
                                        <div style="display: inline-block; background: #f1f5f9; padding: 8px 14px; border-radius: 6px; white-space: nowrap;">
                                            <span class="export-fix-text" style="display: inline-block;">Sumber: Perwakilan BKKBN Prov Aceh</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. PANEL QUICK WIN (GRID 2x2) -->
                        <div class="mb-4 mt-8 flex items-center justify-between border-b-2 border-[var(--navy)] pb-2 relative z-10">
                            <h2 class="text-2xl font-extrabold text-[var(--navy)] uppercase tracking-tight export-fix-text">Ringkasan Empat Quick Win</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 relative z-10">
                            
                            <!-- 1. SIDAYA -->
                            <div class="export-card bg-white rounded-xl border border-slate-300 relative p-4 md:p-5 flex flex-col h-full shadow-sm">
                                <div class="flex justify-between items-start mb-4 border-b-2 pb-3" style="border-color: var(--teal)">
                                    <div class="flex-1 pr-2">
                                        <h3 class="font-black text-[var(--navy)] text-2xl md:text-3xl mb-1 export-fix-text" style="line-height: 1.2;">SIDAYA</h3>
                                        <div class="text-sm font-bold text-[var(--muted)] uppercase tracking-wide export-fix-text">Lansia Berdaya</div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col lg:flex-row gap-4 items-center lg:items-start mb-2 flex-grow">
                                    <div class="w-32 md:w-36 shrink-0 flex items-center justify-center">
                                        <img src="{{ asset('public/image/qw_sidaya_new.png') }}" class="w-full h-auto object-contain hover:scale-105 transition-transform origin-center">
                                    </div>
                                    
                                    <div class="flex-1 flex flex-col gap-3 w-full">
                                        <!-- Pemeriksaan Kesehatan -->
                                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200" style="display: block;">
                                            <div class="text-xs font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Pemeriksaan Kesehatan</div>
                                            @php $pct1 = $d['sidaya']['pemeriksaan_kesehatan']['persentase'] ?? 0; @endphp
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="font-black text-[var(--navy)] text-xl leading-none"><span class="export-fix-text">{{ $fmt($pct1) }}%</span></div>
                                                <div class="text-[10px] sm:text-xs font-medium text-slate-500"><span class="export-fix-text">{{ number_format($d['sidaya']['pemeriksaan_kesehatan']['capaian'] ?? 0, 0, ',', '.') }} capai / {{ number_format($d['sidaya']['pemeriksaan_kesehatan']['target'] ?? 0, 0, ',', '.') }} target</span></div>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5">
                                                <div class="h-full rounded-full" style="background-color: var(--teal); width: {{ min($pct1, 100) }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Kader BKL Terlatih -->
                                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200" style="display: block;">
                                            <div class="text-xs font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Kader BKL Terlatih PJP</div>
                                            @php $pct2 = $d['sidaya']['pelatihan_pjp']['persentase'] ?? 0; @endphp
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="font-black text-[var(--navy)] text-xl leading-none"><span class="export-fix-text">{{ $fmt($pct2) }}%</span></div>
                                                <div class="text-[10px] sm:text-xs font-medium text-slate-500"><span class="export-fix-text">{{ number_format($d['sidaya']['pelatihan_pjp']['capaian'] ?? 0, 0, ',', '.') }} capai / {{ number_format($d['sidaya']['pelatihan_pjp']['target'] ?? 0, 0, ',', '.') }} target</span></div>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5">
                                                <div class="h-full rounded-full" style="background-color: var(--teal); width: {{ min($pct2, 100) }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Peserta Sekolah Lansia -->
                                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200" style="display: block;">
                                            <div class="text-xs font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Peserta Sekolah Lansia</div>
                                            @php $pct3 = $d['sidaya']['sekolah_lansia']['persentase'] ?? 0; @endphp
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="font-black text-[var(--navy)] text-xl leading-none"><span class="export-fix-text">{{ $fmt($pct3) }}%</span></div>
                                                <div class="text-[10px] sm:text-xs font-medium text-slate-500"><span class="export-fix-text">{{ number_format($d['sidaya']['sekolah_lansia']['capaian'] ?? 0, 0, ',', '.') }} capai / {{ number_format($d['sidaya']['sekolah_lansia']['target'] ?? 0, 0, ',', '.') }} target</span></div>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5">
                                                <div class="h-full rounded-full" style="background-color: var(--teal); width: {{ min($pct3, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. GATI -->
                            <div class="export-card bg-white rounded-xl border border-slate-300 relative p-4 md:p-5 flex flex-col h-full shadow-sm">
                                <div class="flex justify-between items-start mb-4 border-b-2 pb-3" style="border-color: var(--cyan)">
                                    <div class="flex-1 pr-2">
                                        <h3 class="font-black text-[var(--navy)] text-2xl md:text-3xl mb-1 export-fix-text" style="line-height: 1.2;">GATI</h3>
                                        <div class="text-sm font-bold text-[var(--muted)] uppercase tracking-wide export-fix-text">Gerakan Ayah Teladan</div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col lg:flex-row gap-4 items-center lg:items-start mb-2 flex-grow">
                                    <div class="w-32 md:w-36 shrink-0 flex items-center justify-center">
                                        <img src="{{ asset('public/image/qw_gati_new.png') }}" class="w-full h-auto object-contain hover:scale-105 transition-transform origin-center">
                                    </div>
                                    
                                    <div class="flex-1 flex flex-col gap-3 w-full">
                                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200" style="display: block;">
                                            <div class="text-xs font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Fasilitasi Edukasi GATI</div>
                                            @php $pctG = $d['gati']['edukasi']['persentase'] ?? 0; @endphp
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="font-black text-[var(--navy)] text-3xl leading-none"><span class="export-fix-text">{{ $fmt($pctG) }}%</span></div>
                                            </div>
                                            <div class="text-xs font-medium text-slate-500 mb-2"><span class="export-fix-text">{{ number_format($d['gati']['edukasi']['total'] ?? 0, 0, ',', '.') }} capai / {{ number_format($d['gati']['edukasi']['target'] ?? 0, 0, ',', '.') }} target</span></div>
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="h-full rounded-full" style="background-color: var(--cyan); width: {{ min($pctG, 100) }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-3 gap-2 mt-1">
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-center" style="display: block;">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Kompak Tenan</div>
                                                <div class="font-black text-[var(--navy)] text-sm md:text-base"><span class="export-fix-text">{{ number_format($d['gati']['edukasi']['kompak_tenan'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-center" style="display: block;">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Dekat</div>
                                                <div class="font-black text-[var(--navy)] text-sm md:text-base"><span class="export-fix-text">{{ number_format($d['gati']['edukasi']['dekat'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-center" style="display: block;">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Sebaya</div>
                                                <div class="font-black text-[var(--navy)] text-sm md:text-base"><span class="export-fix-text">{{ number_format($d['gati']['edukasi']['sebaya'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. TAMASYA -->
                            <div class="export-card bg-white rounded-xl border border-slate-300 relative p-4 md:p-5 flex flex-col h-full shadow-sm">
                                <div class="flex justify-between items-start mb-4 border-b-2 pb-3" style="border-color: var(--orange)">
                                    <div class="flex-1 pr-2">
                                        <h3 class="font-black text-[var(--navy)] text-2xl md:text-3xl mb-1 export-fix-text" style="line-height: 1.2;">TAMASYA</h3>
                                        <div class="text-sm font-bold text-[var(--muted)] uppercase tracking-wide export-fix-text">Taman Asuh Sayang Anak</div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col lg:flex-row gap-4 items-center lg:items-start mb-2 flex-grow">
                                    <div class="w-32 md:w-36 shrink-0 flex flex-col items-center justify-center gap-4">
                                        <img src="{{ asset('public/image/qw_tamasya_new.png') }}" class="w-full h-auto object-contain hover:scale-105 transition-transform origin-center">
                                        
                                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center w-full shadow-sm" style="display: block;">
                                            <div class="text-[10px] font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Jumlah TPA</div>
                                            <div class="font-black text-[var(--orange)] text-2xl md:text-3xl"><span class="export-fix-text">{{ number_format($d['tamasya']['jumlah_tpa'] ?? 0, 0, ',', '.') }}</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 flex flex-col gap-2 w-full">
                                        <!-- 4 Layanan Utama -->
                                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                            <div class="col-span-2 text-[11px] font-bold text-[var(--muted)] uppercase border-b border-slate-200 pb-1 mb-1 export-fix-text">4 Layanan Utama</div>
                                            <div style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase export-fix-text">Memenuhi</div>
                                                <div class="font-black text-[var(--navy)] text-base"><span class="export-fix-text">{{ number_format($d['tamasya']['memenuhi_4_layanan']['memenuhi'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase export-fix-text">Tidak Memenuhi</div>
                                                <div class="font-black text-[var(--navy)] text-base"><span class="export-fix-text">{{ number_format($d['tamasya']['memenuhi_4_layanan']['tidak_memenuhi'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Pelaporan -->
                                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                            <div class="col-span-2 text-[11px] font-bold text-[var(--muted)] uppercase border-b border-slate-200 pb-1 mb-1 export-fix-text">Status Pelaporan</div>
                                            <div style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase export-fix-text">Dilaporkan</div>
                                                <div class="font-black text-[var(--navy)] text-base"><span class="export-fix-text">{{ number_format($d['tamasya']['status_pelaporan']['dilaporkan'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase export-fix-text">Belum Lapor</div>
                                                <div class="font-black text-[var(--navy)] text-base"><span class="export-fix-text">{{ number_format($d['tamasya']['status_pelaporan']['tidak_dilaporkan'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Pemutakhiran Data -->
                                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                            <div class="col-span-2 text-[11px] font-bold text-[var(--muted)] uppercase border-b border-slate-200 pb-1 mb-1 export-fix-text">Pemutakhiran Data</div>
                                            <div style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase export-fix-text">Sudah</div>
                                                <div class="font-black text-[var(--navy)] text-base"><span class="export-fix-text">{{ number_format($d['tamasya']['pemutakhiran_data']['sudah'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase export-fix-text">Belum</div>
                                                <div class="font-black text-[var(--navy)] text-base"><span class="export-fix-text">{{ number_format($d['tamasya']['pemutakhiran_data']['belum'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. GENTING -->
                            <div class="export-card bg-white rounded-xl border border-slate-300 relative p-4 md:p-5 flex flex-col h-full shadow-sm">
                                <div class="flex justify-between items-start mb-4 border-b-2 pb-3" style="border-color: var(--red)">
                                    <div class="flex-1 pr-2">
                                        <h3 class="font-black text-[var(--navy)] text-2xl md:text-3xl mb-1 export-fix-text" style="line-height: 1.2;">GENTING</h3>
                                        <div class="text-sm font-bold text-[var(--muted)] uppercase tracking-wide export-fix-text">Cegah Stunting</div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col lg:flex-row gap-4 items-center lg:items-start mb-2 flex-grow">
                                    <div class="w-32 md:w-36 shrink-0 flex items-center justify-center">
                                        <img src="{{ asset('public/image/qw_genting_new.png') }}" class="w-full h-auto object-contain hover:scale-105 transition-transform origin-center">
                                    </div>
                                    
                                    <div class="flex-1 flex flex-col gap-3 w-full">
                                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200" style="display: block;">
                                            <div class="text-xs font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Fasilitasi Program GENTING</div>
                                            @php $pctGen = $d['genting']['fasilitasi']['persentase'] ?? 0; @endphp
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="font-black text-[var(--navy)] text-3xl leading-none"><span class="export-fix-text">{{ $fmt($pctGen) }}%</span></div>
                                            </div>
                                            <div class="text-xs font-medium text-slate-500 mb-2"><span class="export-fix-text">{{ number_format($d['genting']['fasilitasi']['total'] ?? 0, 0, ',', '.') }} bantuan / {{ number_format($d['genting']['fasilitasi']['target'] ?? 0, 0, ',', '.') }} target</span></div>
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="h-full rounded-full" style="background-color: var(--red); width: {{ min($pctGen, 100) }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-[11px] font-bold text-center text-slate-500 uppercase mt-2 mb-1 export-fix-text">Sebaran Rincian Bantuan</div>
                                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-1 md:gap-2">
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg py-2 px-1 text-center" style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Nutrisi</div>
                                                <div class="font-black text-[var(--navy)] text-sm"><span class="export-fix-text">{{ number_format($d['genting']['fasilitasi']['nutrisi'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg py-2 px-1 text-center" style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Sanitasi</div>
                                                <div class="font-black text-[var(--navy)] text-sm"><span class="export-fix-text">{{ number_format($d['genting']['fasilitasi']['sanitasi'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg py-2 px-1 text-center" style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Air Bersih</div>
                                                <div class="font-black text-[var(--navy)] text-sm"><span class="export-fix-text">{{ number_format($d['genting']['fasilitasi']['air_bersih'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg py-2 px-1 text-center" style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Rmh Layak</div>
                                                <div class="font-black text-[var(--navy)] text-sm"><span class="export-fix-text">{{ number_format($d['genting']['fasilitasi']['rumah_layak'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg py-2 px-1 text-center" style="display: block;">
                                                <div class="text-[9px] font-bold text-slate-500 uppercase mb-1 export-fix-text">Edukasi</div>
                                                <div class="font-black text-[var(--navy)] text-sm"><span class="export-fix-text">{{ number_format($d['genting']['fasilitasi']['edukasi'] ?? 0, 0, ',', '.') }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. FOOTER RESMI -->
                        <div class="bg-[var(--navy)] text-white rounded-xl mt-8 shadow-sm px-8 py-6 flex items-center justify-between relative z-10">
                            <!-- KIRI: Logo -->
                            <div class="flex-1 flex justify-start shrink-0">
                                <img src="{{ asset('public/image/logo-putih.png') }}" class="h-16 lg:h-20 object-contain drop-shadow-sm" alt="BKKBN Logo Putih">
                            </div>

                            <!-- TENGAH: Judul -->
                            <div class="flex-[1.5] flex flex-col items-center text-center px-6 border-x-2 border-white/20">
                                <div class="font-bold text-base lg:text-lg tracking-wide export-fix-text">LAPORAN QUICK WIN</div>
                                <div class="text-sm text-blue-200 opacity-90 mt-1.5"><span class="export-fix-text">Dicetak pada {{ date('d/m/Y H:i') }}</span></div>
                            </div>

                            <!-- KANAN: Info Kontak -->
                            <div class="flex-1 flex flex-col items-end gap-1.5 text-xs lg:text-sm shrink-0">
                                <div style="white-space: nowrap;">
                                    <i class="fa-solid fa-headset text-blue-400 export-shift-icon text-sm lg:text-base" style="vertical-align: middle; margin-right: 6px;"></i>
                                    <span style="vertical-align: middle;"><span class="export-fix-text">Pengaduan <span class="text-yellow-400 font-bold">085361209387</span></span></span>
                                </div>
                                <div style="white-space: nowrap;">
                                    <i class="fa-solid fa-globe text-blue-400 export-shift-icon text-sm lg:text-base" style="vertical-align: middle; margin-right: 6px;"></i>
                                    <span class="text-blue-200" style="vertical-align: middle;"><span class="export-fix-text">aceh.kemendukbangga.go.id</span></span>
                                </div>
                                <div style="white-space: nowrap;">
                                    <i class="fa-brands fa-instagram text-blue-400 export-shift-icon text-sm lg:text-base" style="vertical-align: middle; margin-right: 6px;"></i>
                                    <span class="text-blue-200" style="vertical-align: middle;"><span class="export-fix-text">kemendukbangga_bkkbnaceh</span></span>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>



    </div>

    <script>
        // Zoom Functions
        let zoom = 1;
        function updateZoom() {
            const el = document.getElementById('posterContent');
            el.style.transform = `scale(${zoom})`;
            el.style.transformOrigin = 'top center';
            if (zoom !== 1) el.style.marginBottom = `${(zoom - 1) * el.offsetHeight}px`;
            else el.style.marginBottom = '0';
            document.getElementById('zoomLevel').innerText = Math.round(zoom * 100) + '%';
        }
        function zoomIn() { if (zoom < 2) { zoom += 0.1; updateZoom(); } }
        function zoomOut() { if (zoom > 0.3) { zoom -= 0.1; updateZoom(); } }
        function zoomReset() { zoom = 1; updateZoom(); }

        // Download PNG/PDF functions
            async function d(type = 'png') {
                const btnContainer = document.querySelector('.floating-controls') || document.querySelector('.flex.justify-center.gap-4.mt-8.mb-12');
                const zoomContainer = document.querySelector('.fixed.bottom-6.left-6');
                const target = document.getElementById('posterContent');
                const formFilters = target.querySelector('form');
                
                if(btnContainer) btnContainer.style.display = 'none';
                if(zoomContainer) zoomContainer.style.display = 'none';
                if(formFilters) formFilters.style.display = 'none';
                
                const origZoom = zoom;
                zoom = 1; updateZoom();

                // Simpan posisi scroll dan style asli
                const origScrollX = window.scrollX;
                const origScrollY = window.scrollY;
                const origWidth = target.style.width;
                const origMargin = target.style.margin;
                const origBorderRadius = target.style.borderRadius;
                const origBg = target.style.backgroundColor;
                const origPadding = target.style.paddingBottom;
                const origOverflow = target.style.overflow;

                // KUNCI: Scroll ke atas dulu agar html2canvas tidak salah hitung offset
                window.scrollTo(0, 0);

                // Set fixed width langsung di elemen (BUKAN via windowWidth option)
                target.style.width = '1280px';
                target.style.height = 'auto'; // KUNCI: Hindari min-height bawaan yg bikin ruang kosong
                target.style.minHeight = '0';
                target.style.margin = '0';
                target.style.borderRadius = '0';
                target.style.backgroundColor = '#F4F7FB';
                target.style.paddingBottom = '32px'; // Secukupnya agar footer tidak mepet
                target.style.overflow = 'visible';
                target.classList.remove('sm:mx-4', 'mt-4', 'overflow-hidden');
                
                // Tunggu font loaded + layout settle
                await document.fonts.ready;
                
                // KUNCI: Tambahkan class exporting agar CSS fix (transform translateY) aktif
                document.body.classList.add('exporting');
                await new Promise(r => setTimeout(r, 500)); // beri waktu reflow

                try {
                    const canvas = await html2canvas(target, { 
                        scale: 2, 
                        useCORS: true,
                        backgroundColor: '#F4F7FB',
                        logging: false,
                        scrollX: 0,
                        scrollY: 0,
                        x: 0,
                        y: 0,
                        width: target.offsetWidth,
                        height: target.offsetHeight,
                        windowWidth: 1280,
                        onclone: function(clonedDoc) {
                            clonedDoc.body.classList.add('exporting');

                            const pc = clonedDoc.getElementById('posterContent');
                            if (pc) {
                                pc.style.backgroundColor = '#F4F7FB';
                            }

                            // Fix drop-shadows
                            const drops = clonedDoc.querySelectorAll('[class*="drop-shadow"]');
                            drops.forEach(d => { d.style.filter = 'none'; });

                            // Fix gold-card glitch
                            const goldCards = clonedDoc.querySelectorAll('.gold-card');
                            goldCards.forEach(card => { card.style.boxShadow = 'none'; });
                            
                            // Hide decorative background blur elements (NOT content containers with backdrop-blur)
                            const blurs = clonedDoc.querySelectorAll('.blur-sm, .blur-md, .blur-lg, .blur-xl, .blur-2xl, .blur-3xl');
                            blurs.forEach(b => {
                                if (b.style) b.style.display = 'none';
                            });

                            // Fix backdrop-blur footer
                            const footerBox = clonedDoc.querySelector('.max-w-4xl.bg-teal-900\\/95');
                            if (footerBox) {
                                footerBox.classList.remove('backdrop-blur-md');
                                footerBox.style.boxShadow = 'none';
                            }
                        }
                    });
                    
                    const dataUrl = canvas.toDataURL('image/png');
                    
                    if (type === 'png') {
                        const link = document.createElement('a');
                        link.download = `Laporan_Capaian_BKKBN_{{$bulan}}_{{$tahun}}.png`;
                        link.href = dataUrl;
                        link.click();
                    } else if (type === 'pdf') {
                        const pdf = new window.jspdf.jsPDF({
                            orientation: canvas.width > canvas.height ? 'landscape' : 'portrait',
                            unit: 'px',
                            format: [canvas.width, canvas.height]
                        });
                        pdf.addImage(dataUrl, 'PNG', 0, 0, canvas.width, canvas.height);
                        pdf.save(`Laporan_Capaian_BKKBN_{{$bulan}}_{{$tahun}}.pdf`);
                    }

                } catch (err) {
                    console.error('Error setting up export', err);
                    alert('Gagal mengekspor laporan: ' + (err && err.message ? err.message : String(err)));
                } finally {
                    // Kembalikan semua style asli
                    document.body.classList.remove('exporting');
                    target.style.width = origWidth;
                    target.style.margin = origMargin;
                    target.style.borderRadius = origBorderRadius;
                    target.style.backgroundColor = origBg;
                    target.style.paddingBottom = origPadding;
                    target.style.overflow = origOverflow;
                    
                    if(btnContainer) btnContainer.style.display = 'flex';
                    if(zoomContainer) zoomContainer.style.display = 'flex';
                    if(formFilters) formFilters.style.display = 'flex';
                    zoom = origZoom; updateZoom();
                    window.scrollTo(origScrollX, origScrollY);
                }
            }
    </script>
</x-layout>