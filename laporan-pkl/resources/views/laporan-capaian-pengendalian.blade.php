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
<div class="mb-2 text-[var(--text)] w-full max-w-[1440px] mx-auto px-4 sm:px-6 relative">
    
    <!-- WATERMARK BACKGROUND -->
    <div class="absolute inset-0 pointer-events-none z-[0] flex items-center justify-center overflow-hidden">
        <!-- Apply opacity directly to the image so html2canvas doesn't ignore it -->
        <img src="/public/image/logoBKKBN.png" class="w-[60%] max-w-[800px] object-contain opacity-[0.05]" alt="Watermark BKKBN">
    </div>
    <!-- 1. HERO SECTION -->
    <div class="bg-white rounded-[16px] shadow-sm border border-[var(--line)] overflow-hidden mb-6 relative z-10">
        <div class="relative z-10 px-6 py-8 md:px-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                     <img src="/public/image/logoBKKBN.png" style="height: 56px;" alt="Watermark BKKBN">
                    <div class="leading-snug">
                            <div class="text-sm sm:text-base font-semibold text-slate-500">Kementerian Kependudukan dan Pembangunan Keluarga/BKKBN</div>
                            <div class="text-base sm:text-lg font-extrabold text-teal-700">Perwakilan BKKBN Provinsi Aceh</div>
                        </div>
                </div>
                <div style="display: inline-block; padding: 8px 16px; background: var(--canvas); color: var(--teal); font-size: 1.125rem; font-weight: 600; border-radius: 6px; border: 1px solid var(--line); margin-bottom: 16px; white-space: nowrap;">
                    <i class="fa-solid fa-calendar-alt export-shift-icon" style="vertical-align: middle; margin-top: -2px;"></i>
                    <span style="vertical-align: middle; margin-left: 6px; display: inline-block;">Periode: {{ App\Models\LaporanCapaian::namaBulan($laporans['pengendalian_lapangan']->bulan) }} {{ $laporans['pengendalian_lapangan']->tahun }}</span>
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-[var(--navy)] mb-2 uppercase tracking-tight export-fix-text">LAPORAN CAPAIAN PROGRAM</h1>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[var(--teal)] mb-4 export-fix-text">PENGENDALIAN LAPANGAN</h2>
                <div class="flex flex-wrap gap-3 text-base text-[var(--muted)]">
                    <div style="display: inline-block; background: #f1f5f9; padding: 8px 14px; border-radius: 6px; white-space: nowrap;">
                        <span style="display: inline-block;">Update data: {{ $laporans['pengendalian_lapangan']->updated_at->format('d M Y') }}</span>
                    </div>
                    <div style="display: inline-block; background: #f1f5f9; padding: 8px 14px; border-radius: 6px; white-space: nowrap;">
                        <span style="display: inline-block;">Sumber: Perwakilan BKKBN Prov Aceh</span>
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-4">
                <img src="/public/image/bkb_3d.png" style="height: 280px;" class="object-contain hover:scale-105 transition-transform" alt="Ilustrasi BKB">
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
                        <h3 class="font-black text-[var(--navy)] text-3xl mb-1" style="line-height: 1.2;">{{ $p['name'] }}</h3>
                        <div class="text-base font-bold text-[var(--muted)] uppercase tracking-wide">{{ $p['desc'] }}</div>
                    </div>
                </div>

                <!-- BODY -->
                <div class="flex gap-4 md:gap-5 items-center mb-4">
                    <div class="w-28 md:w-36 lg:w-40 shrink-0 flex items-center justify-center export-fix-icon">
                        <!-- Use simple image, no drop-shadow class -->
                        <img src="{{ $p['icon'] }}" class="w-full h-auto object-contain hover:scale-105 transition-transform origin-center">
                    </div>
                    
                    <div class="flex-1 bg-slate-50 p-3 rounded-lg border border-slate-200 overflow-hidden min-w-0">
                        <div class="text-sm md:text-base font-bold text-[var(--muted)] uppercase mb-1 export-fix-text">Cakupan Laporan</div>
                        <div class="font-black text-[var(--navy)] mb-2 export-fix-text" style="font-size: clamp(1.75rem, 4vw, 2.5rem); line-height: 1.1;">{{ $fmt($lapor_pct) }}%</div>
                        <div class="w-full bg-slate-200 rounded-full h-2 mb-2">
                            <div class="h-full rounded-full" style="background-color: {{ $p['color'] }}; width: {{ min($lapor_pct, 100) }}%"></div>
                        </div>
                        <div class="text-xs md:text-sm font-medium text-slate-500 export-fix-text">{{ number_format($d[$p['id']]['cakupan_laporan']['lapor']??0, 0, ',', '.') }} lapor / {{ number_format($d[$p['id']]['cakupan_laporan']['ada']??0, 0, ',', '.') }} sasaran</div>
                    </div>
                </div>

                <!-- BOTTOM STATS -->
                <div class="grid grid-cols-2 border-t border-slate-200 mx-[-16px] px-4 pt-4 pb-5 bg-slate-50 rounded-b-xl">
                    <div class="border-r border-slate-200 pr-3 flex flex-col justify-center">
                        <div class="text-sm font-bold text-slate-500 mb-2 uppercase tracking-wider leading-none">{{ $p['m1'] }}</div>
                        <div class="font-black text-[var(--navy)] text-3xl leading-none">{{ number_format($p['v1'], 0, ',', '.') }}</div>
                    </div>
                    <div class="pl-3 flex flex-col justify-center">
                        <div class="text-sm font-bold text-slate-500 mb-2 uppercase tracking-wider leading-none">{{ $p['m2'] }}</div>
                        <div class="font-black text-[var(--navy)] text-3xl leading-none">{{ number_format($p['v2'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 4. FOOTER RESMI -->
    <div class="bg-[var(--navy)] text-white rounded-xl mt-6 shadow-sm px-6 py-4 flex flex-wrap items-center justify-center gap-x-12 gap-y-4">
        <!-- Kiri: Logo + Judul -->
        <div class="flex items-center gap-4 shrink-0">
            <img src="/public/image/logo-putih.png" class="h-10 object-contain drop-shadow-sm shrink-0" alt="BKKBN Logo Putih">
            <div class="border-l-2 border-white/20 pl-4">
                <div class="font-bold text-sm tracking-wide export-fix-text">LAPORAN PENGENDALIAN LAPANGAN</div>
                <div class="text-xs text-blue-200 opacity-90 mt-0.5 export-fix-text">Dicetak pada {{ date('d/m/Y H:i') }}</div>
            </div>
        </div>
        <!-- Kanan: Info Kontak -->
        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs shrink-0">
            <div style="white-space: nowrap;">
                <i class="fa-solid fa-headset text-blue-400 export-shift-icon" style="vertical-align: middle; margin-right: 4px;"></i>
                <span style="vertical-align: middle;">Pengaduan <span class="text-yellow-400 font-bold">085361209387</span></span>
            </div>
            <div style="white-space: nowrap;">
                <i class="fa-solid fa-globe text-blue-400 export-shift-icon" style="vertical-align: middle; margin-right: 4px;"></i>
                <span class="text-blue-200" style="vertical-align: middle;">aceh.kemendukbangga.go.id</span>
            </div>
            <div style="white-space: nowrap;">
                <i class="fa-brands fa-instagram text-blue-400 export-shift-icon" style="vertical-align: middle; margin-right: 4px;"></i>
                <span class="text-blue-200" style="vertical-align: middle;">kemendukbangga_bkkbnaceh</span>
            </div>
        </div>
    </div>
</div>
