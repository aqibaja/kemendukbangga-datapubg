@php
    // Helper formatter
    $fmt = function($v) { 
        $v = floatval($v); 
        return (floor($v) == $v) ? number_format($v, 0, ',', '.') : number_format($v, 2, ',', '.'); 
    };

    // Aggregates Global
    $tot_ada = ($d['bkb']['cakupan_laporan']['ada']??0) + ($d['bkr']['cakupan_laporan']['ada']??0) + ($d['bkl']['cakupan_laporan']['ada']??0) + ($d['pikr']['cakupan_laporan']['ada']??0) + ($d['uppka']['cakupan_laporan']['ada']??0) + ($d['ppks']['cakupan_laporan']['ada']??0);
    $tot_lapor = ($d['bkb']['cakupan_laporan']['lapor']??0) + ($d['bkr']['cakupan_laporan']['lapor']??0) + ($d['bkl']['cakupan_laporan']['lapor']??0) + ($d['pikr']['cakupan_laporan']['lapor']??0) + ($d['uppka']['cakupan_laporan']['lapor']??0) + ($d['ppks']['cakupan_laporan']['lapor']??0);
    $cakupan_pct = $tot_ada > 0 ? ($tot_lapor / $tot_ada) * 100 : 0;

    $tot_hadir = ($d['bkb']['anak_hadir_kka']['hadir']??0) + ($d['bkr']['anggota_hadir']['hadir']??0) + ($d['bkl']['anggota_hadir']['total_hadir']??0) + ($d['pikr']['anggota_hadir']['hadir']??0) + ($d['uppka']['anggota_hadir']['hadir']??0);
    
    $status_overall = $cakupan_pct >= 100 ? 'Sudah Lengkap' : ($cakupan_pct >= 50 ? 'Perlu Verifikasi' : 'Belum Lapor');
@endphp

