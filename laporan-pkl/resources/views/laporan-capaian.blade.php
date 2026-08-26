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
        body.exporting div {
            line-height: normal !important;
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
                <div class="flex flex-col items-center mb-6 mt-4 relative">
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

                        <!-- Ambient Glow & Additional Watermarks for "Wah" Effect -->
                        <div class="absolute top-[15%] left-0 w-[500px] h-[500px] pointer-events-none z-0" style="background: radial-gradient(circle, rgba(253,224,71,0.08) 0%, rgba(253,224,71,0) 70%);"></div>
                        <div class="absolute top-[45%] right-0 w-[600px] h-[600px] pointer-events-none z-0" style="background: radial-gradient(circle, rgba(45,212,191,0.08) 0%, rgba(45,212,191,0) 70%);"></div>
                        <div class="absolute top-[80%] left-[20%] w-[500px] h-[500px] pointer-events-none z-0" style="background: radial-gradient(circle, rgba(253,224,71,0.08) 0%, rgba(253,224,71,0) 70%);"></div>
                        
                        <!-- Extra Watermarks removed as requested (user wants only 1 big logo) -->
                        <!-- SECTION 1: 5 BADGE FASKES -->
                        <div class="flex justify-center mt-6 mb-2 relative z-10 w-full">
                            <div class="flex items-center justify-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 px-8 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                <span class="pill-text inline-block relative z-10" style="top: 0px;">CAKUPAN TEMPAT PELAYANAN KESEHATAN</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6 px-2 pb-6">
                            @php
                                $faskes = [
                                    ['title' => 'Pemerintah', 'svg' => asset('public/image/pemerintah_3d.png') . '?v=' . time(), 'data' => $d['cakupan_fasyankes']['pemerintah'] ?? []],
                                    ['title' => 'Jaringan', 'svg' => asset('public/image/jaringan_3d.png') . '?v=' . time(), 'data' => $d['cakupan_fasyankes']['jaringan'] ?? []],
                                    ['title' => 'Swasta', 'svg' => asset('public/image/swasta_3d.png') . '?v=' . time(), 'data' => $d['cakupan_fasyankes']['swasta'] ?? []],
                                    ['title' => 'PMB Setara', 'svg' => asset('public/image/pmb_setara_3d.png') . '?v=' . time(), 'data' => $d['cakupan_fasyankes']['pmb_setara'] ?? []],
                                    ['title' => 'PMB Jejaring', 'svg' => asset('public/image/pmb_jejaring_3d.png') . '?v=' . time(), 'data' => $d['cakupan_fasyankes']['pmb_jejaring'] ?? []]
                                ];
                            @endphp
                            @foreach($faskes as $i => $f)
                            <div class="dark-green-card rounded-xl p-3 text-sm relative flex flex-col justify-center">
                                <div class="flex items-center mb-2 border-b border-teal-700 pb-2">
                                    <div class="w-10 h-10 rounded-full border-2 border-yellow-400 overflow-hidden bg-teal-900 shrink-0 mr-2 flex items-center justify-center">
                                        <img src="{{ $f['svg'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <h4 class="font-bold text-white text-xs md:text-[13px] leading-tight">{{ $f['title'] }}</h4>
                                </div>
                                <div class="text-gray-200 text-xs mt-auto">
                                    <div class="flex justify-between mb-1"><span>Ada</span> <span>= {{ number_format($f['data']['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Lapor</span> <span>= {{ number_format($f['data']['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-2 pt-2 border-t border-teal-700 text-white font-bold"><span>Persentase</span> <span class="text-yellow-300">{{ number_format($f['data']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- SECTION 2: STOCK OPNAME SIRIKA -->
                        <div class="mt-8 relative">
                            <div class="flex justify-center mb-6 relative z-10 mt-4 text-center">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/stock_opname_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="Stock Opname">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">STOCK OPNAME SIRIKA</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-2">
                                @php
                                    $stocks = [
                                        ['title' => 'a. Gudang Provinsi', 'char' => 'a', 'data' => $d['stock_opname']['gudang_provinsi'] ?? []],
                                        ['title' => 'b. Gudang Kab/Kota', 'char' => 'b', 'data' => $d['stock_opname']['gudang_kabkota'] ?? []],
                                        ['title' => 'c. Gudang Fasyankes', 'char' => 'c', 'data' => $d['stock_opname']['gudang_fasyankes'] ?? []],
                                    ];
                                @endphp
                                @foreach($stocks as $s)
                                <div class="dark-green-card rounded-xl p-5 text-sm relative">
                                    <div class="flex items-center mb-1 border-b border-teal-700 pb-2">
                                        <div class="w-6 h-6 rounded-full bg-white text-teal-900 font-bold flex items-center justify-center mr-2 text-sm shrink-0"><span class="shift-up-export">{{ $s['char'] }}</span></div>
                                        <h4 class="font-bold text-yellow-300 text-sm md:text-base whitespace-nowrap">{{ $s['title'] }}</h4>
                                    </div>
                                    <div class="text-gray-200 pl-8">
                                        <div class="flex justify-between mb-2"><span>- ada</span> <span>= {{ number_format($s['data']['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-2"><span>- laporan</span> <span>= {{ number_format($s['data']['laporan'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white font-bold"><span>- persentase</span> <span class="text-yellow-300">= {{ number_format($s['data']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- SECTION 3: KB BARU -->
                        <div class="mt-8 relative">
                            <div class="flex justify-center mb-6 relative z-10 mt-4 text-center">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/kb_baru_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="KB Baru">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">CAPAIAN PESERTA KB BARU</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2">
                                @php
                                    $kbBaru = [
                                        ['title' => 'Peserta KB Baru (PB)', 'data' => $d['kb_baru']['pb'] ?? []],
                                        ['title' => 'Peserta KB Pasca Persalinan', 'data' => $d['kb_baru']['pb_pasca_persalinan'] ?? []],
                                        ['title' => 'PB MKJP', 'data' => $d['kb_baru']['pb_mkjp'] ?? []],
                                        ['title' => 'PB Non MKJP', 'data' => $d['kb_baru']['pb_non_mkjp'] ?? []],
                                    ];
                                @endphp
                                @foreach($kbBaru as $kb)
                                <div class="dark-green-card rounded-xl p-4 text-sm relative flex flex-col justify-center">
                                    <h4 class="font-bold text-center text-yellow-300 text-[13px] mb-2 h-10 flex items-center justify-center">{{ $kb['title'] }}</h4>
                                    <div class="text-gray-200 text-xs mt-auto">
                                        <div class="flex justify-between mb-1.5"><span>PPM</span> <span>= {{ number_format($kb['data']['ppm'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1.5"><span>Capaian</span> <span>= {{ number_format($kb['data']['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-2 pt-2 border-t border-teal-700 text-white font-bold"><span>Persentase</span> <span class="text-yellow-300">= {{ number_format($kb['data']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- SECTION 4: KB AKTIF -->
                        <div class="mt-8 relative">
                            <div class="flex justify-center mb-6 relative z-10 mt-4 text-center">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/kb_aktif_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="KB Aktif">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">CAPAIAN PESERTA KB AKTIF</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-2 items-center">
                                <!-- Aktif MKJP -->
                                <div class="dark-green-card rounded-xl p-5 h-full flex flex-col justify-center">
                                    <h4 class="font-bold text-center text-white pb-3 mb-2 uppercase text-sm border-b border-teal-700">PESERTA KB AKTIF<br>MKJP</h4>
                                    <div class="text-gray-200 text-sm">
                                        <div class="flex justify-between mb-2"><span>PPM</span> <span>= {{ number_format($d['kb_aktif']['pa_mkjp']['ppm'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-2"><span>Capaian</span> <span>= {{ number_format($d['kb_aktif']['pa_mkjp']['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white font-bold"><span>Persentase</span> <span class="text-yellow-300">= {{ number_format($d['kb_aktif']['pa_mkjp']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                
                                <!-- Detail PA -->
                                <div class="gold-card rounded-xl z-10 p-5 h-full shadow-2xl flex flex-col justify-center border-4 border-yellow-400">
                                    <h4 class="font-bold text-center text-teal-900 pb-2 mb-2 text-sm uppercase border-b border-teal-700/30">DETAIL PA (MODERN & TRADISIONAL)</h4>
                                    <div class="font-bold text-teal-800 text-sm mb-1.5">PA Modern:</div>
                                    <div class="pl-4 text-teal-900 text-sm">
                                        <div class="flex justify-between mb-1.5"><span>PPM</span> <span>= {{ number_format($d['kb_aktif']['pa_modern']['ppm'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1.5"><span>Capaian</span> <span>= {{ number_format($d['kb_aktif']['pa_modern']['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-2 pt-2 border-t border-teal-700/30 font-bold"><span>Persentase</span> <span>= {{ number_format($d['kb_aktif']['pa_modern']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                    <div class="flex justify-between text-sm mt-4 pt-2 border-t border-teal-700/30 text-teal-900 font-bold w-full">
                                        <span>PA Tradisional</span> <span>= {{ number_format($d['kb_aktif']['pa_tradisional'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-base mt-3 pt-3 border-t border-teal-700/50 text-teal-900 font-bold w-full">
                                        <span>Total PA</span> <span>= {{ number_format($d['kb_aktif']['pa_keseluruhan'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Aktif Non MKJP -->
                                <div class="dark-green-card rounded-xl p-5 h-full flex flex-col justify-center">
                                    <h4 class="font-bold text-center text-white pb-3 mb-2 uppercase text-sm border-b border-teal-700">PESERTA KB AKTIF<br>NON MKJP</h4>
                                    <div class="text-gray-200 text-sm">
                                        <div class="flex justify-between mb-2"><span>PPM</span> <span>= {{ number_format($d['kb_aktif']['pa_non_mkjp']['ppm'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-2"><span>Capaian</span> <span>= {{ number_format($d['kb_aktif']['pa_non_mkjp']['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white font-bold"><span>Persentase</span> <span class="text-yellow-300">= {{ number_format($d['kb_aktif']['pa_non_mkjp']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: mCPR & UNMET NEED -->
                        <div class="mt-8 mb-6 flex flex-col items-center text-center relative w-full">
                            
                            <!-- Ornamen Background (Rumah Aceh & Masjid) -->
                            <img src="{{ asset('public/image/rumah_aceh.png') }}" alt="Rumah Adat Aceh" class="absolute bottom-0 left-0 w-64 sm:w-80 drop-shadow-[0_0_15px_rgba(255,215,0,0.3)] pointer-events-none hidden md:block z-0 opacity-90">
                            <img src="{{ asset('public/image/masjid_emas.png') }}" alt="Masjid Emas" class="absolute bottom-0 right-0 w-64 sm:w-80 drop-shadow-[0_0_15px_rgba(255,215,0,0.3)] pointer-events-none hidden md:block z-0 opacity-90">

                            <div class="flex justify-center mb-6 relative z-10 mt-4 w-full">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/mcpr_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="mCPR">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">mCPR DAN UNMET NEED</span>
                                </div>
                            </div>
                            </div>
                            <div class="flex flex-wrap justify-center gap-12 px-2 relative z-10">
                                
                                <!-- mCPR -->
                                <div class="gold-card p-2 w-[210px] h-[210px] flex items-center justify-center" style="border-radius: 9999px;">
                                    <div class="bg-teal-900 w-full h-full flex flex-col items-center justify-center p-4 border border-yellow-400/50" style="border-radius: 9999px;">
                                        <div class="shift-up-export w-full flex flex-col items-center">
                                            <div class="font-black text-lg text-yellow-300 mb-1">mCPR</div>
                                            <div class="text-[10px] text-center mb-2 text-gray-300 border-b border-teal-700 pb-2 w-full px-1">
                                                PUS = {{ number_format($d['mcpr_unmet']['mcpr']['pus'] ?? 0, 0, ',', '.') }}<br>
                                                PA Mod = {{ number_format($d['mcpr_unmet']['mcpr']['pa_modern'] ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div class="font-bold text-[9px] text-white">Persentase</div>
                                            <div class="font-black text-yellow-300 text-base mt-1">{{ number_format($d['mcpr_unmet']['mcpr']['persentase'] ?? 0, 2, ',', '.') }}%</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Unmet -->
                                <div class="gold-card p-2 w-[210px] h-[210px] flex items-center justify-center" style="border-radius: 9999px;">
                                    <div class="bg-teal-900 w-full h-full flex flex-col items-center justify-center p-4 border border-yellow-400/50" style="border-radius: 9999px;">
                                        <div class="shift-up-export w-full flex flex-col items-center">
                                            <div class="font-black text-lg text-yellow-300 mb-1 text-center leading-tight">UNMET<br>NEED</div>
                                            <div class="text-[10px] text-center mb-2 text-gray-300 border-b border-teal-700 pb-2 w-full px-1 mt-1">
                                                PUS = {{ number_format($d['mcpr_unmet']['unmet_need']['pus'] ?? 0, 0, ',', '.') }}<br>
                                                UN = {{ number_format($d['mcpr_unmet']['unmet_need']['un'] ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div class="font-bold text-[9px] text-white">Persentase</div>
                                            <div class="font-black text-yellow-300 text-base mt-1">{{ number_format($d['mcpr_unmet']['unmet_need']['persentase'] ?? 0, 2, ',', '.') }}%</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @else
                        <div class="text-center py-32 text-yellow-300 font-bold text-2xl drop-shadow-md">Data Capaian Program tidak tersedia untuk periode ini</div>
                    @endif
                @elseif(request('tipe') == 'elsimil')
                    @if(!isset($laporans['elsimil']))
                        <div class="text-center py-32 text-yellow-300 font-bold text-2xl drop-shadow-md">Data Capaian Elsimil tidak tersedia untuk periode ini</div>
                    @else
                        @php
                            $d = $laporans['elsimil']->data;
                        @endphp
                        
                        <!-- Elsimil Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-8 lg:gap-12 mt-12 px-2 md:px-6 pb-10">
                            <!-- CHART CATIN -->
                            <div class="relative mt-4">
                                <div class="absolute -top-[25px] -left-[20px] w-20 h-20 rounded-full border-4 border-yellow-400 flex items-center justify-center z-20 overflow-hidden" style="background: radial-gradient(circle at center, #064e3b, #022c22); box-shadow: 0 5px 15px rgba(0,0,0,0.6), inset 0 0 10px rgba(250, 204, 21, 0.4);">
                                    <img src="{{ asset('public/image/catin_icon_3d.png') }}?v={{ time() }}" alt="Catin" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -top-[15px] left-[50px] border-2 border-yellow-400 rounded-lg px-4 py-1 z-10 flex flex-col justify-center" style="background: linear-gradient(to bottom, #022c22, #064e3b); box-shadow: 0 5px 10px rgba(0,0,0,0.5); min-height: 48px;">
                                    <div class="text-yellow-300 text-xs font-semibold leading-none mb-1">Trend</div>
                                    <div class="text-white font-black text-sm md:text-base leading-none uppercase">JUMLAH CATIN TERDAMPINGI</div>
                                </div>
                                <div class="gold-card h-full p-[3px]">
                                    <div class="dark-green-card p-4 pt-16 h-full flex flex-col rounded-[9px]" style="min-height: 350px;">
                                        <div class="w-full grow relative">
                                            <canvas id="catinChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CHART BUMIL -->
                            <div class="relative mt-4">
                                <div class="absolute -top-[25px] -left-[20px] w-20 h-20 rounded-full border-4 border-yellow-400 flex items-center justify-center z-20 overflow-hidden" style="background: radial-gradient(circle at center, #064e3b, #022c22); box-shadow: 0 5px 15px rgba(0,0,0,0.6), inset 0 0 10px rgba(250, 204, 21, 0.4);">
                                    <img src="{{ asset('public/image/bumil_icon_3d.png') }}?v={{ time() }}" alt="Bumil" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -top-[15px] left-[50px] border-2 border-yellow-400 rounded-lg px-4 py-1 z-10 flex flex-col justify-center" style="background: linear-gradient(to bottom, #022c22, #064e3b); box-shadow: 0 5px 10px rgba(0,0,0,0.5); min-height: 48px;">
                                    <div class="text-yellow-300 text-xs font-semibold leading-none mb-1">Trend</div>
                                    <div class="text-white font-black text-sm md:text-base leading-none uppercase">JUMLAH BUMIL TERDAMPINGI</div>
                                </div>
                                <div class="gold-card h-full p-[3px]">
                                    <div class="dark-green-card p-4 pt-16 h-full flex flex-col rounded-[9px]" style="min-height: 350px;">
                                        <div class="w-full grow relative">
                                            <canvas id="bumilChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            window.elsimilData = @json($d);
                        </script>
                    @endif
                @elseif(request('tipe') == 'quick_win')
                    <!-- Laporan Quick Win -->
                    @php
                        $qw = $laporans['quick_win'] ?? null;
                        $d = $qw ? $qw->data : [];
                    @endphp
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 xl:gap-4 mt-6 px-2 xl:px-4 pb-6 max-w-[1400px] w-full mx-auto">
                        <!-- 1. Lansia Berdaya (SIDAYA) -->
                        <!-- 1. SIDAYA (Lansia Berdaya) -->
                        <div class="relative bg-gradient-to-br from-teal-900 via-[#022c22] to-emerald-950 rounded-[30px] border-4 border-yellow-400 shadow-[4px_4px_0px_#fde047] p-4 sm:p-5 overflow-hidden">
                            
                            <!-- Title Ribbon Left -->
                            <div class="absolute top-6 -left-2 bg-yellow-400 text-teal-900 font-black px-4 sm:px-6 py-1.5 text-sm sm:text-base uppercase shadow-xl border-y-4 border-r-4 border-teal-900 z-20">
                                1. SIDAYA (Lansia Berdaya)
                                <div class="absolute -bottom-3 left-0 w-3 h-3 bg-yellow-600" style="clip-path: polygon(0 0, 100% 0, 100% 100%);"></div>
                            </div>

                            <div class="flex flex-col lg:flex-row items-center gap-4 xl:gap-4 pt-10 lg:pt-12">
                                <!-- Image Area (Left) -->
                                <div class="w-full md:w-2/5 flex justify-center relative group">
                                    <img src="{{ asset('public/image/qw_sidaya.png') }}" class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 xl:w-40 xl:h-40 2xl:w-48 2xl:h-48 object-contain relative z-10 transform hover:scale-105 hover:rotate-3 transition-transform duration-500 drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)] rounded-2xl shadow-[0_10px_20px_rgba(0,0,0,0.5)] border-2 border-teal-800/50" alt="SIDAYA">
                                </div>
                                
                                <!-- Stats Area (Right) -->
                                <div class="w-full md:w-3/5 flex flex-col gap-8">
                                    
                                    <div class="relative pl-6 border-l-4 border-cyan-400">
                                        <h3 class="text-cyan-300 font-black text-base sm:text-lg uppercase tracking-widest mb-1">Pemeriksaan Kesehatan</h3>
                                        <div class="flex flex-wrap justify-between items-end gap-4">
                                            <div class="flex gap-2 sm:gap-3">
                                                <div>
                                                    <span class="block text-teal-300 text-xs sm:text-sm font-bold uppercase tracking-wider mb-1">Target</span>
                                                    <span class="block text-white font-black text-lg sm:text-xl">{{ number_format($d['sidaya']['pemeriksaan_kesehatan']['target'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-teal-300 text-xs sm:text-sm font-bold uppercase tracking-wider mb-1">Capaian</span>
                                                    <span class="block text-cyan-400 font-black text-lg sm:text-xl">{{ number_format($d['sidaya']['pemeriksaan_kesehatan']['capaian'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right flex-1 sm:flex-none flex justify-end items-end gap-2">
                                                <span class="block text-yellow-400 font-black text-4xl sm:text-5xl 2xl:text-6xl drop-shadow-[0_4px_4px_rgba(0,0,0,0.8)] leading-none">{{ number_format((float)($d['sidaya']['pemeriksaan_kesehatan']['persentase'] ?? 0), 0, ',', '.') }}</span>
                                                <span class="block text-yellow-400 text-lg sm:text-xl font-black mb-1">
                                                    @php
                                                        $pctStr = number_format((float)($d['sidaya']['pemeriksaan_kesehatan']['persentase'] ?? 0), 2, ',', '.');
                                                        $dec = substr($pctStr, -3);
                                                        if($dec == ',00') echo '%'; else echo $dec.'%';
                                                    @endphp
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative pl-6 border-l-4 border-emerald-400">
                                        <h3 class="text-emerald-300 font-black text-base sm:text-lg uppercase tracking-widest mb-1">Kader BKL Terlatih PJP</h3>
                                        <div class="flex flex-wrap justify-between items-end gap-4">
                                            <div class="flex gap-2 sm:gap-3">
                                                <div>
                                                    <span class="block text-teal-300 text-xs sm:text-sm font-bold uppercase tracking-wider mb-1">Target</span>
                                                    <span class="block text-white font-black text-lg sm:text-xl">{{ number_format($d['sidaya']['pelatihan_pjp']['target'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-teal-300 text-xs sm:text-sm font-bold uppercase tracking-wider mb-1">Capaian</span>
                                                    <span class="block text-emerald-400 font-black text-lg sm:text-xl">{{ number_format($d['sidaya']['pelatihan_pjp']['capaian'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right flex-1 sm:flex-none flex justify-end items-end gap-2">
                                                <span class="block text-yellow-400 font-black text-4xl sm:text-5xl 2xl:text-6xl drop-shadow-[0_4px_4px_rgba(0,0,0,0.8)] leading-none">{{ number_format((float)($d['sidaya']['pelatihan_pjp']['persentase'] ?? 0), 0, ',', '.') }}</span>
                                                <span class="block text-yellow-400 text-lg sm:text-xl font-black mb-1">
                                                    @php
                                                        $pctStr2 = number_format((float)($d['sidaya']['pelatihan_pjp']['persentase'] ?? 0), 2, ',', '.');
                                                        $dec2 = substr($pctStr2, -3);
                                                        if($dec2 == ',00') echo '%'; else echo $dec2.'%';
                                                    @endphp
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative pl-6 border-l-4 border-purple-400">
                                        <h3 class="text-purple-300 font-black text-base sm:text-lg uppercase tracking-widest mb-1">Peserta Sekolah Lansia</h3>
                                        <div class="flex flex-wrap justify-between items-end gap-4">
                                            <div class="flex gap-2 sm:gap-3">
                                                <div>
                                                    <span class="block text-teal-300 text-xs sm:text-sm font-bold uppercase tracking-wider mb-1">Target</span>
                                                    <span class="block text-white font-black text-lg sm:text-xl">{{ number_format($d['sidaya']['sekolah_lansia']['target'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-teal-300 text-xs sm:text-sm font-bold uppercase tracking-wider mb-1">Capaian</span>
                                                    <span class="block text-purple-400 font-black text-lg sm:text-xl">{{ number_format($d['sidaya']['sekolah_lansia']['capaian'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right flex-1 sm:flex-none flex justify-end items-end gap-2">
                                                <span class="block text-yellow-400 font-black text-4xl sm:text-5xl 2xl:text-6xl drop-shadow-[0_4px_4px_rgba(0,0,0,0.8)] leading-none">{{ number_format((float)($d['sidaya']['sekolah_lansia']['persentase'] ?? 0), 0, ',', '.') }}</span>
                                                <span class="block text-yellow-400 text-lg sm:text-xl font-black mb-1">
                                                    @php
                                                        $pctStr3 = number_format((float)($d['sidaya']['sekolah_lansia']['persentase'] ?? 0), 2, ',', '.');
                                                        $dec3 = substr($pctStr3, -3);
                                                        if($dec3 == ',00') echo '%'; else echo $dec3.'%';
                                                    @endphp
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- 2. GATI (Gerakan Ayah Teladan) -->
                        <div class="relative bg-gradient-to-bl from-teal-900 via-[#022c22] to-[#011a14] rounded-[30px] border-4 border-yellow-400 shadow-[-4px_4px_0px_#fde047] p-4 sm:p-5 overflow-hidden">
                            
                            <!-- Title Ribbon Right -->
                            <div class="absolute top-6 -right-2 bg-yellow-400 text-teal-900 font-black px-4 sm:px-6 py-1.5 text-sm sm:text-base uppercase shadow-xl border-y-4 border-l-4 border-teal-900 z-20">
                                2. GATI (Gerakan Ayah Teladan)
                                <div class="absolute -bottom-3 right-0 w-3 h-3 bg-yellow-600" style="clip-path: polygon(0 0, 100% 0, 0 100%);"></div>
                            </div>
                            
                            <div class="flex flex-col lg:flex-row-reverse items-center gap-4 xl:gap-4 pt-10 lg:pt-12">
                                <!-- Image Area (Right) -->
                                <div class="w-full md:w-2/5 flex justify-center relative group">
                                    <img src="{{ asset('public/image/qw_gati.png') }}" class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 xl:w-40 xl:h-40 2xl:w-48 2xl:h-48 object-contain relative z-10 transform hover:-scale-x-105 hover:rotate-3 transition-transform duration-500 drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)] rounded-2xl shadow-[0_10px_20px_rgba(0,0,0,0.5)] border-2 border-teal-800/50" alt="GATI">
                                </div>
                                
                                <!-- Stats Area (Left) -->
                                <div class="w-full md:w-3/5 flex flex-col gap-3 relative z-10">
                                    <h3 class="text-yellow-300 font-black text-lg sm:text-xl uppercase tracking-widest border-b-2 border-teal-700/50 pb-2 mb-2">Fasilitasi Edukasi GATI</h3>
                                    
                                    <div class="grid grid-cols-3 gap-2 items-center mb-2">
                                        <div>
                                            <span class="block text-teal-400 text-[10px] font-bold uppercase tracking-wider mb-1">Target</span>
                                            <span class="block text-white font-black text-lg sm:text-xl">{{ number_format($d['gati']['edukasi']['target'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="relative">
                                            <span class="relative block text-yellow-400 text-[10px] font-bold uppercase tracking-wider mb-1">Capaian</span>
                                            <span class="relative block text-yellow-300 font-black text-lg sm:text-xl drop-shadow-md">{{ number_format($d['gati']['edukasi']['total'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-right flex justify-end items-end gap-1">
                                            <span class="text-yellow-400 font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ number_format((float)($d['gati']['edukasi']['persentase'] ?? 0), 0, ',', '.') }}</span>
                                            <span class="text-yellow-400 text-base font-black">
                                                @php
                                                    $pctStrG = number_format((float)($d['gati']['edukasi']['persentase'] ?? 0), 2, ',', '.');
                                                    $decG = substr($pctStrG, -3);
                                                    if($decG == ',00') echo '%'; else echo $decG.'%';
                                                @endphp
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Breakdown with large pills -->
                                    <div class="bg-teal-950/80 p-3 sm:p-4 rounded-3xl border border-teal-800 backdrop-blur-sm">
                                        <span class="block text-teal-300 text-xs font-bold uppercase tracking-widest text-center mb-2">Rincian Capaian per Program</span>
                                        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                                            <div class="bg-gradient-to-br from-teal-800 to-teal-900 border-2 border-teal-500 rounded-2xl px-3 py-2 flex-1 min-w-[65px] text-center transform hover:-translate-y-1 transition-transform shadow-lg">
                                                <span class="block text-teal-300 text-[9px] sm:text-[10px] font-bold uppercase mb-1">Kompak Tenan</span>
                                                <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['gati']['edukasi']['kompak_tenan'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="bg-gradient-to-br from-teal-800 to-teal-900 border-2 border-cyan-500 rounded-2xl px-3 py-2 flex-1 min-w-[65px] text-center transform hover:-translate-y-1 transition-transform shadow-lg">
                                                <span class="block text-cyan-300 text-[9px] sm:text-[10px] font-bold uppercase mb-1">Dekat</span>
                                                <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['gati']['edukasi']['dekat'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="bg-gradient-to-br from-teal-800 to-teal-900 border-2 border-emerald-500 rounded-2xl px-3 py-2 flex-1 min-w-[65px] text-center transform hover:-translate-y-1 transition-transform shadow-lg">
                                                <span class="block text-emerald-300 text-[9px] sm:text-[10px] font-bold uppercase mb-1">Sebaya</span>
                                                <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['gati']['edukasi']['sebaya'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. TAMASYA -->
                        <div class="relative bg-gradient-to-br from-[#022c22] via-teal-900 to-emerald-950 rounded-[30px] border-4 border-yellow-400 shadow-[4px_4px_0px_#fde047] p-4 sm:p-5 overflow-hidden">
                            <div class="absolute top-6 -left-2 bg-yellow-400 text-teal-900 font-black px-4 sm:px-6 py-1.5 text-sm sm:text-base uppercase shadow-xl border-y-4 border-r-4 border-teal-900 z-20">
                                3. TAMASYA (Taman Asuh Sayang Anak)
                                <div class="absolute -bottom-3 left-0 w-3 h-3 bg-yellow-600" style="clip-path: polygon(0 0, 100% 0, 100% 100%);"></div>
                            </div>

                            <div class="flex flex-col lg:flex-row items-center gap-4 xl:gap-4 pt-10 lg:pt-12">
                                <div class="w-full md:w-2/5 flex justify-center relative group">
                                    <img src="{{ asset('public/image/qw_tamasya.png') }}" class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 xl:w-40 xl:h-40 2xl:w-48 2xl:h-48 object-contain relative z-10 transform hover:-translate-y-2 hover:-rotate-2 transition-transform duration-500 drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)] rounded-2xl shadow-[0_10px_20px_rgba(0,0,0,0.5)] border-2 border-teal-800/50" alt="TAMASYA">
                                </div>
                                
                                <div class="w-full md:w-3/5 flex flex-col gap-3 relative z-10">
                                    
                                    <!-- A: Jumlah TPA -->
                                    <div class="bg-gradient-to-r from-yellow-400 to-amber-500 text-teal-900 p-4 sm:p-5 rounded-3xl shadow-2xl  relative overflow-hidden border-2 border-white">
                                        <div class="absolute -right-4 -top-2 text-yellow-600/30 text-[90px] font-black leading-none pointer-events-none">{{ $d['tamasya']['jumlah_tpa'] ?? 0 }}</div>
                                        <h3 class="font-black text-base sm:text-lg uppercase tracking-widest relative z-10">Jumlah TPA yang Ada</h3>
                                        <span class="block text-4xl sm:text-5xl 2xl:text-6xl font-black relative z-10 drop-shadow-md">{{ number_format($d['tamasya']['jumlah_tpa'] ?? 0, 0, ',', '.') }}</span>
                                    </div>

                                    <!-- B, C, D in a grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                        <!-- B: 4 Layanan -->
                                        <div class="border-2 border-blue-500/50 rounded-3xl p-5 bg-gradient-to-br from-teal-950/80 to-[#022c22]/80 backdrop-blur-sm relative overflow-hidden group hover:border-blue-400 transition-colors">
                                            <h4 class="text-blue-300 font-black text-sm sm:text-base uppercase tracking-widest mb-2">4 Layanan Utama</h4>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-teal-200 text-xs sm:text-sm font-bold uppercase">Memenuhi</span>
                                                <span class="text-blue-400 font-black text-lg sm:text-xl">{{ number_format($d['tamasya']['memenuhi_4_layanan']['memenuhi'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-teal-200 text-xs sm:text-sm font-bold uppercase">Tidak Memenuhi</span>
                                                <span class="text-rose-400 font-black text-lg sm:text-xl">{{ number_format($d['tamasya']['memenuhi_4_layanan']['tidak_memenuhi'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- C: Pelaporan -->
                                        <div class="border-2 border-purple-500/50 rounded-3xl p-5 bg-gradient-to-br from-teal-950/80 to-[#022c22]/80 backdrop-blur-sm relative overflow-hidden group hover:border-purple-400 transition-colors">
                                            <h4 class="text-purple-300 font-black text-sm sm:text-base uppercase tracking-widest mb-2">Status Pelaporan</h4>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-teal-200 text-xs sm:text-sm font-bold uppercase">Dilaporkan</span>
                                                <span class="text-purple-400 font-black text-lg sm:text-xl">{{ number_format($d['tamasya']['status_pelaporan']['dilaporkan'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-teal-200 text-xs sm:text-sm font-bold uppercase">Belum Lapor</span>
                                                <span class="text-rose-400 font-black text-lg sm:text-xl">{{ number_format($d['tamasya']['status_pelaporan']['tidak_dilaporkan'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- D: Pemutakhiran -->
                                        <div class="border-2 border-emerald-500/50 rounded-3xl p-5 bg-gradient-to-br from-teal-950/80 to-[#022c22]/80 backdrop-blur-sm sm:col-span-2 flex flex-col sm:flex-row justify-between items-center gap-4 group hover:border-emerald-400 transition-colors">
                                            <div class="text-center sm:text-left">
                                                <h4 class="text-emerald-300 font-black text-sm sm:text-base uppercase tracking-widest">Pemutakhiran Data</h4>
                                                <span class="text-teal-400 text-xs font-semibold">Progres validasi & verifikasi</span>
                                            </div>
                                            <div class="flex gap-2 sm:gap-3">
                                                <div class="text-center">
                                                    <span class="block text-teal-200 text-xs font-bold uppercase tracking-wider mb-1">Sudah</span>
                                                    <span class="text-white font-black text-xl sm:text-2xl">{{ number_format($d['tamasya']['pemutakhiran_data']['sudah'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="text-center">
                                                    <span class="block text-teal-200 text-xs font-bold uppercase tracking-wider mb-1">Belum</span>
                                                    <span class="text-rose-400 font-black text-xl sm:text-2xl">{{ number_format($d['tamasya']['pemutakhiran_data']['belum'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- 4. GENTING -->
                        <div class="relative bg-gradient-to-bl from-[#022c22] via-teal-900 to-[#011a14] rounded-[30px] border-4 border-yellow-400 shadow-[-4px_4px_0px_#fde047] p-4 sm:p-5 overflow-hidden">
                            <div class="absolute top-6 -right-2 bg-yellow-400 text-teal-900 font-black px-4 sm:px-6 py-1.5 text-sm sm:text-base uppercase shadow-xl border-y-4 border-l-4 border-teal-900 z-20">
                                4. GENTING (Cegah Stunting)
                                <div class="absolute -bottom-3 right-0 w-3 h-3 bg-yellow-600" style="clip-path: polygon(0 0, 100% 0, 0 100%);"></div>
                            </div>
                            
                            <div class="flex flex-col lg:flex-row-reverse items-center gap-4 xl:gap-4 pt-10 lg:pt-12">
                                <div class="w-full md:w-2/5 flex justify-center relative group">
                                    <img src="{{ asset('public/image/qw_genting.png') }}" class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 xl:w-40 xl:h-40 2xl:w-48 2xl:h-48 object-contain relative z-10 transform hover:scale-105 hover:-rotate-3 transition-transform duration-500 drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)] rounded-2xl shadow-[0_10px_20px_rgba(0,0,0,0.5)] border-2 border-teal-800/50" alt="GENTING">
                                </div>
                                
                                <div class="w-full md:w-3/5 flex flex-col gap-3 relative z-10">
                                    
                                    <div>
                                        <h3 class="text-yellow-300 font-black text-lg sm:text-xl uppercase tracking-widest border-b-2 border-teal-700/50 pb-2 mb-2">Fasilitasi Program GENTING</h3>
                                        <div class="grid grid-cols-3 gap-2 items-center mb-2">
                                        <div>
                                            <span class="block text-teal-400 text-[10px] font-bold uppercase tracking-wider mb-1">Target</span>
                                            <span class="block text-white font-black text-lg sm:text-xl">{{ number_format($d['genting']['fasilitasi']['target'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="relative">
                                            <span class="relative block text-yellow-400 text-[10px] font-bold uppercase tracking-wider mb-1">Bantuan</span>
                                            <span class="relative block text-yellow-300 font-black text-lg sm:text-xl drop-shadow-md">{{ number_format($d['genting']['fasilitasi']['total'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-right flex justify-end items-end gap-1">
                                            <span class="text-yellow-400 font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ number_format((float)($d['genting']['fasilitasi']['persentase'] ?? 0), 0, ',', '.') }}</span>
                                            <span class="text-yellow-400 text-base font-black">
                                                @php
                                                    $pctStrGen = number_format((float)($d['genting']['fasilitasi']['persentase'] ?? 0), 2, ',', '.');
                                                    $decGen = substr($pctStrGen, -3);
                                                    if($decGen == ',00') echo '%'; else echo $decGen.'%';
                                                @endphp
                                            </span>
                                        </div>
                                    </div>

                                        <div class="mt-8 bg-teal-950/80 p-3 sm:p-4 rounded-3xl border border-teal-800 backdrop-blur-sm shadow-xl">
                                            <span class="block text-center text-teal-300 text-xs font-bold uppercase tracking-widest mb-2">Sebaran Rincian Bantuan Diberikan</span>
                                            <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                                                <div class="bg-[#022c22] border-2 border-emerald-500 rounded-2xl px-2 py-2 text-center flex-1 min-w-[60px] transform hover:-translate-y-1 transition-transform shadow-lg">
                                                    <span class="block text-emerald-400 text-[9px] sm:text-[10px] font-black uppercase tracking-wider mb-1">Nutrisi</span>
                                                    <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['genting']['fasilitasi']['nutrisi'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="bg-[#022c22] border-2 border-sky-500 rounded-2xl px-2 py-2 text-center flex-1 min-w-[60px] transform hover:-translate-y-1 transition-transform shadow-lg">
                                                    <span class="block text-sky-400 text-[9px] sm:text-[10px] font-black uppercase tracking-wider mb-1">Sanitasi</span>
                                                    <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['genting']['fasilitasi']['sanitasi'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="bg-[#022c22] border-2 border-blue-500 rounded-2xl px-2 py-2 text-center flex-1 min-w-[60px] transform hover:-translate-y-1 transition-transform shadow-lg">
                                                    <span class="block text-blue-400 text-[9px] sm:text-[10px] font-black uppercase tracking-wider mb-1">Air Bersih</span>
                                                    <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['genting']['fasilitasi']['air_bersih'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="bg-[#022c22] border-2 border-amber-500 rounded-2xl px-2 py-2 text-center flex-1 min-w-[60px] transform hover:-translate-y-1 transition-transform shadow-lg">
                                                    <span class="block text-amber-400 text-[9px] sm:text-[10px] font-black uppercase tracking-wider mb-1">Rmh Layak</span>
                                                    <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['genting']['fasilitasi']['rumah_layak'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="bg-[#022c22] border-2 border-purple-500 rounded-2xl px-2 py-2 text-center flex-1 min-w-[60px] transform hover:-translate-y-1 transition-transform shadow-lg">
                                                    <span class="block text-purple-400 text-[9px] sm:text-[10px] font-black uppercase tracking-wider mb-1">Edukasi</span>
                                                    <span class="block text-white font-black text-base sm:text-lg">{{ number_format($d['genting']['fasilitasi']['edukasi'] ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                target.style.width = '1400px';
                target.style.margin = '0';
                target.style.borderRadius = '0';
                target.style.backgroundColor = '#F4F7FB';
                target.style.paddingBottom = '80px';
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
                        width: target.scrollWidth,
                        height: target.scrollHeight,
                        onclone: function(clonedDoc) {
                            clonedDoc.body.classList.add('exporting');

                            // Fix drop-shadows
                            const drops = clonedDoc.querySelectorAll('[class*="drop-shadow"]');
                            drops.forEach(d => { d.style.filter = 'none'; });

                            // Fix gold-card glitch
                            const goldCards = clonedDoc.querySelectorAll('.gold-card');
                            goldCards.forEach(card => { card.style.boxShadow = 'none'; });
                            
                            // Hide blur effects
                            const blurs = clonedDoc.querySelectorAll('[class*="blur-"]');
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

    <!-- Elsimil Chart Setup -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.elsimilData) {
                const pointLabelsPlugin = {
                    id: 'pointLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        ctx.font = 'bold 13px Poppins';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        
                        chart.data.datasets.forEach((dataset, i) => {
                            const meta = chart.getDatasetMeta(i);
                            meta.data.forEach((point, index) => {
                                const value = dataset.data[index];
                                const percentage = dataset.percentages ? dataset.percentages[index] : '';

                                ctx.fillStyle = '#fde047';
                                ctx.fillText(value.toLocaleString('id-ID'), point.x, point.y - 12);

                                if (percentage) {
                                    if (percentage.includes('-')) {
                                        ctx.fillStyle = '#fca5a5';
                                        ctx.fillText('▼ ' + percentage.replace('-', ''), point.x, point.y - 28);
                                    } else {
                                        ctx.fillStyle = '#6ee7b7';
                                        ctx.fillText('▲ ' + percentage.replace('+', ''), point.x, point.y - 28);
                                    }
                                }
                            });
                        });
                    }
                };

                Chart.register(pointLabelsPlugin);

                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 80, right: 45, left: 35, bottom: 10 } },
                    plugins: { legend: { display: false }, tooltip: { enabled: false }, pointLabels: true },
                    scales: {
                        x: { grid: { display: false, drawBorder: true, color: '#ffffff' }, ticks: { color: '#ffffff', font: { family: 'Poppins', size: 13, weight: 'bold' } } },
                        y: { display: false, min: 0 }
                    },
                    elements: {
                        line: { tension: 0.4 },
                        point: { radius: 5, backgroundColor: '#ffffff', borderWidth: 2, borderColor: '#0284c7', hoverRadius: 7 }
                    }
                };

                const initElsimilChart = (canvasId, dataObj, color, gradientStart, gradientEnd) => {
                    const canvas = document.getElementById(canvasId);
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    
                    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, gradientStart);
                    gradient.addColorStop(1, gradientEnd);

                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    const labels = Object.keys(dataObj).map(m => monthNames[parseInt(m) - 1]);
                    const dataPoints = Object.values(dataObj);
                    
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

                    // adjust max y to prevent top text clipping
                    const maxVal = Math.max(...dataPoints.map(Number));
                    const options = JSON.parse(JSON.stringify(commonOptions));
                    options.scales.y.max = maxVal + (maxVal * 0.5); // Add 50% headroom
                    options.layout.padding.top = 85; 
                    options.clip = false;
                    options.elements.point.borderColor = color;

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: dataPoints,
                                percentages: percentages,
                                borderColor: color,
                                backgroundColor: gradient,
                                borderWidth: 3,
                                fill: true,
                            }]
                        },
                        options: options
                    });
                };

                if (window.elsimilData.catin) {
                    initElsimilChart('catinChart', window.elsimilData.catin, '#38bdf8', 'rgba(56, 189, 248, 0.5)', 'rgba(6, 78, 59, 0)');
                }
                if (window.elsimilData.bumil) {
                    initElsimilChart('bumilChart', window.elsimilData.bumil, '#f472b6', 'rgba(244, 114, 182, 0.5)', 'rgba(6, 78, 59, 0)');
                }
            }
        });
    </script>
</x-layout>