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
        'PERENCANAAN DAN KEUANGAN' => [
            'slug'    => 'renkeu',
            'csv_key' => 'RANKEU',
            'ketua'   => 'Ketua Tim Perencanaan & Keuangan',
        ],
        'KELUARGA SEJAHTERA DAN PEMBANGUNAN KELUARGA' => [
            'slug'    => 'kspk',
            'csv_key' => 'KKPS',
            'ketua'   => 'Ketua Tim Keluarga Sejahtera',
        ],
        'UMUM, PELAYANAN PUBLIK DAN PENGELOLAAN BMN' => [
            'slug'    => 'umum',
            'csv_key' => 'UMUM',
            'ketua'   => 'Ketua Tim Umum & Pelayanan Publik',
        ],
        'PENGENDALIAN PENDUDUK DAN JAKSTRA' => [
            'slug'    => 'dalduk',
            'csv_key' => 'DALDUK',
            'ketua'   => 'Ketua Tim Pengendalian Penduduk',
        ],
        'HUBUNGAN MASYARAKAT DAN INFORMASI PUBLIK' => [
            'slug'    => 'humasip',
            'csv_key' => 'HUMASIP',
            'ketua'   => 'Ketua Tim Humas & Infopublik',
        ],
        'PENGGERAKKAN DAN PERAN SERTA MASYARAKAT' => [
            'slug'    => 'permas',
            'csv_key' => 'PERMAS',
            'ketua'   => 'Ketua Tim Penggerakkan & PSM',
        ],
        'ZI WBK/WBBM DAN SPIP' => [
            'slug'    => 'ziwbk',
            'csv_key' => 'ZIWBK',
            'ketua'   => 'Ketua Tim ZI WBK/WBBM & SPIP',
        ],
        'HUKUM DAN KEPEGAWAIAN' => [
            'slug'    => 'hupeg',
            'csv_key' => 'HUPEG',
            'ketua'   => 'Ketua Tim Hukum & Kepegawaian',
        ],
        'PELATIHAN DAN PENGEMBANGAN KOMPETENSI' => [
            'slug'    => 'latkom',
            'csv_key' => 'LATKOM',
            'ketua'   => 'Ketua Tim Pelatihan & Pengembangan',
        ],
        'BINA KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI' => [
            'slug'    => 'kbkr',
            'csv_key' => 'KBKR',
            'ketua'   => 'Ketua Tim Bina KB & Kesehatan Reproduksi',
        ],
        'PELAPORAN STATISTIK DAN PENGELOLAAN TIK' => [
            'slug'    => 'datin',
            'csv_key' => 'DATIN',
            'ketua'   => 'Ketua Tim Pelaporan & TIK',
        ],
        'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH' => [
            'slug'    => null,       // belum ada foto, gunakan avatar
            'csv_key' => null,
            'ketua'   => 'Kepala Perwakilan BKKBN Aceh',
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
            
            // Deduplikasi harus menggunakan $itemDate agar satu orang bisa dihitung lebih dari 1x di hari yang berbeda
            $key  = $itemDate . '|' . $tim . '|' . $nama;

            if (isset($seenKeys[$key])) continue;
            $seenKeys[$key] = true;

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

    /**
     * Normalisasi nama tim agar pencocokan fleksibel (spasi, trailing space, dll).
     */
    public function normalizeTeamName(string $name): string
    {
        $normalized = trim(strtoupper(preg_replace('/\s+/', ' ', $name)));
        
        // Gabungkan variasi nama Perwakilan Aceh menjadi satu
        if (str_contains($normalized, 'PERWAKILAN BKKBN PROVINSI ACEH')) {
            return 'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH';
        }
        
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
     * Ambil daftar anggota tim dari file CSV di folder foto-katim.
     * File pattern: public/image/foto-katim/APEL SENIN_{CSV_KEY}_Table.csv
     *
     * @return string[]  Daftar nama anggota (sudah trim, tanpa header)
     */
    public static function getMembersFromCsv(string $tim): array
    {
        // Tim khusus yang tidak punya CSV tetapi punya anggota tetap
        if ($tim === 'ATASAN PERWAKILAN KEMENDUKBANGGA / BKKBN PROVINSI ACEH') {
            return ['SAFRINA SALIM', 'IHYA, S.E., M.M.'];
        }

        $csvKey = self::$timKerja[$tim]['csv_key'] ?? null;
        if (!$csvKey) return [];

        $path = public_path("image/foto-katim/APEL SENIN_{$csvKey}_Table.csv");
        if (!file_exists($path)) return [];

        $lines   = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $members = [];

        foreach ($lines as $i => $line) {
            if ($i === 0) continue;  // skip header "NAMA"
            $name = trim($line, " \t\r\n\"'");
            if ($name !== '') $members[] = $name;
        }

        return $members;
    }
}
