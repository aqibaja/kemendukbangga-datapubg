<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full min-h-screen flex justify-center py-4 sm:py-8 px-3 sm:px-4">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6 lg:p-8">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                            {{ $editMode ? 'Edit Laporan Capaian' : 'Tambah Laporan Capaian' }}
                        </h2>
                        <p class="text-sm text-gray-500">Isi data lengkap sesuai tipe dashboard</p>
                    </div>
                    <a href="/user"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                        ← Kembali
                    </a>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ $editMode ? route('laporan-capaian.update', $laporan->id) : route('laporan-capaian.store') }}"
                      id="laporanForm">
                    @csrf

                    {{-- Tipe + Bulan + Tahun --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1">Tipe Dashboard</label>
                            <select name="tipe" id="tipeSelect"
                                    class="w-full border rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="toggleFormSections()" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="pengendalian_lapangan" {{ ($laporan->tipe ?? '') == 'pengendalian_lapangan' ? 'selected' : '' }}>
                                    Capaian Pengendalian Lapangan
                                </option>
                                <option value="capaian_program" {{ ($laporan->tipe ?? '') == 'capaian_program' ? 'selected' : '' }}>
                                    Laporan Capaian Program
                                </option>
                                <option value="elsimil" {{ ($laporan->tipe ?? '') == 'elsimil' ? 'selected' : '' }}>
                                    Laporan Capaian ELSIMIL
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1">Bulan</label>
                            <select name="bulan" id="bulanSelect" class="w-full border rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="toggleElsimilMonths()">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ ($laporan->bulan ?? now()->month) == $m ? 'selected' : '' }}>
                                        {{ \App\Models\LaporanCapaian::namaBulan($m) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1">Tahun</label>
                            <select name="tahun" class="w-full border rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @for($y = 2024; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ ($laporan->tahun ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <hr class="mb-6">

                    @php
                        $d = $laporan->data ?? [];
                    @endphp

                    {{-- ======================== PENGENDALIAN LAPANGAN ======================== --}}
                    <div id="section-pengendalian_lapangan" class="form-section hidden">

                        {{-- BKB --}}
                        <h3 class="text-lg font-bold text-teal-700 mb-3 border-l-4 border-teal-500 pl-3">1. Bina Keluarga Balita (BKB)</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. Cakupan Laporan</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Ada</label><input type="number" name="bkb_cakupan_ada" class="inp" value="{{ $d['bkb']['cakupan_laporan']['ada'] ?? '' }}"></div>
                            <div><label class="lbl">Lapor</label><input type="number" name="bkb_cakupan_lapor" class="inp" value="{{ $d['bkb']['cakupan_laporan']['lapor'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkb_cakupan_persen" class="inp" value="{{ $d['bkb']['cakupan_laporan']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">b. Persentase Anak Hadir Menggunakan KKA</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Hadir</label><input type="number" name="bkb_kka_hadir" class="inp" value="{{ $d['bkb']['anak_hadir_kka']['hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Menggunakan KKA</label><input type="number" name="bkb_kka_menggunakan" class="inp" value="{{ $d['bkb']['anak_hadir_kka']['menggunakan_kka'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkb_kka_persen" class="inp" value="{{ $d['bkb']['anak_hadir_kka']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">c. Keluarga Ikut BKB</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Target</label><input type="number" name="bkb_keluarga_target" class="inp" value="{{ $d['bkb']['keluarga_ikut_bkb']['target'] ?? '' }}"></div>
                            <div><label class="lbl">Capaian</label><input type="number" name="bkb_keluarga_capaian" class="inp" value="{{ $d['bkb']['keluarga_ikut_bkb']['capaian'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkb_keluarga_persen" class="inp" value="{{ $d['bkb']['keluarga_ikut_bkb']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">d. Pembinaan Baduta 1000 HPK</p>
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div><label class="lbl">Target</label><input type="number" name="bkb_baduta_target" class="inp" value="{{ $d['bkb']['pembinaan_baduta']['target'] ?? '' }}"></div>
                            <div><label class="lbl">Capaian</label><input type="number" name="bkb_baduta_capaian" class="inp" value="{{ $d['bkb']['pembinaan_baduta']['capaian'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkb_baduta_persen" class="inp" value="{{ $d['bkb']['pembinaan_baduta']['persentase'] ?? '' }}"></div>
                        </div>

                        {{-- BKR --}}
                        <h3 class="text-lg font-bold text-teal-700 mb-3 border-l-4 border-teal-500 pl-3">2. Bina Keluarga Remaja (BKR)</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. Cakupan Laporan</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Ada</label><input type="number" name="bkr_cakupan_ada" class="inp" value="{{ $d['bkr']['cakupan_laporan']['ada'] ?? '' }}"></div>
                            <div><label class="lbl">Lapor</label><input type="number" name="bkr_cakupan_lapor" class="inp" value="{{ $d['bkr']['cakupan_laporan']['lapor'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkr_cakupan_persen" class="inp" value="{{ $d['bkr']['cakupan_laporan']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">b. Anggota BKR Hadir Pertemuan</p>
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div><label class="lbl">Jumlah</label><input type="number" name="bkr_anggota_jumlah" class="inp" value="{{ $d['bkr']['anggota_hadir']['jumlah'] ?? '' }}"></div>
                            <div><label class="lbl">Hadir</label><input type="number" name="bkr_anggota_hadir" class="inp" value="{{ $d['bkr']['anggota_hadir']['hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkr_anggota_persen" class="inp" value="{{ $d['bkr']['anggota_hadir']['persentase'] ?? '' }}"></div>
                        </div>

                        {{-- BKL --}}
                        <h3 class="text-lg font-bold text-teal-700 mb-3 border-l-4 border-teal-500 pl-3">3. Bina Keluarga Lansia (BKL)</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. Cakupan Laporan</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Ada</label><input type="number" name="bkl_cakupan_ada" class="inp" value="{{ $d['bkl']['cakupan_laporan']['ada'] ?? '' }}"></div>
                            <div><label class="lbl">Lapor</label><input type="number" name="bkl_cakupan_lapor" class="inp" value="{{ $d['bkl']['cakupan_laporan']['lapor'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkl_cakupan_persen" class="inp" value="{{ $d['bkl']['cakupan_laporan']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">b. Anggota BKL Hadir Pertemuan</p>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
                            <div><label class="lbl">Jumlah Keluarga</label><input type="number" name="bkl_anggota_jumlah_keluarga" class="inp" value="{{ $d['bkl']['anggota_hadir']['jumlah_keluarga'] ?? '' }}"></div>
                            <div><label class="lbl">Keluarga Hadir</label><input type="number" name="bkl_anggota_keluarga_hadir" class="inp" value="{{ $d['bkl']['anggota_hadir']['keluarga_hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Lansia Hadir</label><input type="number" name="bkl_anggota_lansia_hadir" class="inp" value="{{ $d['bkl']['anggota_hadir']['lansia_hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Total Hadir</label><input type="number" name="bkl_anggota_total_hadir" class="inp" value="{{ $d['bkl']['anggota_hadir']['total_hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="bkl_anggota_persen" class="inp" value="{{ $d['bkl']['anggota_hadir']['persentase'] ?? '' }}"></div>
                        </div>

                        {{-- PIK-R --}}
                        <h3 class="text-lg font-bold text-teal-700 mb-3 border-l-4 border-teal-500 pl-3">4. PIK-R</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. Cakupan Laporan</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Ada</label><input type="number" name="pikr_cakupan_ada" class="inp" value="{{ $d['pikr']['cakupan_laporan']['ada'] ?? '' }}"></div>
                            <div><label class="lbl">Lapor</label><input type="number" name="pikr_cakupan_lapor" class="inp" value="{{ $d['pikr']['cakupan_laporan']['lapor'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="pikr_cakupan_persen" class="inp" value="{{ $d['pikr']['cakupan_laporan']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">b. Anggota Remaja Hadir Pertemuan</p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                            <div><label class="lbl">Jumlah Remaja 15-19</label><input type="number" name="pikr_anggota_jumlah_remaja" class="inp" value="{{ $d['pikr']['anggota_hadir']['jumlah_remaja'] ?? '' }}"></div>
                            <div><label class="lbl">Target (10%)</label><input type="number" name="pikr_anggota_target" class="inp" value="{{ $d['pikr']['anggota_hadir']['target'] ?? '' }}"></div>
                            <div><label class="lbl">Hadir</label><input type="number" name="pikr_anggota_hadir" class="inp" value="{{ $d['pikr']['anggota_hadir']['hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="pikr_anggota_persen" class="inp" value="{{ $d['pikr']['anggota_hadir']['persentase'] ?? '' }}"></div>
                        </div>

                        {{-- UPPKA --}}
                        <h3 class="text-lg font-bold text-teal-700 mb-3 border-l-4 border-teal-500 pl-3">5. UPPKA</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. Cakupan Laporan</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">Ada</label><input type="number" name="uppka_cakupan_ada" class="inp" value="{{ $d['uppka']['cakupan_laporan']['ada'] ?? '' }}"></div>
                            <div><label class="lbl">Lapor</label><input type="number" name="uppka_cakupan_lapor" class="inp" value="{{ $d['uppka']['cakupan_laporan']['lapor'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="uppka_cakupan_persen" class="inp" value="{{ $d['uppka']['cakupan_laporan']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">b. Anggota UPPKA Hadir Pertemuan</p>
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div><label class="lbl">Jumlah Keluarga</label><input type="number" name="uppka_anggota_jumlah" class="inp" value="{{ $d['uppka']['anggota_hadir']['jumlah_keluarga'] ?? '' }}"></div>
                            <div><label class="lbl">Hadir</label><input type="number" name="uppka_anggota_hadir" class="inp" value="{{ $d['uppka']['anggota_hadir']['hadir'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="uppka_anggota_persen" class="inp" value="{{ $d['uppka']['anggota_hadir']['persentase'] ?? '' }}"></div>
                        </div>

                        {{-- PPKS --}}
                        <h3 class="text-lg font-bold text-teal-700 mb-3 border-l-4 border-teal-500 pl-3">6. PPKS</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. Cakupan Laporan</p>
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div><label class="lbl">Ada</label><input type="number" name="ppks_cakupan_ada" class="inp" value="{{ $d['ppks']['cakupan_laporan']['ada'] ?? '' }}"></div>
                            <div><label class="lbl">Lapor</label><input type="number" name="ppks_cakupan_lapor" class="inp" value="{{ $d['ppks']['cakupan_laporan']['lapor'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="ppks_cakupan_persen" class="inp" value="{{ $d['ppks']['cakupan_laporan']['persentase'] ?? '' }}"></div>
                        </div>
                    </div>

                    {{-- ======================== CAPAIAN PROGRAM ======================== --}}
                    <div id="section-capaian_program" class="form-section hidden">

                        {{-- 1. Fasyankes --}}
                        <h3 class="text-lg font-bold text-amber-700 mb-3 border-l-4 border-amber-500 pl-3">1. Cakupan Tempat Pelayanan Kesehatan</h3>
                        @php
                            $fasyItems = [
                                ['prefix' => 'fasy_pemerintah', 'label' => 'a. Pemerintah', 'key' => 'pemerintah'],
                                ['prefix' => 'fasy_jaringan', 'label' => 'b. Jaringan', 'key' => 'jaringan'],
                                ['prefix' => 'fasy_swasta', 'label' => 'c. Swasta', 'key' => 'swasta'],
                                ['prefix' => 'fasy_pmb_setara', 'label' => 'd. PMB Setara', 'key' => 'pmb_setara'],
                                ['prefix' => 'fasy_pmb_jejaring', 'label' => 'e. PMB Jejaring', 'key' => 'pmb_jejaring'],
                            ];
                        @endphp
                        @foreach($fasyItems as $fi)
                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">{{ $fi['label'] }}</p>
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <div><label class="lbl">Ada</label><input type="number" name="{{ $fi['prefix'] }}_ada" class="inp" value="{{ $d['cakupan_fasyankes'][$fi['key']]['ada'] ?? '' }}"></div>
                                <div><label class="lbl">Lapor</label><input type="number" name="{{ $fi['prefix'] }}_lapor" class="inp" value="{{ $d['cakupan_fasyankes'][$fi['key']]['lapor'] ?? '' }}"></div>
                                <div><label class="lbl">Persentase (%)</label><input type="text" name="{{ $fi['prefix'] }}_persen" class="inp" value="{{ $d['cakupan_fasyankes'][$fi['key']]['persentase'] ?? '' }}"></div>
                            </div>
                        @endforeach

                        {{-- 2. Stock Opname --}}
                        <h3 class="text-lg font-bold text-amber-700 mb-3 border-l-4 border-amber-500 pl-3 mt-6">2. Stock Opname Sirika</h3>
                        @php
                            $stockItems = [
                                ['prefix' => 'stock_provinsi', 'label' => 'a. Gudang Provinsi', 'key' => 'gudang_provinsi'],
                                ['prefix' => 'stock_kabkota', 'label' => 'b. Gudang Kab/Kota', 'key' => 'gudang_kabkota'],
                                ['prefix' => 'stock_fasyankes', 'label' => 'c. Gudang Fasyankes', 'key' => 'gudang_fasyankes'],
                            ];
                        @endphp
                        @foreach($stockItems as $si)
                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">{{ $si['label'] }}</p>
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <div><label class="lbl">Ada</label><input type="number" name="{{ $si['prefix'] }}_ada" class="inp" value="{{ $d['stock_opname'][$si['key']]['ada'] ?? '' }}"></div>
                                <div><label class="lbl">Laporan Stockopname</label><input type="number" name="{{ $si['prefix'] }}_laporan" class="inp" value="{{ $d['stock_opname'][$si['key']]['laporan'] ?? '' }}"></div>
                                <div><label class="lbl">Persentase (%)</label><input type="text" name="{{ $si['prefix'] }}_persen" class="inp" value="{{ $d['stock_opname'][$si['key']]['persentase'] ?? '' }}"></div>
                            </div>
                        @endforeach

                        {{-- 3. KB Baru --}}
                        <h3 class="text-lg font-bold text-amber-700 mb-3 border-l-4 border-amber-500 pl-3 mt-6">3. Capaian Peserta KB Baru</h3>
                        @php
                            $kbItems = [
                                ['prefix' => 'kb_pb', 'label' => 'a. Peserta KB Baru (PB)', 'key' => 'pb'],
                                ['prefix' => 'kb_pasca', 'label' => 'b. KB Pasca Persalinan', 'key' => 'pb_pasca_persalinan'],
                                ['prefix' => 'kb_mkjp', 'label' => 'c. PB MKJP', 'key' => 'pb_mkjp'],
                                ['prefix' => 'kb_nonmkjp', 'label' => 'd. PB Non MKJP', 'key' => 'pb_non_mkjp'],
                            ];
                        @endphp
                        @foreach($kbItems as $ki)
                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">{{ $ki['label'] }}</p>
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <div><label class="lbl">PPM</label><input type="number" name="{{ $ki['prefix'] }}_ppm" class="inp" value="{{ $d['kb_baru'][$ki['key']]['ppm'] ?? '' }}"></div>
                                <div><label class="lbl">Capaian</label><input type="number" name="{{ $ki['prefix'] }}_capaian" class="inp" value="{{ $d['kb_baru'][$ki['key']]['capaian'] ?? '' }}"></div>
                                <div><label class="lbl">Persentase (%)</label><input type="text" name="{{ $ki['prefix'] }}_persen" class="inp" value="{{ $d['kb_baru'][$ki['key']]['persentase'] ?? '' }}"></div>
                            </div>
                        @endforeach

                        {{-- 4. KB Aktif --}}
                        <h3 class="text-lg font-bold text-amber-700 mb-3 border-l-4 border-amber-500 pl-3 mt-6">4. Capaian Peserta KB Aktif</h3>
                        @php
                            $paItems = [
                                ['prefix' => 'pa_mkjp', 'label' => 'a. PA MKJP', 'key' => 'pa_mkjp'],
                                ['prefix' => 'pa_nonmkjp', 'label' => 'b. PA Non MKJP', 'key' => 'pa_non_mkjp'],
                                ['prefix' => 'pa_modern', 'label' => 'c. PA Modern', 'key' => 'pa_modern'],
                            ];
                        @endphp
                        @foreach($paItems as $pi)
                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">{{ $pi['label'] }}</p>
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <div><label class="lbl">PPM</label><input type="number" name="{{ $pi['prefix'] }}_ppm" class="inp" value="{{ $d['kb_aktif'][$pi['key']]['ppm'] ?? '' }}"></div>
                                <div><label class="lbl">Capaian</label><input type="number" name="{{ $pi['prefix'] }}_capaian" class="inp" value="{{ $d['kb_aktif'][$pi['key']]['capaian'] ?? '' }}"></div>
                                <div><label class="lbl">Persentase (%)</label><input type="text" name="{{ $pi['prefix'] }}_persen" class="inp" value="{{ $d['kb_aktif'][$pi['key']]['persentase'] ?? '' }}"></div>
                            </div>
                        @endforeach
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div><label class="lbl">PA Tradisional</label><input type="number" name="pa_tradisional" class="inp" value="{{ $d['kb_aktif']['pa_tradisional'] ?? '' }}"></div>
                            <div><label class="lbl">PA Keseluruhan</label><input type="number" name="pa_keseluruhan" class="inp" value="{{ $d['kb_aktif']['pa_keseluruhan'] ?? '' }}"></div>
                        </div>

                        {{-- 5. mCPR & Unmet Need --}}
                        <h3 class="text-lg font-bold text-amber-700 mb-3 border-l-4 border-amber-500 pl-3 mt-6">5. mCPR dan Unmet Need</h3>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">a. mCPR</p>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><label class="lbl">PUS</label><input type="number" name="mcpr_pus" class="inp" value="{{ $d['mcpr_unmet']['mcpr']['pus'] ?? '' }}"></div>
                            <div><label class="lbl">PA Modern</label><input type="number" name="mcpr_pa_modern" class="inp" value="{{ $d['mcpr_unmet']['mcpr']['pa_modern'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="mcpr_persen" class="inp" value="{{ $d['mcpr_unmet']['mcpr']['persentase'] ?? '' }}"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">b. Unmet Need</p>
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div><label class="lbl">PUS</label><input type="number" name="unmet_pus" class="inp" value="{{ $d['mcpr_unmet']['unmet_need']['pus'] ?? '' }}"></div>
                            <div><label class="lbl">UN</label><input type="number" name="unmet_un" class="inp" value="{{ $d['mcpr_unmet']['unmet_need']['un'] ?? '' }}"></div>
                            <div><label class="lbl">Persentase (%)</label><input type="text" name="unmet_persen" class="inp" value="{{ $d['mcpr_unmet']['unmet_need']['persentase'] ?? '' }}"></div>
                        </div>
                    </div>

                    {{-- ======================== ELSIMIL ======================== --}}
                    <div id="section-elsimil" class="form-section hidden">
                        <h3 class="text-lg font-bold text-emerald-700 mb-3 border-l-4 border-emerald-500 pl-3">Trend Jumlah Catin Terdampingi</h3>
                        <p class="text-xs text-gray-500 mb-3">Isi data per bulan (dari Januari sampai bulan yang dilaporkan)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                            @for($m = 1; $m <= 12; $m++)
                                <div class="elsimil-month-group" data-month="{{ $m }}">
                                    <label class="lbl">{{ \App\Models\LaporanCapaian::namaBulan($m) }}</label>
                                    <input type="number" name="elsimil_catin_{{ $m }}" class="inp"
                                           value="{{ $d['catin'][$m] ?? $d['catin'][(string)$m] ?? '' }}">
                                </div>
                            @endfor
                        </div>

                        <h3 class="text-lg font-bold text-emerald-700 mb-3 border-l-4 border-emerald-500 pl-3">Trend Jumlah Bumil Terdampingi</h3>
                        <p class="text-xs text-gray-500 mb-3">Isi data per bulan (dari Januari sampai bulan yang dilaporkan)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                            @for($m = 1; $m <= 12; $m++)
                                <div class="elsimil-month-group" data-month="{{ $m }}">
                                    <label class="lbl">{{ \App\Models\LaporanCapaian::namaBulan($m) }}</label>
                                    <input type="number" name="elsimil_bumil_{{ $m }}" class="inp"
                                           value="{{ $d['bumil'][$m] ?? $d['bumil'][(string)$m] ?? '' }}">
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Placeholder if no type selected --}}
                    <div id="section-placeholder" class="text-center py-12 text-gray-400">
                        <i class="fa-solid fa-arrow-up text-3xl mb-3 block"></i>
                        <p class="text-lg font-semibold">Pilih Tipe Dashboard di atas</p>
                        <p class="text-sm">Form input akan muncul sesuai tipe yang dipilih</p>
                    </div>

                    {{-- Submit --}}
                    <div class="flex flex-col sm:flex-row justify-center gap-3 mt-6 pt-6 border-t">
                        <button type="submit" id="submitBtn"
                                class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-semibold hidden">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>
                            {{ $editMode ? 'Update Laporan' : 'Simpan Laporan' }}
                        </button>
                        <a href="/user"
                           class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-semibold text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .lbl {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }
        .inp {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .inp:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
    </style>

    <script>
        function toggleFormSections() {
            const tipe = document.getElementById('tipeSelect').value;
            const sections = document.querySelectorAll('.form-section');
            const placeholder = document.getElementById('section-placeholder');
            const submitBtn = document.getElementById('submitBtn');

            sections.forEach(s => s.classList.add('hidden'));

            if (tipe) {
                const target = document.getElementById('section-' + tipe);
                if (target) {
                    target.classList.remove('hidden');
                }
                placeholder.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                
                if (tipe === 'elsimil') {
                    toggleElsimilMonths();
                }
            } else {
                placeholder.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function toggleElsimilMonths() {
            const selectedMonth = document.getElementById('bulanSelect').value;
            const elsimilGroups = document.querySelectorAll('.elsimil-month-group');
            
            elsimilGroups.forEach(group => {
                if (group.getAttribute('data-month') === selectedMonth) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
        }

        // Init on page load
        document.addEventListener('DOMContentLoaded', () => {
            toggleFormSections();
            toggleElsimilMonths();
        });
    </script>
</x-layout>