<!-- DASHBOARD CONTAINER (INFOGRAFIK BKKBN ACEH) -->
<div class="mb-10 text-[var(--text)] w-full max-w-[1440px] mx-auto px-4 sm:px-6 relative">
    
    <!-- WATERMARK BACKGROUND -->
    <div class="fixed inset-0 pointer-events-none z-[0] flex items-center justify-center overflow-hidden">
        <!-- Apply opacity directly to the image so html2canvas doesn't ignore it -->
        <img src="/public/image/logoBKKBN.png" class="w-[60%] max-w-[800px] object-contain opacity-[0.05]" alt="Watermark BKKBN">
    </div>

    <!-- 1. HERO SECTION -->
    <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] overflow-hidden mb-6 relative z-10">
        <div class="relative z-10 px-6 py-8 md:px-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/BKKBN_Logo_2020.svg" class="h-10" alt="BKKBN Logo">
                    <div class="text-xs font-bold text-[var(--muted)] uppercase tracking-wider">Perwakilan Provinsi Aceh</div>
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-[var(--canvas)] text-[var(--teal)] text-sm font-semibold rounded border border-[var(--line)] mb-4 export-fix-text">
                    <i class="fa-solid fa-calendar-alt"></i>
                    Periode: {{ App\Models\LaporanCapaian::namaBulan($laporans['pengendalian_lapangan']->bulan) }} {{ $laporans['pengendalian_lapangan']->tahun }}
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[var(--navy)] mb-1 uppercase tracking-tight export-fix-text">LAPORAN CAPAIAN PROGRAM</h1>
                <h2 class="text-xl md:text-2xl font-bold text-[var(--teal)] mb-3 export-fix-text">PENGENDALIAN LAPANGAN</h2>
                <div class="flex gap-3 text-xs text-[var(--muted)]">
                    <span class="bg-slate-100 px-2 py-1 rounded">Update data: {{ $laporans['pengendalian_lapangan']->updated_at->format('d M Y') }}</span>
                    <span class="bg-slate-100 px-2 py-1 rounded">Sumber: Perwakilan BKKBN Prov Aceh</span>
                </div>
            </div>
            <div class="hidden md:block shrink-0 px-4">
                <!-- Hilangkan drop-shadow agar tidak ada bayangan saat diexport -->
                <img src="/public/image/bkb_3d.png" class="h-48 md:h-64 object-contain hover:scale-105 transition-transform" alt="Ilustrasi BKB">
            </div>
        </div>
    </div>

    <!-- 2. KPI RINGKASAN (4 Kartu) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] p-5 flex items-center gap-4 hover:border-[var(--teal)] transition-colors min-h-[120px]">
            <div class="w-14 h-14 rounded-full bg-[var(--canvas)] text-[var(--teal)] flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <div class="text-xs text-[var(--muted)] font-bold uppercase tracking-wide mb-1 pb-0.5 export-fix-text">Cakupan Laporan</div>
                <div class="text-3xl font-black text-[var(--navy)] pb-1 export-fix-text">{{ $fmt($cakupan_pct) }}%</div>
                <div class="text-xs text-[var(--muted)] mt-1 pb-1 export-fix-text">{{ number_format($tot_lapor, 0, ',', '.') }} dr {{ number_format($tot_ada, 0, ',', '.') }} Kelompok</div>
            </div>
        </div>
        <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] p-5 flex items-center gap-4 hover:border-[var(--teal)] transition-colors min-h-[120px]">
            <div class="w-14 h-14 rounded-full bg-[#E6F4EA] text-[var(--green)] flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="text-xs text-[var(--muted)] font-bold uppercase tracking-wide mb-1 pb-0.5">Total Anggota Hadir</div>
                <div class="text-3xl font-black text-[var(--navy)] pb-1">{{ number_format($tot_hadir, 0, ',', '.') }}</div>
                <div class="text-xs text-[var(--muted)] mt-1 pb-1">Akumulasi BKB, BKR, BKL dll</div>
            </div>
        </div>
        <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] p-5 flex items-center gap-4 hover:border-[var(--teal)] transition-colors min-h-[120px]">
            <div class="w-14 h-14 rounded-full bg-[#FFF3E0] text-[var(--orange)] flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <div class="text-xs text-[var(--muted)] font-bold uppercase tracking-wide mb-1 pb-0.5">Program Dilaporkan</div>
                <div class="text-3xl font-black text-[var(--navy)] pb-1">6 / 6</div>
                <div class="text-xs text-[var(--muted)] mt-1 pb-1">Semua program beroperasi</div>
            </div>
        </div>
        <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] p-5 flex items-center gap-4 hover:border-[var(--teal)] transition-colors min-h-[120px]">
            <div class="w-14 h-14 rounded-full {{ $cakupan_pct >= 100 ? 'bg-[#E6F4EA] text-[var(--green)]' : 'bg-[#FEE2E2] text-[var(--red)]' }} flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-shield-check"></i>
            </div>
            <div>
                <div class="text-xs text-[var(--muted)] font-bold uppercase tracking-wide mb-1 pb-0.5">Status Laporan</div>
                <div class="text-2xl font-black text-[var(--navy)] leading-tight pb-1">{{ $status_overall }}</div>
                <div class="text-xs text-[var(--muted)] mt-1 pb-1">Konsolidasi Data</div>
            </div>
        </div>
    </div>

    <!-- 3. PANEL PROGRAM AKTIF (GRID 3x2) -->
    <div class="mb-4 mt-8 flex items-center justify-between border-b-2 border-[var(--navy)] pb-2">
        <h2 class="text-2xl font-extrabold text-[var(--navy)] uppercase tracking-tight">Ringkasan Enam Program</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @php
            $programs = [
                ['id' => 'bkb', 'name' => 'BKB', 'desc' => 'Bina Keluarga Balita', 'color' => 'var(--teal)', 'icon' => '/public/image/bkb_icon_v2.png', 'm1' => 'Keluarga Ikut', 'v1' => $d['bkb']['keluarga_ikut_bkb']['capaian']??0, 'm2' => 'Hadir KKA', 'v2' => $d['bkb']['anak_hadir_kka']['hadir']??0],
                ['id' => 'bkr', 'name' => 'BKR', 'desc' => 'Bina Keluarga Remaja', 'color' => 'var(--cyan)', 'icon' => '/public/image/bkr_icon_v2.png', 'm1' => 'Target Jmlh', 'v1' => $d['bkr']['anggota_hadir']['jumlah']??0, 'm2' => 'Hadir', 'v2' => $d['bkr']['anggota_hadir']['hadir']??0],
                ['id' => 'bkl', 'name' => 'BKL', 'desc' => 'Bina Keluarga Lansia', 'color' => 'var(--orange)', 'icon' => '/public/image/bkl_icon_v2.png', 'm1' => 'Jml Keluarga', 'v1' => $d['bkl']['anggota_hadir']['jumlah_keluarga']??0, 'm2' => 'Lansia Hadir', 'v2' => $d['bkl']['anggota_hadir']['lansia_hadir']??0],
                ['id' => 'pikr', 'name' => 'PIK-R', 'desc' => 'Pusat Info Konseling Remaja', 'color' => 'var(--red)', 'icon' => '/public/image/pikr_icon_v2.png', 'm1' => 'Jml Remaja', 'v1' => $d['pikr']['anggota_hadir']['jumlah_remaja']??0, 'm2' => 'Hadir', 'v2' => $d['pikr']['anggota_hadir']['hadir']??0],
                ['id' => 'uppka', 'name' => 'UPPKA', 'desc' => 'Usaha Peningkatan Pendapatan', 'color' => 'var(--navy)', 'icon' => '/public/image/uppka_icon_v2.png', 'm1' => 'Jml Kel. Ada', 'v1' => $d['uppka']['anggota_hadir']['jumlah_keluarga']??0, 'm2' => 'Kel. Hadir', 'v2' => $d['uppka']['anggota_hadir']['hadir']??0],
                ['id' => 'ppks', 'name' => 'PPKS', 'desc' => 'Pusat Pelayanan Keluarga', 'color' => 'var(--purple)', 'icon' => '/public/image/ppks_icon_v2.png', 'm1' => 'Klpk Ada', 'v1' => $d['ppks']['cakupan_laporan']['ada']??0, 'm2' => 'Klpk Lapor', 'v2' => $d['ppks']['cakupan_laporan']['lapor']??0]
            ];
        @endphp

        @foreach($programs as $p)
            @php 
                $lapor_pct = $d[$p['id']]['cakupan_laporan']['persentase'] ?? 0;
            @endphp
            <div class="export-card bg-white rounded-xl border border-slate-300 relative p-4 pb-0 flex flex-col justify-between" style="min-height: 200px;">
                <!-- HEADER: Increased padding bottom (pb-4) and text size (text-2xl) so it's not cramped -->
                <div class="flex justify-between items-start mb-4 border-b-2 pb-4" style="border-color: {{ $p['color'] }}">
                    <div class="flex-1 pr-2">
                        <h3 class="font-black text-[var(--navy)] text-2xl mb-1" style="line-height: 1.2;">{{ $p['name'] }}</h3>
                        <div class="text-xs font-bold text-[var(--muted)] uppercase tracking-wide">{{ $p['desc'] }}</div>
                    </div>
                    <div class="shrink-0 mt-1">
                        @if($lapor_pct >= 100)
                            <div class="text-[10px] font-bold text-[var(--green)] border border-[var(--green)] px-2 py-[2px] rounded bg-[#E6F4EA] uppercase tracking-wide leading-none inline-block export-fix-text">Lengkap</div>
                        @elseif($lapor_pct >= 50)
                            <div class="text-[10px] font-bold text-[var(--orange)] border border-[var(--orange)] px-2 py-[2px] rounded bg-[#FFF3E0] uppercase tracking-wide leading-none inline-block export-fix-text">Perlu Verif</div>
                        @else
                            <div class="text-[10px] font-bold text-[var(--red)] border border-[var(--red)] px-2 py-[2px] rounded bg-[#FEE2E2] uppercase tracking-wide leading-none inline-block export-fix-text">Minim</div>
                        @endif
                    </div>
                </div>

                <!-- BODY -->
                <div class="flex gap-4 md:gap-5 items-center mb-4">
                    <div class="w-24 md:w-32 shrink-0 flex items-center justify-center export-fix-icon">
                        <!-- Use simple image, no drop-shadow class -->
                        <img src="{{ $p['icon'] }}" class="w-full h-auto object-contain hover:scale-105 transition-transform origin-center">
                    </div>
                    
                    <div class="flex-1 bg-slate-50 p-3 rounded-lg border border-slate-200">
                        <div class="text-[10px] md:text-xs font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Cakupan Laporan</div>
                        <div class="text-xl md:text-2xl font-black text-[var(--navy)] mb-2 export-fix-text" style="line-height: 1;">{{ $fmt($lapor_pct) }}%</div>
                        <div class="w-full bg-slate-200 rounded-full h-2 mb-2">
                            <div class="h-full rounded-full" style="background-color: {{ $p['color'] }}; width: {{ min($lapor_pct, 100) }}%"></div>
                        </div>
                        <div class="text-[9px] font-medium text-slate-500 export-fix-text">{{ number_format($d[$p['id']]['cakupan_laporan']['lapor']??0, 0, ',', '.') }} lapor / {{ number_format($d[$p['id']]['cakupan_laporan']['ada']??0, 0, ',', '.') }} sasaran</div>
                    </div>
                </div>

                <!-- BOTTOM STATS -->
                <div class="grid grid-cols-2 border-t border-slate-200 mx-[-16px] px-4 py-3 bg-slate-50 rounded-b-xl" style="min-height: 70px;">
                    <div class="border-r border-slate-200 pr-3 flex flex-col justify-center">
                        <div class="text-[10px] font-bold text-slate-500 mb-1 uppercase tracking-wider leading-none">{{ $p['m1'] }}</div>
                        <div class="font-black text-[var(--navy)] text-xl leading-none">{{ number_format($p['v1'], 0, ',', '.') }}</div>
                    </div>
                    <div class="pl-3 flex flex-col justify-center">
                        <div class="text-[10px] font-bold text-slate-500 mb-1 uppercase tracking-wider leading-none">{{ $p['m2'] }}</div>
                        <div class="font-black text-[var(--navy)] text-xl leading-none">{{ number_format($p['v2'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 4. FOOTER RESMI -->
    <div class="bg-[var(--navy)] text-white rounded-xl py-3 px-5 flex flex-col lg:flex-row items-center justify-between gap-4 mt-6 shadow-sm">
        <div class="flex items-center gap-5 shrink-0">
            <img src="/public/image/logo-putih.png" class="h-16 md:h-20 object-contain drop-shadow-sm" alt="BKKBN Logo Putih">
            <div class="border-l-2 border-white/20 pl-5">
                <div class="font-bold text-sm md:text-base tracking-wide pb-0.5 export-fix-text">LAPORAN PENGENDALIAN LAPANGAN</div>
                <div class="text-xs text-blue-200 mt-1 opacity-90 pb-1 export-fix-text">Dicetak pada {{ date('d/m/Y H:i') }}</div>
            </div>
        </div>
        
        <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-4 text-[10px] sm:text-[11px] bg-[#022A59] px-4 py-2 rounded-lg border border-blue-800/50">
            <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-headset text-blue-400"></i>
                <span>Pengaduan <b class="text-yellow-400">085361209387</b></span>
            </div>
            <div class="hidden sm:block w-px h-3 bg-blue-700"></div>
            <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-globe text-blue-400"></i>
                <span class="text-blue-100">aceh.kemendukbangga.go.id</span>
            </div>
            <div class="hidden sm:block w-px h-3 bg-blue-700"></div>
            <div class="flex items-center gap-1.5">
                <i class="fa-brands fa-instagram text-blue-400"></i>
                <span class="text-blue-100">kemendukbangga_bkkbnaceh</span>
            </div>
        </div>
    </div>
</div>
