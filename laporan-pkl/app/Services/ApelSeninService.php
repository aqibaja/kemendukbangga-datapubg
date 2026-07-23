<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ApelSeninService
{
    /**
     * Daftar Tim Kerja.
     * Slug = bagian akhir nama file foto di: public/image/foto-katim/timkerja-{slug}.jpeg
     * CSV anggota di: public/image/foto-katim/APEL SENIN_{CSV_KEY}_Table.csv
     */
    public static array $timKerja = [
        'PENGELOLAAN KEPENDUDUKAN' => [
            'slug'    => 'PengelolaanKependudukan',
            'ketua'   => 'Ketua Tim Pengelolaan Kependudukan',
        ],
        'KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI' => [
            'slug'    => 'KeluargaBerencanaDanKesehatanReproduksi',
            'ketua'   => 'Ketua Tim KB & Kesehatan Reproduksi',
        ],
        'PEMBANGUNAN KELUARGA' => [
            'slug'    => 'PembangunanKeluarga',
            'ketua'   => 'Ketua Tim Pembangunan Keluarga',
        ],
        'PENGGERAKKAN MASYARAKAT DAN PENGELOLAAN LINI LAPANGAN' => [
            'slug'    => 'PenggerakkanMasyarakatDanPengelolaanLiniLapangan',
            'ketua'   => 'Ketua Tim Penggerakkan Masyarakat & Lini Lapangan',
        ],
        'PERENCANAAN DAN KEUANGAN' => [
            'slug'    => 'PerencanaanDanKeuangan',
            'ketua'   => 'Ketua Tim Perencanaan & Keuangan',
        ],
        'PENGELOLAAN SDM, ORGANISASI, DAN HUKUM' => [
            'slug'    => 'PengelolaanSDMOrganisasiDanHukum',
            'ketua'   => 'Ketua Tim SDM, Organisasi & Hukum',
        ],
        'PENGELOLAAN MANAJEMEN KINERJA' => [
            'slug'    => 'PengelolaanManajemenKinerja',
            'ketua'   => 'Ketua Tim Manajemen Kinerja',
        ],
        'UMUM, HUMAS, DAN PROTOKOL' => [
            'slug'    => 'UmumHumasDanProtokol',
            'ketua'   => 'Ketua Tim Umum, Humas & Protokol',
        ],
        'DATA DAN INFORMASI' => [
            'slug'    => 'DataDanInformasi',
            'ketua'   => 'Ketua Tim Data & Informasi',
        ],
        'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH' => [
            'slug'    => 'atasan',
            'ketua'   => 'Kepala Perwakilan BKKBN Aceh',
        ],
    ];

    public static array $teamMembers = [
        'PENGELOLAAN KEPENDUDUKAN' => [
            'AHMAD KHALIDI', 'ALFIN KHAIRI, M. SC', 'CITRA MULIANI, S.K, PM', 'DEDI SASTRADI, SP.M. EMD', 'HILDAN MAWARDI, S. SOS, M. SI', 'KIKY RIZKY ANANDA, SKM', 'NUR AFFIFAH PASARIBU, SKM', 'SRIWAHYUNI, SE', 'ZULKIFLI, S.E., M.A.P.'
        ],
        'KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI' => [
            'ARIUS GUSNANDAR, SE', 'DR. CUT LIZA FEBTYA', 'DR. NOLIASARI', 'HAYATUL KHOLIFAH PUTRI, SKM', 'HUDIATUL AULI', 'KESSA IKHWANDA, SKM', 'KHAIRUL IBAD, SE, M. SI', 'MUTIA ANDRIANI, S. STAT', 'NONI YUSVALIANA, SKM', 'NURHABIBAH PRASETYA, SKM', 'REFA ALAYDRUS, S. FARM, APT', 'ROSLINDAWATI, SKM, M.SI', 'SURYADI SAPUTRA, AMD. AK', 'ZULHIKMAH SAHPUTRI, ST'
        ],
        'PEMBANGUNAN KELUARGA' => [
            'ASTRI, S. SOS', 'DINA ASTITA, S. AG, M. SI', 'DR. AFRIDA, MAPS', 'FAISAL', 'FITRIA ISMAYANTI, S. TR. S. SOS', 'HAYATUR RAHMI, M. SC', 'MUCHTAR LUBIS', 'NIHRASYIAH, S. PSI', 'NURHAYATI, S. PD', 'NURISMI, SE, M. SC', 'NURLAILI', 'PUSPITA PALUPI, S. PSI', 'RIDHA ILHAM, ST, M. SI', 'SAIFUDDIN', 'SITI KHAIRIYANI, S. I. KOM', 'WAHYU', 'WINDA NURI ADINDA, S. PD'
        ],
        'PENGGERAKKAN MASYARAKAT DAN PENGELOLAAN LINI LAPANGAN' => [
            'AL KINDI HARLEY, S. SOS, MA', 'DR. MUHAMMAD JABARI, MAP', 'EFIYANTI, S.H, MAP', 'ELA ISWARI, SE', 'ERIENA SARTIKA AYU, S. PSI, MAPS', 'FARIDAH, SE, MM', 'HADIANA QANITA, S. STAT', 'IRMA DIMYATI, SE, M. SI', 'IRWANSYAH', 'MURTADA', 'RINA KHAIRUNA NASUTION, SKM', 'SANIAH'
        ],
        'PERENCANAAN DAN KEUANGAN' => [
            'ABDI DZIL IKRAM, SE', 'AGUS MUNANDAR, SE', 'DINI RAMADINI', 'HUSNI THAMRIN, SE, MM', 'IHSAN KUSUMA', 'ISWANI, SST', 'MAHLIA FARDIANTO', 'MITA ARLINI, SE, M. SI', 'MUTTAQIN, A. MD', 'NASRUDDIN, SE', 'NITA AFRIDA, SE', 'NOVI YANTI FARIDAH', 'NOVIA PUCHA AUDYA, S. STAT', 'RIKA ANGGRAINI, SE', 'RINA MAGHFIRAH, A. MD', 'SUCI WULANDARI T, SKM', 'TAMLIKHA, SE, MAP'
        ],
        'PENGELOLAAN SDM, ORGANISASI, DAN HUKUM' => [
            'ARI MARDANA, S. SOS', 'CUTTI HAJAR, AMD. MSDM', 'ILHAM SYAHPUTRA, S. SOS, M. SI', 'IRA MEUTYA, S. PSI', 'ISHANI YUNITA, S. SOS. I', 'MADIAN, SE', 'MAUKIYUDDIN, A. MD', 'MUKHYAR, S. SOS', 'MUNA MAULIDA, M. PD', 'ROMI FAHRI, S. KOM', 'RUHUL BAWADI, SE', 'SARI FITRIANI, S. PSI', 'SRI WAHYUNI, SKM', 'TARI INDAH PERTIWI', 'ZULFIKAR, SE'
        ],
        'PENGELOLAAN MANAJEMEN KINERJA' => [
            'AMRIZAL, SH, M. HUM', 'ARIFA ZAHRA, S. IP', 'EARLY PROPHITA, S. PSI, MM', 'FAHMI, SE', 'FENNY SILFIA PUTRI, SE, M. SI', 'FITA RONAYA, SH', 'JUNI MAHZUR, S. SOS, M. SI', 'M. ZAIN', 'MAYA ZATIL AQMAR, SE', 'RIFKI KHAIRUL AMRI, SE', 'SURYA RIZKY, S.I.KOM'
        ],
        'UMUM, HUMAS, DAN PROTOKOL' => [
            'AGUS MIDARLI', 'AIDIL FITARSYAH', 'ALISSA SHAFIRA AYUWI, S. IP', 'ARMIADI', 'AZHAR, SE', 'AZHARI', 'BAHRI ASMAWI, SE', 'CUT ROSA MINORA, SE', 'DENI NURFANDI', 'EDDY MUNAWAR, ST, M. SI', 'FAHRI MA\'RUF, S.A.P', 'FREDY, S. PD', 'HANIFAH, SE', 'ICHYA ULUMIDDIN', 'IRMAWATI, S. FARM', 'JOPI DIAN SAPUTRA, SE, S. SOS', 'KHALIS', 'MAULIDI', 'MUHAMMAD', 'MUHAMMAD IQBAL', 'MURTAZA', 'NIKMATUL AKBAR, S. AK, MAP', 'NOVI MAULINA, A. MD', 'NURHAFIZAH, A. MD', 'NURMIATI, SE', 'RAHMATSYAH, SE', 'RATNA YUSA DEWI', 'RIO HUSNADI', 'RIZAL SYAHPUTRA', 'RIZQA ANDRIANI LUBIS, S.I.KOM', 'RONA VITRYA', 'SRI RAIHAN, S. PD, MAP', 'SUNARTI', 'T.M. NASIR', 'WIDIA GUSTIASARI, SKM', 'YORA MUNIRAH, S.I.KOM', 'YOVANDI FEBRIANSYAH', 'ZARRA SILVIA BALQIS, S. SOS', 'ZULFADHLI, SE', 'ZULFIKAR, SE'
        ],
        'DATA DAN INFORMASI' => [
            'BAYU PRAWIRA, S. KOM', 'INAS SALSABILA, S. STAT', 'M. HUSNUL AQIB, ST', 'NANDA MASITHAH, SKM', 'ROSNA', 'WAHYU RIZKY, ST'
        ],
        'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH' => [
            'IHYA, S.E., M.M.', 'SAFRINA SALIM, SKM, M.KES'
        ],
    ];

    /**
     * Ambil semua data presensi dari Google Apps Script (dengan cache).
     */
    public function getAllData(): array
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);
        set_time_limit(120);

        $cacheKey      = 'apel_senin_data_gsheet';
        $cacheDuration = 60 * 60 * 6; // 6 jam (dalam detik)

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $apiUrl = env('APEL_SENIN_SCRIPT_URL');
        $rows   = [];

        if (!empty($apiUrl)) {
            try {
                $response = Http::withoutVerifying()->timeout(10)->get($apiUrl);

                if ($response->successful()) {
                    $data = json_decode($response->body(), true);

                    if (is_array($data) && count($data) > 0) {
                        foreach ($data as $row) {
                            $namaRaw = trim($row['nama'] ?? $row['NAMA'] ?? '');
                            if (!$namaRaw) continue;

                            $timRaw = trim(strtoupper($row['tim_kerja'] ?? $row['TIM_KERJA'] ?? ''));
                            if (!$timRaw) continue;

                            // Normalisasi nama tim agar cocok dengan key di $timKerja
                            $tim  = $this->normalizeTeamName($timRaw);
                            $nama = $this->normalizeMemberName($namaRaw);

                            $rows[] = [
                                'timestamp'    => $row['timestamp'] ?? $row['Timestamp'] ?? null,
                                'email'        => $row['email']     ?? $row['Email Address'] ?? '',
                                'tim_kerja'    => $tim,
                                'nama'         => $nama,
                                'ikut_apel'    => $row['ikut_apel']  ?? 'Ya',
                                'keterangan'   => $row['keterangan'] ?? '',
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('ApelSenin Fetch Exception: ' . $e->getMessage());
            }
        }

        // Selalu simpan ke cache meskipun hasilnya kosong,
        // agar halaman loading tidak looping terus-menerus
        Cache::put($cacheKey, $rows, $cacheDuration);

        return $rows;
    }

    /**
     * Hapus cache agar data bisa di-refresh.
     */
    public function clearCache(): void
    {
        Cache::forget('apel_senin_data_gsheet');
    }

    /**
     * Daftar tanggal apel yang tersedia (sebagai filter dropdown).
     * Format: ['2026-01-26' => 'Senin, 26 Januari 2026', ...]
     */
    public function getApelDates(): array
    {
        $data  = $this->getAllData();
        $dates = [];

        foreach ($data as $item) {
            if (empty($item['timestamp'])) continue;
            try {
                $date  = Carbon::parse($item['timestamp'])->format('Y-m-d');
                if (!isset($dates[$date])) {
                    $carbon = Carbon::parse($date)->locale('id');
                    $dates[$date] = $carbon->isoFormat('dddd, D MMMM YYYY');
                }
            } catch (\Exception $e) {
                // skip invalid timestamp
            }
        }

        krsort($dates); // terbaru duluan
        return $dates;
    }

    /**
     * Statistik jumlah kehadiran per Tim Kerja pada satu tanggal apel.
     * Jika $date = null → akumulasi semua tanggal (unique peserta per apel).
     *
     * @return array ['TIM KERJA' => count, ...]
     */
    public function getStatsByTeam(?string $date = null): array
    {
        $data      = $this->getAllData();
        $teamCount = [];

        // Inisialisasi semua tim dengan 0
        foreach (array_keys(self::$timKerja) as $tim) {
            $teamCount[$tim] = 0;
        }

        // Untuk menghindari duplikat pada hari yang sama
        $seenKeys = [];

        foreach ($data as $item) {
            if (empty($item['timestamp'])) continue;
            try {
                $itemDate = Carbon::parse($item['timestamp'])->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
            
            if ($date !== null && $itemDate !== $date) {
                continue;
            }

            $tim  = $this->normalizeTeamName($item['tim_kerja']);
            $nama = $item['nama'];

            $key  = $itemDate . '|' . $tim . '|' . $nama;

            if (isset($seenKeys[$key])) continue;
            $seenKeys[$key] = true;

            // Pastikan hanya tim yang valid yang dihitung
            if (!isset(self::$timKerja[$tim])) {
                continue;
            }

            if (!isset($teamCount[$tim])) {
                $teamCount[$tim] = 0;
            }
            $teamCount[$tim]++;
        }

        arsort($teamCount);
        return $teamCount;
    }

    /**
     * Ranking peserta di satu Tim Kerja berdasarkan frekuensi kehadiran apel.
     *
     * @param string $tim  Nama tim kerja
     * @return array [['nama' => ..., 'attended_count' => ..., 'percentage' => ..., 'total_apel' => ...], ...]
     */
    public function getMemberRankingByTeam(string $tim): array
    {
        $data        = $this->getAllData();
        $allDates    = $this->getApelDates();
        $totalApel   = count($allDates);
        $timNorm     = $this->normalizeTeamName($tim);
        $persons     = [];

        foreach ($data as $item) {
            if ($this->normalizeTeamName($item['tim_kerja']) !== $timNorm) continue;

            $nama = trim($item['nama']);
            if (!$nama) continue;

            if (empty($item['timestamp'])) continue;
            try {
                $date = Carbon::parse($item['timestamp'])->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }

            if (!isset($persons[$nama])) {
                $persons[$nama] = ['nama' => $nama, 'dates' => []];
            }
            $persons[$nama]['dates'][$date] = true;
        }

        $rankings = [];
        foreach ($persons as $nama => $info) {
            $attended  = count($info['dates']);
            $pct       = $totalApel > 0 ? round(($attended / $totalApel) * 100, 1) : 0;
            $rankings[] = [
                'nama'           => $nama,
                'attended_count' => $attended,
                'total_apel'     => $totalApel,
                'percentage'     => $pct,
            ];
        }

        usort($rankings, fn($a, $b) => $b['attended_count'] <=> $a['attended_count']);
        return $rankings;
    }

    /**
     * Trend kehadiran per tanggal apel untuk satu tim tertentu.
     *
     * @param string $tim
     * @return array ['2026-01-26' => count, ...]
     */
    public function getTrendByTeam(string $tim): array
    {
        $data    = $this->getAllData();
        $timNorm = $this->normalizeTeamName($tim);
        $trend   = [];

        // Inisialisasi semua tanggal dengan 0
        foreach (array_keys($this->getApelDates()) as $date) {
            $trend[$date] = 0;
        }

        $seen = [];

        foreach ($data as $item) {
            if ($this->normalizeTeamName($item['tim_kerja']) !== $timNorm) continue;
            if (empty($item['timestamp'])) continue;

            try {
                $date = Carbon::parse($item['timestamp'])->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }

            $key = $date . '|' . $item['nama'];
            if (isset($seen[$key])) continue;
            $seen[$key] = true;

            $trend[$date] = ($trend[$date] ?? 0) + 1;
        }

        ksort($trend);
        return $trend;
    }

    /**
     * Peserta hadir di satu tim kerja pada tanggal tertentu.
     *
     * @param string $tim
     * @param string|null $date  null = semua tanggal
     * @return array [['nama' => ..., 'tanggal' => ..., 'keterangan' => ...], ...]
     */
    public function getAttendeesByTeam(string $tim, ?string $date = null): array
    {
        $data    = $this->getAllData();
        $timNorm = $this->normalizeTeamName($tim);
        $result  = [];
        $seen    = [];

        foreach ($data as $item) {
            if ($this->normalizeTeamName($item['tim_kerja']) !== $timNorm) continue;

            if ($date !== null) {
                if (empty($item['timestamp'])) continue;
                try {
                    $itemDate = Carbon::parse($item['timestamp'])->format('Y-m-d');
                } catch (\Exception $e) {
                    continue;
                }
                if ($itemDate !== $date) continue;
            }

            $nama = trim($item['nama']);
            $key  = ($date ?? 'all') . '|' . $nama;
            if (isset($seen[$key])) continue;
            $seen[$key] = true;

            $tanggal = '';
            if (!empty($item['timestamp'])) {
                try {
                    $tanggal = Carbon::parse($item['timestamp'])->locale('id')->isoFormat('D MMMM YYYY');
                } catch (\Exception $e) {}
            }

            $result[] = [
                'nama'       => $nama,
                'tanggal'    => $tanggal,
                'keterangan' => $item['keterangan'] ?? '',
                'timestamp'  => $item['timestamp'] ?? '',
            ];
        }

        usort($result, fn($a, $b) => strcmp($a['nama'], $b['nama']));
        return $result;
    }

    public function normalizeTeamName(string $name): string
    {
        $normalized = trim(strtoupper(preg_replace('/\s+/', ' ', $name)));
        
        // Gabungkan variasi nama Perwakilan Aceh menjadi satu
        if (str_contains($normalized, 'PERWAKILAN BKKBN PROVINSI ACEH')) {
            return 'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH';
        }

        // Mapping tim lama ke tim baru agar data historis tetap masuk ke 9 tim utama
        $map = [
            'KELUARGA SEJAHTERA DAN PEMBANGUNAN KELUARGA' => 'PEMBANGUNAN KELUARGA',
            'UMUM, PELAYANAN PUBLIK DAN PENGELOLAAN BMN'  => 'UMUM, HUMAS, DAN PROTOKOL',
            'HUBUNGAN MASYARAKAT DAN INFORMASI PUBLIK'    => 'UMUM, HUMAS, DAN PROTOKOL',
            'PENGENDALIAN PENDUDUK DAN JAKSTRA'           => 'PENGELOLAAN KEPENDUDUKAN',
            'PENGGERAKKAN DAN PERAN SERTA MASYARAKAT'     => 'PENGGERAKKAN MASYARAKAT DAN PENGELOLAAN LINI LAPANGAN',
            'ZI WBK/WBBM DAN SPIP'                        => 'PENGELOLAAN MANAJEMEN KINERJA',
            'HUKUM DAN KEPEGAWAIAN'                       => 'PENGELOLAAN SDM, ORGANISASI, DAN HUKUM',
            'PELATIHAN DAN PENGEMBANGAN KOMPETENSI'       => 'PENGELOLAAN SDM, ORGANISASI, DAN HUKUM',
            'BINA KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI' => 'KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI',
            'PELAPORAN STATISTIK DAN PENGELOLAAN TIK'     => 'DATA DAN INFORMASI',
        ];

        if (isset($map[$normalized])) {
            return $map[$normalized];
        }

        // Jika tim masih tidak valid dengan daftar saat ini, biarkan apa adanya 
        // namun nanti akan difilter di getStatsByTeam
        
        return $normalized;
    }

    /**
     * URL foto ketua tim dari folder foto-katim.
     * Pattern file: public/image/foto-katim/timkerja-{slug}.jpeg
     * Fallback ke ui-avatars jika file tidak ditemukan.
     */
    public static function getPhotoUrl(string $tim): string
    {
        $slug = self::$timKerja[$tim]['slug'] ?? null;

        if ($slug) {
            $fileName = "timkerja-{$slug}.jpeg";
            
            // Cek di dalam folder laporan-pkl/public/image (saat diekstrak dari ZIP)
            if (file_exists(base_path("public/image/foto-katim/{$fileName}"))) {
                return asset("laporan-pkl/public/image/foto-katim/{$fileName}");
            }
            
            // Cek di root public_html/image (jika sudah dipindahkan manual)
            if (file_exists(public_path("image/foto-katim/{$fileName}"))) {
                return asset("image/foto-katim/{$fileName}");
            }
        }

        // Fallback: avatar berbasis inisial 2 kata pertama nama tim
        $initials = strtoupper(implode('', array_map(
            fn($w) => $w[0],
            array_slice(explode(' ', $tim), 0, 2)
        )));
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials)
            . '&background=0D6EFD&color=fff&bold=true&size=160&font-size=0.4';
    }

    /**
     * Normalisasi nama anggota (mengabaikan titik, koma, spasi, huruf besar/kecil)
     * agar data dari Google Sheets cocok dengan daftar resmi.
     */
    public function normalizeMemberName(string $inputName): string
    {
        $cleanInput = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $inputName));
        $inputNoTitle = strtoupper(preg_replace('/[^A-Z0-9]/i', '', explode(',', $inputName)[0]));

        // 1. Pencarian presisi tinggi (hanya membuang tanda baca dan spasi)
        foreach (self::$teamMembers as $members) {
            foreach ($members as $official) {
                $cleanOfficial = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $official));
                if ($cleanInput === $cleanOfficial) {
                    return $official; // Kembalikan format nama resmi
                }
            }
        }
        
        // 2. Pencarian tanpa gelar (mengambil kata sebelum koma)
        if (strlen($inputNoTitle) > 3) {
            foreach (self::$teamMembers as $members) {
                foreach ($members as $official) {
                    $officialNoTitle = strtoupper(preg_replace('/[^A-Z0-9]/i', '', explode(',', $official)[0]));
                    if ($inputNoTitle === $officialNoTitle) {
                        return $official;
                    }
                }
            }
        }

        // 3. Pencarian toleransi typo ringan (misal huruf I dan Y, ST dan S.T.)
        $bestMatch = null;
        $highestPercent = 0;
        foreach (self::$teamMembers as $members) {
            foreach ($members as $official) {
                $cleanOfficial = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $official));
                similar_text($cleanInput, $cleanOfficial, $percent);
                if ($percent > 85 && $percent > $highestPercent) {
                    $highestPercent = $percent;
                    $bestMatch = $official;
                }
            }
        }

        if ($bestMatch) {
            return $bestMatch;
        }

        return strtoupper($inputName);
    }

    /**
     * Ambil daftar anggota tim dari array $teamMembers.
     *
     * @return string[]  Daftar nama anggota (sudah trim)
     */
    public static function getTeamMembers(string $tim): array
    {
        return self::$teamMembers[$tim] ?? [];
    }
}
