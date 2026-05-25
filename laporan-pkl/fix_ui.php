<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';

$content = <<<'HTML'
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .bg-flyer { 
            background: linear-gradient(to bottom, #1fa2a8 0%, #4cb8b5 30%, #e0aa3e 70%, #9e5b12 100%);
            font-family: 'Poppins', sans-serif;
        }
        .bg-watermark {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><path d="M50 10 Q90 10 90 50 Q90 90 50 90 Q10 90 10 50 Q10 10 50 10 Z" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="2"/></svg>');
            background-size: 150px;
            z-index: 0; pointer-events: none;
        }
        .bg-bkb { background-color: #1a4138; } /* Dark Green */
        .bg-bkr { background-color: #1a4138; } /* Dark Green */
        .bg-bkl { background-color: #5e2a6d; } /* Purple */
        .bg-pikr { background-color: #c15320; } /* Orange */
        .bg-uppka { background-color: #2c5b85; } /* Blue */
        .bg-ppks { background-color: #1a4138; } /* Dark Green */
        
        .pill-title {
            background-color: #2a3132;
            color: white;
            border: 2px solid #eab308; /* yellow-500 */
        }
        
        .card-border {
            border: 2px solid #eab308;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        .footer-ribbon {
            background-color: #1a4138;
            border-top: 2px solid #eab308;
        }
    </style>

    <!-- Zoom Controls -->
    <div id="zoomControls" class="fixed bottom-8 left-8 z-50 flex flex-col gap-2 print:hidden">
        <button onclick="zoomIn()" class="w-10 h-10 rounded-xl bg-white shadow-xl border-2 border-slate-300 flex items-center justify-center hover:bg-slate-50 transition-all duration-200 text-slate-700 font-bold text-lg"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
        <button onclick="zoomReset()" class="w-10 h-10 rounded-xl bg-white shadow-xl border-2 border-slate-300 flex items-center justify-center hover:bg-slate-50 transition-all duration-200 text-slate-700 font-bold text-[10px]" id="zoomLevel">100%</button>
        <button onclick="zoomOut()" class="w-10 h-10 rounded-xl bg-white shadow-xl border-2 border-slate-300 flex items-center justify-center hover:bg-slate-50 transition-all duration-200 text-slate-700 font-bold text-lg"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
    </div>

    <div id="shareBtns" class="fixed bottom-8 right-8 z-50 flex gap-4 print:hidden">
        <button onclick="d('png')" class="px-5 py-3 rounded-xl bg-white shadow-xl border-2 border-sky-500 flex items-center gap-2 hover:bg-sky-50 transition-all duration-200 text-sky-700 font-bold text-xs sm:text-sm"><i class="fa-solid fa-image text-base"></i> PNG</button>
        <button onclick="d('pdf')" class="px-5 py-3 rounded-xl bg-white shadow-xl border-2 border-red-500 flex items-center gap-2 hover:bg-red-50 transition-all duration-200 text-red-700 font-bold text-xs sm:text-sm"><i class="fa-solid fa-file-pdf text-base"></i> PDF</button>
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

        // SVG Circle Generator Helper
        if (!function_exists('svgRing')) {
            function svgRing($percent) {
                $p = min(max((float)$percent, 0), 100);
                return '
                <svg class="w-full h-full transform -rotate-90 drop-shadow-md" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="rgba(0,0,0,0.4)" stroke-width="4"></circle>
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#eab308" stroke-width="4" stroke-dasharray="'.$p.', 100"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-[9px] sm:text-[10px] font-bold text-white drop-shadow">'.number_format($percent, 2, ',', '.').'%</span>
                </div>';
            }
        }
    @endphp

    <div class="bg-flyer min-h-screen flex flex-col relative z-0 mt-4 sm:mx-4 rounded-3xl overflow-hidden shadow-2xl" id="posterContent">
        <div class="bg-watermark"></div>
        <div class="flex-grow flex flex-col items-center p-2 sm:p-4 z-10">
            <div class="max-w-4xl w-full text-white relative">
                
                <!-- Header -->
                <div class="flex flex-col items-center mb-8 mt-6">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $logoSrc }}" alt="Logo BKKBN" class="w-16 h-16 object-contain drop-shadow-lg">
                        <div class="leading-tight text-left">
                            <div class="text-xs font-semibold">Kementerian Kependudukan dan</div>
                            <div class="text-xs font-semibold">Pembangunan Keluarga/BKKBN</div>
                            <div class="text-xs font-bold text-yellow-300">Perwakilan BKKBN Provinsi Aceh</div>
                        </div>
                    </div>
                    <div class="text-2xl sm:text-3xl md:text-4xl font-extrabold drop-shadow-lg text-center leading-tight">
                        CAPAIAN PENGENDALIAN LAPANGAN<br>
                        KEMENDUKBANGGA BKKBN PROV ACEH<br>
                        <span class="text-yellow-300 drop-shadow-md uppercase">BULAN {{ \App\Models\LaporanCapaian::namaBulan($bulan) }} TAHUN {{ $tahun }}</span>
                    </div>

                    <!-- Form Filter -->
                    <form method="GET" action="/laporan-capaian" class="print:hidden mt-6 bg-black/20 backdrop-blur-sm p-3 rounded-2xl flex flex-wrap justify-center items-center gap-2 border border-white/30">
                        <select name="bulan" class="bg-white/10 text-white rounded px-3 py-1.5 text-sm font-bold">
                            @for($m=1;$m<=12;$m++)
                                <option value="{{$m}}" {{$bulan==$m?'selected':''}} class="text-black">{{App\Models\LaporanCapaian::namaBulan($m)}}</option>
                            @endfor
                        </select>
                        <select name="tahun" class="bg-white/10 text-white rounded px-3 py-1.5 text-sm font-bold">
                            @for($y=2024;$y<=now()->year+1;$y++)
                                <option value="{{$y}}" {{$tahun==$y?'selected':''}} class="text-black">{{$y}}</option>
                            @endfor
                        </select>
                        <button type="submit" class="bg-yellow-400 text-teal-900 rounded px-4 py-1.5 text-sm font-bold hover:bg-yellow-300">Tampilkan</button>
                    </form>
                </div>

                @if(isset($laporans['pengendalian_lapangan']))
                    @php $d = $laporans['pengendalian_lapangan']->data; @endphp

                    <!-- 1. BKB -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 pill-title px-6 py-1 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap z-10 shadow-lg">
                            1. BINA KELUARGA BALITA (BKB)
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5 pt-7 bg-bkb card-border">
                            <!-- Cakupan Laporan -->
                            @php $bkb1 = $d['bkb']['cakupan_laporan'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">a. CAKUPAN LAPORAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">👶🏻</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkb1['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Ada</span> <span>: {{ number_format($bkb1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Lapor</span> <span>: {{ number_format($bkb1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Persentase Anak Hadir -->
                            @php $bkb2 = $d['bkb']['anak_hadir_kka'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">b. PERSENTASE ANAK HADIR MENGGUNAKAN KKA</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">👩‍👦</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkb2['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Hadir</span> <span>: {{ number_format($bkb2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Guna KKA</span> <span>: {{ number_format($bkb2['menggunakan_kka'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Keluarga Ikut BKB -->
                            @php $bkb3 = $d['bkb']['keluarga_ikut_bkb'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">c. KELUARGA IKUT BKB</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">👨‍👩‍👧‍👦</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkb3['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Target</span> <span>: {{ number_format($bkb3['target'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Capaian</span> <span>: {{ number_format($bkb3['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pembinaan Baduta -->
                            @php $bkb4 = $d['bkb']['pembinaan_baduta'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">d. PEMBINAAN BADUTA 1000 HPK</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">🍼</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkb4['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Target</span> <span>: {{ number_format($bkb4['target'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Capaian</span> <span>: {{ number_format($bkb4['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. BKR -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 pill-title px-6 py-1 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap z-10 shadow-lg">
                            2. BINA KELUARGA REMAJA (BKR)
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5 pt-7 bg-bkr card-border">
                            @php $bkr1 = $d['bkr']['cakupan_laporan'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">a. CAKUPAN LAPORAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">🧑‍🤝‍🧑</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkr1['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Ada</span> <span>: {{ number_format($bkr1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Lapor</span> <span>: {{ number_format($bkr1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            @php $bkr2 = $d['bkr']['anggota_hadir'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">b. ANGGOTA BKR HADIR PERTEMUAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">👦🏽</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkr2['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Jumlah</span> <span>: {{ number_format($bkr2['jumlah'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Hadir</span> <span>: {{ number_format($bkr2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. BKL -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 pill-title px-6 py-1 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap z-10 shadow-lg">
                            3. BINA KELUARGA LANSIA (BKL)
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5 pt-7 bg-bkl card-border">
                            @php $bkl1 = $d['bkl']['cakupan_laporan'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">a. CAKUPAN LAPORAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg h-full">
                                    <div class="text-3xl drop-shadow">🧓🏼👴🏼</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkl1['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Ada</span> <span>: {{ number_format($bkl1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Lapor</span> <span>: {{ number_format($bkl1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            @php $bkl2 = $d['bkl']['anggota_hadir'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">b. ANGGOTA BKL HADIR PERTEMUAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg h-full">
                                    <div class="text-3xl drop-shadow">👵🏼</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($bkl2['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1 text-[10px] sm:text-xs"><span>Jml Kel.</span> <span>: {{ number_format($bkl2['jumlah_keluarga'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between text-[10px] sm:text-xs"><span>Tot Hadir</span> <span>: {{ number_format($bkl2['total_hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PIK-R -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 pill-title px-6 py-1 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap z-10 shadow-lg">
                            4. PUSAT INFORMASI & KONSELING REMAJA (PIK-R)
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5 pt-7 bg-pikr card-border">
                            @php $pikr1 = $d['pikr']['cakupan_laporan'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">a. CAKUPAN LAPORAN</h3>
                                <div class="flex items-center justify-center gap-2 bg-black/20 p-2 rounded-lg h-full">
                                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 shrink-0">{!! svgRing($pikr1['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-xs sm:text-sm flex-grow ml-2 font-semibold">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Ada</span> <span>: {{ number_format($pikr1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Lapor</span> <span>: {{ number_format($pikr1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            @php $pikr2 = $d['pikr']['anggota_hadir'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">b. ANGGOTA REMAJA HADIR PERTEMUAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg h-full">
                                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 shrink-0">
                                        <!-- Half gauge simulation using circle -->
                                        {!! svgRing($pikr2['persentase'] ?? 0) !!}
                                    </div>
                                    <div class="text-3xl drop-shadow ml-1">👩🏼‍🤝‍👨🏻</div>
                                    <div class="text-white text-[10px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Target</span> <span>: {{ number_format($pikr2['target'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Hadir</span> <span>: {{ number_format($pikr2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. UPPKA -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 pill-title px-6 py-1 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap z-10 shadow-lg">
                            5. UPPKA (USAHA PENINGKATAN PENDAPATAN)
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5 pt-7 bg-uppka card-border">
                            @php $uppka1 = $d['uppka']['cakupan_laporan'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">a. CAKUPAN LAPORAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">🪙</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($uppka1['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Ada</span> <span>: {{ number_format($uppka1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Lapor</span> <span>: {{ number_format($uppka1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                            @php $uppka2 = $d['uppka']['anggota_hadir'] ?? []; @endphp
                            <div>
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">b. ANGGOTA UPPKA HADIR PERTEMUAN</h3>
                                <div class="flex items-center gap-2 bg-black/20 p-2 rounded-lg">
                                    <div class="text-3xl drop-shadow">🤝</div>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 shrink-0">{!! svgRing($uppka2['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-[11px] sm:text-xs flex-grow ml-2">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Jml Kel.</span> <span>: {{ number_format($uppka2['jumlah_keluarga'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Hadir</span> <span>: {{ number_format($uppka2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. PPKS -->
                    <div class="relative mt-8 mb-6">
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 pill-title px-6 py-1 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap z-10 shadow-lg">
                            6. PUSAT PELAYANAN KELUARGA SEJAHTERA (PPKS)
                        </div>
                        <div class="flex justify-center">
                            @php $ppks1 = $d['ppks']['cakupan_laporan'] ?? []; @endphp
                            <div class="w-full sm:w-96 p-5 pt-7 bg-ppks card-border">
                                <h3 class="text-white font-bold text-[11px] sm:text-xs mb-2 text-center">a. CAKUPAN LAPORAN</h3>
                                <div class="flex items-center justify-center gap-3 bg-black/20 p-3 rounded-lg">
                                    <div class="text-4xl drop-shadow">👨‍👩‍👧‍👦</div>
                                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 shrink-0">{!! svgRing($ppks1['persentase'] ?? 0) !!}</div>
                                    <div class="text-white text-xs sm:text-sm flex-grow ml-2 font-semibold">
                                        <div class="flex justify-between border-b border-white/10 pb-1 mb-1"><span>Ada</span> <span>: {{ number_format($ppks1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Lapor</span> <span>: {{ number_format($ppks1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="text-center py-32 text-white font-bold text-2xl drop-shadow-md">Data tidak tersedia untuk periode ini</div>
                @endif
            </div>
        </div>

        <!-- FOOTER & ORNAMENTS -->
        <div class="w-full flex flex-col items-center mt-auto relative z-20 overflow-hidden">
            <!-- Ornamen Rumah & Masjid -->
            <div class="w-full max-w-5xl flex justify-between items-end px-2 sm:px-8 relative z-20">
                <div class="w-32 sm:w-48 lg:w-64 opacity-90 drop-shadow-[0_5px_15px_rgba(255,215,0,0.5)]">
                    <svg viewBox="0 0 100 100" class="w-full h-auto text-yellow-500 fill-current" xmlns="http://www.w3.org/2000/svg">
                        <!-- Simplified Rumah Adat SVG -->
                        <path d="M10 80 L90 80 L90 40 L50 10 L10 40 Z"/>
                        <path d="M20 80 L20 90 M80 80 L80 90 M30 80 L30 90 M70 80 L70 90" stroke="currentColor" stroke-width="4"/>
                        <polygon points="10,40 50,10 90,40 85,45 50,20 15,45" fill="#ca8a04"/>
                    </svg>
                </div>
                <div class="w-32 sm:w-48 lg:w-64 opacity-90 drop-shadow-[0_5px_15px_rgba(255,215,0,0.5)]">
                    <svg viewBox="0 0 100 100" class="w-full h-auto text-yellow-500 fill-current" xmlns="http://www.w3.org/2000/svg">
                        <!-- Simplified Masjid SVG -->
                        <path d="M20 90 L80 90 L80 50 L20 50 Z"/>
                        <path d="M35 50 Q50 20 65 50 Z"/>
                        <rect x="25" y="60" width="10" height="30" fill="#ca8a04"/>
                        <rect x="45" y="60" width="10" height="30" fill="#ca8a04"/>
                        <rect x="65" y="60" width="10" height="30" fill="#ca8a04"/>
                        <path d="M10 90 L10 30 Q15 20 20 30 L20 90 Z"/>
                        <path d="M80 90 L80 30 Q85 20 90 30 L90 90 Z"/>
                    </svg>
                </div>
            </div>

            <!-- Footer Ribbon -->
            <div class="w-full footer-ribbon text-white p-2 sm:p-3 relative z-30 shadow-[0_-5px_15px_rgba(0,0,0,0.4)]">
                <div class="max-w-4xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2 sm:gap-4 text-[10px] sm:text-xs">
                    <div class="flex items-center gap-2 font-bold">
                        <i class="fa-brands fa-whatsapp text-green-400 text-lg"></i>
                        <div class="leading-tight">
                            <span>No Layanan Pengaduan</span><br>
                            <span class="text-sm sm:text-base">085361209387</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-globe text-lg text-yellow-400"></i> aceh.kemendukbangga.go.id
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-brands fa-facebook text-lg text-blue-400"></i>
                        <i class="fa-brands fa-instagram text-lg text-pink-400"></i>
                        <i class="fa-brands fa-youtube text-lg text-red-500"></i>
                        kemendukbangga_bkkbnaceh
                    </div>
                </div>
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
        async function d(type) {
            const btnContainer = document.getElementById('shareBtns');
            const zoomContainer = document.getElementById('zoomControls');
            const target = document.getElementById('posterContent');
            const formFilters = target.querySelector('form');
            
            btnContainer.style.display = 'none';
            zoomContainer.style.display = 'none';
            if(formFilters) formFilters.style.display = 'none';
            
            const origZoom = zoom;
            zoom = 1; updateZoom();
            
            await new Promise(r => setTimeout(r, 200));

            try {
                const canvas = await html2canvas(target, { 
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: null,
                    logging: false,
                    windowWidth: 1000, 
                    onclone: function(clonedDoc) {
                        const poster = clonedDoc.getElementById('posterContent');
                        if (poster) {
                            poster.classList.remove('min-h-screen', 'sm:mx-4', 'mt-4');
                            poster.style.width = '1000px';
                            poster.style.margin = '0';
                            poster.style.borderRadius = '0'; 
                        }
                    }
                });
                
                if (type === 'png') {
                    const link = document.createElement('a');
                    link.download = 'Infografis_BKKBN_{{$bulan}}_{{$tahun}}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                } else if (type === 'pdf') {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new window.jspdf.jsPDF({
                        orientation: canvas.width > canvas.height ? 'landscape' : 'portrait',
                        unit: 'px',
                        format: [canvas.width, canvas.height]
                    });
                    pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
                    pdf.save('Infografis_BKKBN_{{$bulan}}_{{$tahun}}.pdf');
                }
            } catch (err) {
                console.error('Error generating ' + type, err);
                alert('Gagal menghasilkan file. Silakan coba lagi.');
            } finally {
                btnContainer.style.display = 'flex';
                zoomContainer.style.display = 'flex';
                if(formFilters) formFilters.style.display = 'flex';
                zoom = origZoom; updateZoom();
            }
        }
    </script>
</x-layout>
HTML;

file_put_contents($file, $content);
echo "UI updated to match flyer.\n";
