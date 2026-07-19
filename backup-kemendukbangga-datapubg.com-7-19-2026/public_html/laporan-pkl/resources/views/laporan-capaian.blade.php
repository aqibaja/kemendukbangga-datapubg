<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .bg-main { 
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
    @endphp

    <div class="bg-main flex flex-col relative z-0 mt-4 sm:mx-4 rounded-3xl overflow-hidden shadow-2xl" id="posterContent">
        <!-- Flex container for perfectly centered watermark that html2canvas understands -->
        <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-0 overflow-hidden">
            <img src="{{ $logoSrc }}" alt="Watermark BKKBN" style="width: 80vw; max-width: 800px; opacity: 0.05;">
        </div>
        <div class="flex-grow flex flex-col items-center p-2 sm:p-4">
            <div class="max-w-7xl w-full text-white relative z-10">
                
                <!-- Header -->
                <div class="flex flex-col items-center mb-8 sm:mb-12 mt-4 relative">
                    <div class="flex flex-col sm:flex-row items-center gap-3 mb-4 text-center sm:text-left">
                        <img src="{{ $logoSrc }}" alt="Logo BKKBN" class="w-14 h-14 sm:w-16 sm:h-16 object-contain  p-1">
                        <div class="leading-tight">
                            <div class="text-[10px] sm:text-xs font-semibold">Kementerian Kependudukan dan</div>
                            <div class="text-[10px] sm:text-xs font-semibold">Pembangunan Keluarga/BKKBN</div>
                            <div class="text-[10px] sm:text-xs font-bold text-yellow-300">Perwakilan BKKBN Provinsi Aceh</div>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold drop-shadow-lg text-center leading-tight px-2">
                        @if(request('tipe', 'pengendalian_lapangan') == 'pengendalian_lapangan')
                            LAPORAN CAPAIAN PROGRAM PENGENDALIAN LAPANGAN<br>
                        @elseif(request('tipe') == 'capaian_program')
                            LAPORAN CAPAIAN PROGRAM<br>
                        @elseif(request('tipe') == 'elsimil')
                            LAPORAN CAPAIAN ELSIMIL<br>
                        @endif
                        KEMENDUKBANGGA (BKKBN) PROV ACEH<br>
                        <span class="text-yellow-300 drop-shadow-md uppercase">{{ \App\Models\LaporanCapaian::namaBulan($bulan) }} TAHUN {{ $tahun }}</span>
                    </div>

                    <!-- Form Filter -->
                    <form method="GET" action="/laporan-capaian" class="print:hidden mt-6 bg-teal-900/50 backdrop-blur-md p-3 rounded-2xl flex flex-wrap justify-center items-center gap-2 border border-teal-500 shadow-lg">
                        <select name="tipe" class="bg-white/10 text-white border border-white/30 rounded-lg px-3 py-1.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <option value="pengendalian_lapangan" {{ request('tipe', 'pengendalian_lapangan') == 'pengendalian_lapangan' ? 'selected' : '' }} class="bg-teal-800 text-white">Pengendalian Lapangan</option>
                            <option value="capaian_program" {{ request('tipe') == 'capaian_program' ? 'selected' : '' }} class="bg-teal-800 text-white">Capaian Program</option>
                            <option value="elsimil" {{ request('tipe') == 'elsimil' ? 'selected' : '' }} class="bg-teal-800 text-white">Capaian Elsimil</option>
                        </select>
                        <select name="bulan" class="bg-white/10 text-white border border-white/30 rounded-lg px-3 py-1.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            @for($m=1;$m<=12;$m++)
                                <option value="{{$m}}" {{$bulan==$m?'selected':''}} class="bg-teal-800 text-white">{{App\Models\LaporanCapaian::namaBulan($m)}}</option>
                            @endfor
                        </select>
                        <select name="tahun" class="bg-white/10 text-white border border-white/30 rounded-lg px-3 py-1.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            @for($y=2024;$y<=now()->year+1;$y++)
                                <option value="{{$y}}" {{$tahun==$y?'selected':''}} class="bg-teal-800 text-white">{{$y}}</option>
                            @endfor
                        </select>
                        <button type="submit" class="bg-yellow-400 text-teal-900 border-none rounded-lg px-4 py-1.5 text-sm font-bold hover:bg-yellow-300 transition-colors shadow-md">Tampilkan</button>
                    </form>
                </div>

                @if(request('tipe', 'pengendalian_lapangan') == 'pengendalian_lapangan')
                    @if(isset($laporans['pengendalian_lapangan']))
                        @php $d = $laporans['pengendalian_lapangan']->data; @endphp

                    <!-- BKB -->
                    <div class="mt-6">
                        <div class="flex justify-center mb-6 mt-4">
                            <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/bkb_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="BKB">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">BINA KELUARGA BALITA (BKB)</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 px-2">
                            <!-- Cakupan Laporan -->
                            @php $bkb1 = $d['bkb']['cakupan_laporan'] ?? []; @endphp
                            <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                <div class="text-gray-200">
                                    <div class="flex justify-between mb-1"><span>Ada</span> <span class="font-bold text-white">{{ number_format($bkb1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($bkb1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkb1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                            <!-- Persentase Anak Hadir -->
                            @php $bkb2 = $d['bkb']['anak_hadir_kka'] ?? []; @endphp
                            <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">b. Anak Hadir KKA</h3>
                                <div class="text-gray-200">
                                    <div class="flex justify-between mb-1"><span>Hadir</span> <span class="font-bold text-white">{{ number_format($bkb2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Guna KKA</span> <span class="font-bold text-white">{{ number_format($bkb2['menggunakan_kka'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkb2['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                            <!-- Keluarga Ikut BKB -->
                            @php $bkb3 = $d['bkb']['keluarga_ikut_bkb'] ?? []; @endphp
                            <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">c. Keluarga Ikut BKB</h3>
                                <div class="text-gray-200">
                                    <div class="flex justify-between mb-1"><span>Target</span> <span class="font-bold text-white">{{ number_format($bkb3['target'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Capaian</span> <span class="font-bold text-white">{{ number_format($bkb3['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkb3['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                            <!-- Pembinaan Baduta -->
                            @php $bkb4 = $d['bkb']['pembinaan_baduta'] ?? []; @endphp
                            <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">d. Pembinaan Baduta</h3>
                                <div class="text-gray-200">
                                    <div class="flex justify-between mb-1"><span>Target</span> <span class="font-bold text-white">{{ number_format($bkb4['target'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Capaian</span> <span class="font-bold text-white">{{ number_format($bkb4['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkb4['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BKR & BKL ROW -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                        <!-- BKR -->
                        <div>
                            <div class="flex justify-center mb-6 mt-4">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/bkr_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="BKR">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">BINA KELUARGA REMAJA (BKR)</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-2">
                                @php $bkr1 = $d['bkr']['cakupan_laporan'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Ada</span> <span class="font-bold text-white">{{ number_format($bkr1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($bkr1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkr1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                @php $bkr2 = $d['bkr']['anggota_hadir'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">b. Anggota Hadir</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Jumlah</span> <span class="font-bold text-white">{{ number_format($bkr2['jumlah'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Hadir</span> <span class="font-bold text-white">{{ number_format($bkr2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkr2['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BKL -->
                        <div>
                            <div class="flex justify-center mb-6 mt-4">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/bkl_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="BKL">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">BINA KELUARGA LANSIA (BKL)</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-2">
                                @php $bkl1 = $d['bkl']['cakupan_laporan'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Ada</span> <span class="font-bold text-white">{{ number_format($bkl1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($bkl1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkl1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                @php $bkl2 = $d['bkl']['anggota_hadir'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">b. Anggota Hadir</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Jml Kel.</span> <span class="font-bold text-white">{{ number_format($bkl2['jumlah_keluarga'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Tot Hadir</span> <span class="font-bold text-white">{{ number_format($bkl2['total_hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($bkl2['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PIK-R & UPPKA ROW -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                        <!-- PIK-R -->
                        <div>
                            <div class="flex justify-center mb-6 mt-4">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/pikr_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="PIK-R">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">PIK REMAJA (PIK-R)</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-2">
                                @php $pikr1 = $d['pikr']['cakupan_laporan'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Ada</span> <span class="font-bold text-white">{{ number_format($pikr1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($pikr1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($pikr1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                @php $pikr2 = $d['pikr']['anggota_hadir'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">b. Anggota Hadir</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Target</span> <span class="font-bold text-white">{{ number_format($pikr2['target'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Hadir</span> <span class="font-bold text-white">{{ number_format($pikr2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($pikr2['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPPKA -->
                        <div>
                            <div class="flex justify-center mb-6 mt-4">
                                <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/uppka_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="UPPKA">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">UPPKA</span>
                                </div>
                            </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-2">
                                @php $uppka1 = $d['uppka']['cakupan_laporan'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Ada</span> <span class="font-bold text-white">{{ number_format($uppka1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($uppka1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($uppka1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                @php $uppka2 = $d['uppka']['anggota_hadir'] ?? []; @endphp
                                <div class="dark-green-card rounded-xl p-4 text-sm relative">
                                    <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">b. Anggota Hadir</h3>
                                    <div class="text-gray-200">
                                        <div class="flex justify-between mb-1"><span>Jml Kel.</span> <span class="font-bold text-white">{{ number_format($uppka2['jumlah_keluarga'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-1"><span>Hadir</span> <span class="font-bold text-white">{{ number_format($uppka2['hadir'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-lg">{{ number_format($uppka2['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PPKS -->
                    <div class="mt-8 mb-6 relative">
                        <div class="flex justify-center mb-6 mt-4 relative z-10">
                            <div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    <img src="{{ asset('public/image/ppks_3d.png') }}?v={{ time() }}" class="w-full h-full object-cover" alt="PPKS">
                                </div>
                                <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 pl-10 sm:pl-14 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">PUSAT PELAYANAN KELUARGA SEJAHTERA (PPKS)</span>
                                </div>
                            </div>
                        </div>
                        <!-- Card and Images Container -->
                        <div class="flex justify-center items-center px-2 relative z-10 w-full max-w-5xl mx-auto mt-2 py-4">
                            <!-- Image 1 (Left) -->
                            <img src="{{ asset('public/image/rumah_aceh.png') }}" alt="Rumah Adat Aceh" class="absolute left-0 md:left-10 top-1/2 -translate-y-1/2 w-40 md:w-64 drop-shadow-[0_0_15px_rgba(255,215,0,0.3)] pointer-events-none hidden sm:block z-0 opacity-80">

                            @php $ppks1 = $d['ppks']['cakupan_laporan'] ?? []; @endphp
                            <div class="dark-green-card rounded-xl p-5 text-sm relative w-full sm:w-80 flex-shrink-0 z-10">
                                <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                <div class="text-gray-200">
                                    <div class="flex justify-between mb-2 text-base"><span>Ada</span> <span class="font-bold text-white">{{ number_format($ppks1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-2 text-base"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($ppks1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-4 pt-3 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-2xl">{{ number_format($ppks1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>

                            <!-- Image 2 (Right) -->
                            <img src="{{ asset('public/image/masjid_emas.png') }}" alt="Masjid Emas" class="absolute right-0 md:right-10 top-1/2 -translate-y-1/2 w-40 md:w-64 drop-shadow-[0_0_15px_rgba(255,215,0,0.3)] pointer-events-none hidden sm:block z-0 opacity-80">
                        </div>
                    </div>

                    @else
                        <div class="text-center py-32 text-yellow-300 font-bold text-2xl drop-shadow-md">Data Pengendalian Lapangan tidak tersedia untuk periode ini</div>
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
                                    <div class="flex items-center mb-3 border-b border-teal-700 pb-2">
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
                                    <h4 class="font-bold text-center text-yellow-300 text-[13px] mb-4 h-10 flex items-center justify-center">{{ $kb['title'] }}</h4>
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
                                    <h4 class="font-bold text-center text-white pb-3 mb-4 uppercase text-sm border-b border-teal-700">PESERTA KB AKTIF<br>MKJP</h4>
                                    <div class="text-gray-200 text-sm">
                                        <div class="flex justify-between mb-2"><span>PPM</span> <span>= {{ number_format($d['kb_aktif']['pa_mkjp']['ppm'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mb-2"><span>Capaian</span> <span>= {{ number_format($d['kb_aktif']['pa_mkjp']['capaian'] ?? 0, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between mt-3 pt-2 border-t border-teal-700 text-white font-bold"><span>Persentase</span> <span class="text-yellow-300">= {{ number_format($d['kb_aktif']['pa_mkjp']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                    </div>
                                </div>
                                
                                <!-- Detail PA -->
                                <div class="gold-card rounded-xl z-10 p-5 h-full shadow-2xl flex flex-col justify-center border-4 border-yellow-400">
                                    <h4 class="font-bold text-center text-teal-900 pb-2 mb-4 text-sm uppercase border-b border-teal-700/30">DETAIL PA (MODERN & TRADISIONAL)</h4>
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
                                    <h4 class="font-bold text-center text-white pb-3 mb-4 uppercase text-sm border-b border-teal-700">PESERTA KB AKTIF<br>NON MKJP</h4>
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

                        <!-- JSON DATA FOR CHART.JS -->
                        <script>
                            window.elsimilData = @json($d);
                        </script>
                    @endif
                @endif

            </div>
        </div>

        <!-- FOOTER STATIS RESPONSIF -->
        <div class="w-full flex flex-col items-center mt-8 relative z-20 px-2 sm:px-8 pt-8 pb-8">
            
            <!-- Kotak Layanan -->
            <div class="w-full max-w-4xl bg-teal-900/95 backdrop-blur-md border border-yellow-400 text-white rounded-2xl sm:rounded-full p-4 sm:px-8 sm:py-3 mb-6 relative z-30 shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-6 text-xs sm:text-sm">
                <div class="flex items-center gap-2">
                    <span class="bg-green-500 rounded-full w-6 h-6 flex items-center justify-center shadow-md">
                        <span class="shift-up-export inline-block" style="line-height:1;">📞</span>
                    </span>
                    <span class="text-center sm:text-left shift-up-export inline-block">Layanan Pengaduan <br class="hidden sm:block"><b class="text-yellow-300">085361209387</b></span>
                </div>
                <div class="w-full sm:w-px h-px sm:h-6 bg-teal-500"></div> <!-- Garis Pembatas -->
                <div class="flex items-center gap-2">
                    <span class="shift-up-export inline-block" style="line-height:1;">🌐</span> 
                    <span class="shift-up-export inline-block">aceh.kemendukbangga.go.id</span>
                </div>
                <div class="w-full sm:w-px h-px sm:h-6 bg-teal-500"></div> <!-- Garis Pembatas -->
                <div class="flex items-center gap-2">
                    <span class="shift-up-export inline-block" style="line-height:1;">📱</span> 
                    <span class="shift-up-export inline-block">kemendukbangga_bkkbnaceh</span>
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
            
            // Allow some time for layout to settle
            await new Promise(r => setTimeout(r, 200));

            try {
                // Konfigurasi html2canvas dengan onclone agar live DOM tidak terpengaruh
                const canvas = await html2canvas(target, { 
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: null,
                    logging: false,
                    windowWidth: 1200, // Force a consistent width for export
                    onclone: function(clonedDoc) {
                        // 1. Perbaiki text pill (tombol kuning)
                        const texts = clonedDoc.querySelectorAll('.pill-text');
                        texts.forEach(t => t.style.top = '-8px');

                        // 2. Perbaiki teks mCPR dan persentase yang turun
                        const shiftElements = clonedDoc.querySelectorAll('.shift-up-export');
                        shiftElements.forEach(t => {
                            t.style.position = 'relative';
                            t.style.top = '-6px'; // Naikkan
                        });

                        // 3. Fix Chart.js overflow due to fixed windowWidth cloning
                        const charts = clonedDoc.querySelectorAll('canvas');
                        charts.forEach(c => {
                            c.style.width = '100%';
                            c.style.height = '100%';
                        });
                        
                        // 3. Hilangkan margin dan border radius agar export rapi
                        const poster = clonedDoc.getElementById('posterContent');
                        if (poster) {
                            poster.classList.remove('min-h-screen', 'sm:mx-4', 'mt-4');
                            poster.style.width = '1200px';
                            poster.style.margin = '0';
                            poster.style.borderRadius = '0';
                        }

                        // 4. Perbaiki glitch garis ganda pada gold-card akibat bug box-shadow: inset di html2canvas
                        const goldCards = clonedDoc.querySelectorAll('.gold-card');
                        goldCards.forEach(card => {
                            card.style.boxShadow = 'none'; // Matikan shadow yang bikin glitch
                        });

                        // 5. Perbaiki glitch sudut terpotong pada footer akibat bug backdrop-blur di html2canvas
                        const footerBox = clonedDoc.querySelector('.max-w-4xl.bg-teal-900\\/95');
                        if (footerBox) {
                            footerBox.classList.remove('backdrop-blur-md');
                            footerBox.style.boxShadow = 'none';
                        }
                    }
                });
                
                if (type === 'png') {
                    const link = document.createElement('a');
                    link.download = 'Laporan_Capaian_BKKBN_{{$bulan}}_{{$tahun}}.png';
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
                    pdf.save('Laporan_Capaian_BKKBN_{{$bulan}}_{{$tahun}}.pdf');
                }

            } catch (err) {
                console.error('Error setting up export', err);
            } finally {
                btnContainer.style.display = 'flex';
                zoomContainer.style.display = 'flex';
                if(formFilters) formFilters.style.display = 'flex';
                zoom = origZoom; updateZoom();
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