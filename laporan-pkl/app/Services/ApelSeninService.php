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
            'slug'    => null,
            'ketua'   => 'Kepala Perwakilan BKKBN Aceh',
        ],
    ];

    public static array $teamMembers = [
        'PENGELOLAAN KEPENDUDUKAN' => [
            'ZULKIFLI, S.E., M.A.P.', 'HILDAN MAWARDI, S.Sos., M.Si.', 'ALFIN KHAIRI, M.Sc.', 'DEDI SASTRADI, S.P., M.EMD.', 'SRIWAHYUNI, S.E.', 'CITRA MULIANI, S.K., Pm.', 'NUR AFFIFAH PASARIBU, S.K.M.', 'KIKY RIZKY ANANDA, S.K.M.', 'AHMAD KHALIDI.'
        ],
        'KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI' => [
            'dr. CUT LIZA FEBTYA.', 'ROSLINDAWATI, S.K.M., M.Si.', 'dr. NOLIASARI.', 'KHAIRUL IBAD, S.E., M.Si.', 'ZULHIKMAH SAHPUTRI, S.T.', 'REFA ALAYDRUS, S.Farm., Apt.', 'SURYADI SAPUTRA, A.Md.Ak.', 'MUTIA ANDRIANI, S.Stat.', 'HAYATUL KHOLIFAH PUTRI, S.K.M.', 'NONI YUSVALIANA, S.K.M.', 'NURHABIBAH PRASETYA, S.K.M.', 'KESSA IKHWANDA, S.K.M.', 'ARIUS GUSNANDAR, S.E.', 'HUDIATUL AULI.'
        ],
        'PEMBANGUNAN KELUARGA' => [
            'DINA ASTITA, S.Ag., M.Si.', 'NURISMI, S.E., M.Sc.', 'RIDHA ILHAM, S.T., M.Si.', 'PUSPITA PALUPI, S.Psi.', 'NURHAYATI, S.Pd.', 'WINDA NURI ADINDA, S.Pd.', 'SITI KHAIRIYANI, S.I.Kom.', 'NIHRASYIAH, S.Psi.', 'FITRIA ISMAYANTI, S.Tr.S.Sos.', 'dr. AFRIDA, MAPS.', 'HAYATUR RAHMI, M.Sc.', 'FAISAL.', 'ASTRI, S.Sos.', 'SAIFUDDIN.', 'NURLAILI.', 'WAHYU.', 'MUCHTAR LUBIS.'
        ],
        'PENGGERAKKAN MASYARAKAT DAN PENGELOLAAN LINI LAPANGAN' => [
            'EFIYANTI, S.H., M.A.P.', 'ERIENA SARTIKA AYU, S.Psi., MAPS.', 'AL KINDI HARLEY, S.Sos., M.A.', 'FARIDAH, S.E., M.M.', 'IRMA DIMYATI, S.E., M.Si.', 'dr. MUHAMMAD JABARI, M.A.P.', 'ELA ISWARI, S.E.', 'HADIANA QANITA, S.Stat.', 'RINA KHAIRUNA NASUTION, S.K.M.', 'SANIAH.', 'MURTADA.', 'IRWANSYAH.'
        ],
        'PERENCANAAN DAN KEUANGAN' => [
            'HUSNI THAMRIN, S.E., M.M.', 'DINI RAMADINI.', 'NITA AFRIDA, S.E.', 'TAMLIKHA, S.E., M.A.P.', 'ABDI DZIL IKRAM, S.E.', 'NOVIA PUCHA AUDYA, S.Stat.', 'MITA ARLINI, S.E., M.Si.', 'NASRUDDIN, S.E.', 'ISWANI, S.ST.', 'AGUS MUNANDAR, S.E.', 'SUCI WULANDARI T., S.K.M.', 'RINA MAGHFIRAH, A.Md.', 'RIKA ANGGRAINI, S.E.', 'MUTTAQIN, A.Md.', 'NOVI YANTI.', 'FARIDAH IHSAN.', 'KUSUMA.', 'MAHLIA.', 'FARDIANTO.'
        ],
        'PENGELOLAAN SDM, ORGANISASI, DAN HUKUM' => [
            'IRA MEUTYA, S.Psi.', 'MADIAN, S.E.', 'ARI MARDANA, S.Sos.', 'SARI FITRIANI, S.Psi.', 'SRI WAHYUNI, S.K.M.', 'ILHAM SYAHPUTRA, S.Sos., M.Si.', 'CUTTI HAJAR, A.Md.MSDM.', 'MAUKIYUDDIN, A.Md.', 'ZULFIKAR, S.E.', 'MUKHYAR, S.Sos.', 'ROMI FAHRI, S.Kom.', 'MUNA MAULIDA, M.Pd.', 'RUHUL BAWADI, S.E.', 'ISHANI YUNITA, S.Sos.I.', 'TARI INDAH PERTIWI.'
        ],
        'PENGELOLAAN MANAJEMEN KINERJA' => [
            'FENNY SILFIA PUTRI, S.E., M.Si.', 'JUNI MAHZUR, S.Sos., M.Si.', 'FAHMI, S.E.', 'EARLY PROPHITA, S.Psi., M.M.', 'AMRIZAL, S.H., M.Hum.', 'RIFKI KHAIRUL AMRI, S.E.', 'MAYA ZATIL AQMAR, S.E.', 'FITA RONAYA, S.H.', 'SURYA RIZKY, S.I.Kom.', 'ARIFA ZAHRA, S.IP.', 'M. ZAIN.'
        ],
        'UMUM, HUMAS, DAN PROTOKOL' => [
            'ZULFADHLI, S.E.', 'RAHMATSYAH, S.E.', 'EDDY MUNAWAR, S.T., M.Si.', 'SRI RAIHAN, S.Pd., M.A.P.', 'NIKMATUL AKBAR, S.Ak., M.A.P.', 'BAHRI ASMAWI, S.E.', 'CUT ROSA MINORA, S.E.', 'HANIFAH, S.E.', 'NURMIATI, S.E.', 'ZULFIKAR, S.E.', 'AZHAR, S.E.', 'IRMAWATI, S.Farm.', 'ZARRA SILVIA BALQIS, S.Sos.', 'FREDY, S.Pd.', 'JOPI DIAN SAPUTRA, S.E., S.Sos.', 'RIZQA ANDRIANI LUBIS, S.I.Kom.', 'FAHRI MARUF, S.A.P.', 'MAULIDI.', 'WIDIA GUSTIASARI, S.K.M.', 'ALISSA SHAFIRA AYUWI, S.IP.', 'NURHAFIZAH, A.Md.', 'NOVI MAULINA, A.Md.', 'AZHARI.', 'MURTAZA.', 'AIDIL FITARSYAH.', 'RONA VITRYA.', 'MUHAMMAD IQBAL.', 'YOVANDI FEBRIANSYAH.', 'RATNA YUSA.', 'DEWI SUNARTI.', 'RIO HUSNADI.', 'MUHAMMAD T.M. NASIR.', 'DENI NURFANDI.', 'KHALIS.', 'ARMIADI.', 'AGUS MIDARLI.', 'RIZAL SYAHPUTRA.'
        ],
        'DATA DAN INFORMASI' => [
            'BAYU PRAWIRA, S.Kom.', 'NANDA MASITHAH, S.K.M.', 'WAHYU RIZKY, S.T.', 'INAS SALSABILA, S.Stat.', 'M. HUSNUL AQIB, S.T.', 'ROSNA.'
        ],
        'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH' => [
            'SAFRINA SALIM', 'IHYA, S.E., M.M.'
        ],
    ];

    /**
     * Ambil semua data presensi dari Google Apps Script (dengan cache).
     */
    public function getAllData(): array
    {
        $apiUrl = env('APEL_SENIN_SCRIPT_URL');

        if (empty($apiUrl)) {
            Log::error('APEL_SENIN_SCRIPT_URL is not set in .env');
            return [];
        }

        $cacheKey      = 'apel_senin_data_gsheet';
        $cacheDuration = 60 * 60 * 6; // 6 jam (dalam detik)

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $rows = [];

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);
        set_time_limit(120);

        try {
            $response = Http::timeout(120)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                if (is_array($data)) {
                    foreach ($data as $row) {
                        if (empty($row['nama'])) continue;

                        $tim = trim(strtoupper($row['tim_kerja'] ?? ''));
                        if (!$tim) continue;

                        // Normalisasi nama tim agar cocok dengan key di $timKerja
                        $tim = $this->normalizeTeamName($tim);

                        $nama  = trim($row['nama'] ?? '');
                        if (!$nama) continue;
                        $nama = $this->normalizeMemberName($nama);

                        $rows[] = [
                            'timestamp'    => $row['timestamp'] ?? null,
                            'email'        => $row['email']     ?? '',
                            'tim_kerja'    => $tim,
                            'nama'         => $nama,
                            'ikut_apel'    => $row['ikut_apel']  ?? 'Ya',
                            'keterangan'   => $row['keterangan'] ?? '',
                        ];
                    }
                } else {
                    Log::error('ApelSenin: Invalid JSON format. Body: ' . substr($response->body(), 0, 500));
                }
            } else {
                Log::error('ApelSenin: Failed to fetch. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('ApelSenin Exception: ' . $e->getMessage());
        }

        if (!empty($rows)) {
            Cache::put($cacheKey, $rows, $cacheDuration);
        }

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
        $inputNoTitle = strtoupper(preg_replace('/[^A-Z0-9]/i', '', explode(',', $inputName)[0]));
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
