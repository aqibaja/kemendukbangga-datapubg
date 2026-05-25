<?php
$file = '/Users/mhusnulaqib/Developer/kemendukbangga-datapubg/laporan-pkl/resources/views/laporan-capaian.blade.php';

// I will recreate the file with standard tailwind, no line-height hacks
$content = <<<'HTML'
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .bg-main { 
            background: linear-gradient(135deg, #1fa2a8 0%, #0d5f5a 50%, #d48e15 100%); 
            background-attachment: fixed;
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
        .bg-watermark {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 80vw; max-width: 800px; opacity: 0.05; z-index: -1; pointer-events: none;
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

    <div class="bg-main min-h-screen flex flex-col relative z-0 mt-4 sm:mx-4 rounded-3xl overflow-hidden shadow-2xl" id="posterContent">
        <img src="{{ $logoSrc }}" alt="Watermark BKKBN" class="bg-watermark">
        <div class="flex-grow flex flex-col items-center p-2 sm:p-4">
            <div class="max-w-6xl w-full text-white relative z-10">
                
                <!-- Header -->
                <div class="flex flex-col items-center mb-8 sm:mb-12 mt-4 relative">
                    <div class="flex flex-col sm:flex-row items-center gap-3 mb-4 text-center sm:text-left">
                        <img src="{{ $logoSrc }}" alt="Logo BKKBN" class="w-14 h-14 sm:w-16 sm:h-16 object-contain rounded-full bg-white p-1 shadow-lg">
                        <div class="leading-tight">
                            <div class="text-[10px] sm:text-xs font-semibold">Kementerian Kependudukan dan</div>
                            <div class="text-[10px] sm:text-xs font-semibold">Pembangunan Keluarga/BKKBN</div>
                            <div class="text-[10px] sm:text-xs font-bold text-yellow-300">Perwakilan BKKBN Provinsi Aceh</div>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold drop-shadow-lg text-center leading-tight px-2">
                        LAPORAN CAPAIAN PROGRAM PENGENDALIAN LAPANGAN<br>
                        KEMENDUKBANGGA (BKKBN) PROV ACEH<br>
                        <span class="text-yellow-300 drop-shadow-md uppercase">{{ \App\Models\LaporanCapaian::namaBulan($bulan) }} TAHUN {{ $tahun }}</span>
                    </div>

                    <!-- Form Filter -->
                    <form method="GET" action="/laporan-capaian" class="print:hidden mt-6 bg-teal-900/50 backdrop-blur-md p-3 rounded-2xl flex flex-wrap justify-center items-center gap-2 border border-teal-500 shadow-lg">
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

                @if(isset($laporans['pengendalian_lapangan']))
                    @php $d = $laporans['pengendalian_lapangan']->data; @endphp

                    <!-- BKB -->
                    <div class="mt-8">
                        <div class="flex justify-center mb-6">
                            <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                BINA KELUARGA BALITA (BKB)
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                        <!-- BKR -->
                        <div>
                            <div class="flex justify-center mb-6">
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                    BINA KELUARGA REMAJA (BKR)
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
                            <div class="flex justify-center mb-6">
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                    BINA KELUARGA LANSIA (BKL)
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                        <!-- PIK-R -->
                        <div>
                            <div class="flex justify-center mb-6">
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                    PIK REMAJA (PIK-R)
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
                            <div class="flex justify-center mb-6">
                                <div class="text-center font-bold text-sm sm:text-lg text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                    UPPKA
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
                    <div class="mt-12 mb-16">
                        <div class="flex justify-center mb-6">
                            <div class="text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400 px-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
                                PUSAT PELAYANAN KELUARGA SEJAHTERA (PPKS)
                            </div>
                        </div>
                        <div class="flex justify-center px-2">
                            @php $ppks1 = $d['ppks']['cakupan_laporan'] ?? []; @endphp
                            <div class="dark-green-card rounded-xl p-5 text-sm relative w-full sm:w-80">
                                <h3 class="text-white font-bold mb-3 text-center border-b border-teal-700 pb-2">a. Cakupan Laporan</h3>
                                <div class="text-gray-200">
                                    <div class="flex justify-between mb-2 text-base"><span>Ada</span> <span class="font-bold text-white">{{ number_format($ppks1['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-2 text-base"><span>Lapor</span> <span class="font-bold text-white">{{ number_format($ppks1['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-4 pt-3 border-t border-teal-700 text-white"><span>Persentase</span> <span class="font-bold text-yellow-300 text-2xl">{{ number_format($ppks1['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="text-center py-32 text-yellow-300 font-bold text-2xl drop-shadow-md">Data tidak tersedia untuk periode ini</div>
                @endif
            </div>
        </div>

        <!-- FOOTER STATIS RESPONSIF -->
        <div class="w-full flex flex-col items-center mt-auto relative z-20 px-2 sm:px-8 bg-gradient-to-t from-teal-950 to-transparent pt-12">
            
            <!-- Kotak Layanan -->
            <div class="w-full max-w-4xl bg-teal-900/95 backdrop-blur-md border border-yellow-400 text-white rounded-2xl sm:rounded-full p-4 sm:px-8 sm:py-3 mb-2 sm:-mb-10 relative z-30 shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-6 text-xs sm:text-sm">
                <div class="flex items-center gap-2">
                    <span class="bg-green-500 rounded-full w-6 h-6 flex items-center justify-center shadow-md">📞</span>
                    <span class="text-center sm:text-left">Layanan Pengaduan <br class="hidden sm:block"><b class="text-yellow-300">085361209387</b></span>
                </div>
                <div class="w-full sm:w-px h-px sm:h-6 bg-teal-500"></div> <!-- Garis Pembatas -->
                <div class="flex items-center gap-2">
                    <span>🌐 aceh.kemendukbangga.go.id</span>
                </div>
                <div class="w-full sm:w-px h-px sm:h-6 bg-teal-500"></div> <!-- Garis Pembatas -->
                <div class="flex items-center gap-2">
                    <span>📱 kemendukbangga_bkkbnaceh</span>
                </div>
            </div>

            <!-- Ornamen Rumah Adat dan Masjid -->
            <div class="flex justify-between items-end w-full max-w-7xl relative z-10 overflow-hidden px-4">
                <div class="w-24 sm:w-48 lg:w-64 h-24 sm:h-48 lg:h-64 flex items-end">
                    <img src="https://via.placeholder.com/250x150/0a3a35/ffd700?text=Rumah+Adat" alt="Rumah Adat Aceh" class="w-full object-contain object-bottom drop-shadow-[0_0_15px_rgba(255,215,0,0.3)] rounded-tl-full rounded-tr-xl">
                </div>
                <div class="w-32 sm:w-56 lg:w-72 h-32 sm:h-56 lg:h-72 flex items-end">
                    <img src="https://via.placeholder.com/300x200/0a3a35/ffd700?text=Masjid+Aceh" alt="Masjid" class="w-full object-contain object-bottom drop-shadow-[0_0_15px_rgba(255,215,0,0.3)] rounded-t-full">
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
                const canvas = await html2canvas(target, { 
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: null,
                    logging: false
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
echo "File restored completely.\n";
