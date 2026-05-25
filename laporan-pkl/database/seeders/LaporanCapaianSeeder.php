<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\PresentationLink;
use App\Models\LaporanCapaian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LaporanCapaianSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles if not exist
        $adminRole = Role::firstOrCreate(['id' => 1], ['nama_role' => 'admin_utama']);
        $userRole = Role::firstOrCreate(['id' => 2], ['nama_role' => 'user']);

        // 2. Seed Users if not exist
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'id' => 14,
                'nama' => 'Admin Utama',
                'password' => Hash::make('admin123'),
                'id_role' => 1,
            ]
        );

        $user = User::firstOrCreate(
            ['username' => 'rian123'],
            [
                'id' => 15,
                'nama' => 'Rian Indra Pratama',
                'password' => Hash::make('rian123'),
                'id_role' => 2,
            ]
        );

        // 3. Seed Presentation Links if not exist
        PresentationLink::firstOrCreate(
            ['key' => 'apel_senin'],
            [
                'name' => 'Presensi Apel Senin',
                'url' => 'https://s.id/APELYOK',
            ]
        );

        PresentationLink::firstOrCreate(
            ['key' => 'zoom_presensi'],
            [
                'name' => 'Presensi Zoom',
                'url' => 'https://forms.gle/XkWbaiBoRmqTBAd9A',
            ]
        );

        // 4. Seed Laporan Capaian April 2026 - Pengendalian Lapangan
        LaporanCapaian::updateOrCreate(
            ['tipe' => 'pengendalian_lapangan', 'bulan' => 4, 'tahun' => 2026],
            [
                'judul' => 'Capaian Pengendalian Lapangan KemendukBangga BKKBN Prov Aceh Bulan April Tahun 2026',
                'dibuat_oleh' => $admin->id,
                'data' => [
                    'bkb' => [
                        'cakupan_laporan' => [
                            'ada' => 5682,
                            'lapor' => 5580,
                            'persentase' => 98.20,
                        ],
                        'anak_hadir_kka' => [
                            'hadir' => 172580,
                            'menggunakan_kka' => 168963,
                            'persentase' => 97.90,
                        ],
                        'keluarga_ikut_bkb' => [
                            'target' => 188201,
                            'capaian' => 147653,
                            'persentase' => 78.45,
                        ],
                        'pembinaan_baduta' => [
                            'target' => 225307,
                            'capaian' => 117404,
                            'persentase' => 42.11,
                        ],
                    ],
                    'bkr' => [
                        'cakupan_laporan' => [
                            'ada' => 3814,
                            'lapor' => 3736,
                            'persentase' => 97.95,
                        ],
                        'anggota_hadir' => [
                            'jumlah' => 145495,
                            'hadir' => 127276,
                            'persentase' => 87.48,
                        ],
                    ],
                    'bkl' => [
                        'cakupan_laporan' => [
                            'ada' => 3882,
                            'lapor' => 3806,
                            'persentase' => 98.04,
                        ],
                        'anggota_hadir' => [
                            'jumlah_keluarga' => 81787,
                            'keluarga_hadir' => 72953,
                            'lansia_hadir' => 37813,
                            'total_hadir' => 110766,
                            'persentase' => 135.43,
                        ],
                    ],
                    'pikr' => [
                        'cakupan_laporan' => [
                            'ada' => 2025,
                            'lapor' => 1936,
                            'persentase' => 95.60,
                        ],
                        'anggota_hadir' => [
                            'jumlah_remaja' => 447739,
                            'target' => 44778,
                            'hadir' => 61697,
                            'persentase' => 137.78,
                        ],
                    ],
                    'uppka' => [
                        'cakupan_laporan' => [
                            'ada' => 1560,
                            'lapor' => 1524,
                            'persentase' => 97.69,
                        ],
                        'anggota_hadir' => [
                            'jumlah_keluarga' => 19725,
                            'hadir' => 17616,
                            'persentase' => 89.31,
                        ],
                    ],
                    'ppks' => [
                        'cakupan_laporan' => [
                            'ada' => 285,
                            'lapor' => 274,
                            'persentase' => 96.14,
                        ],
                    ],
                ]
            ]
        );

        // 5. Seed Laporan Capaian April 2026 - Capaian Program
        LaporanCapaian::updateOrCreate(
            ['tipe' => 'capaian_program', 'bulan' => 4, 'tahun' => 2026],
            [
                'judul' => 'Laporan Capaian Program Kemendukbangga / BKKBN Prov Aceh April Tahun 2026',
                'dibuat_oleh' => $admin->id,
                'data' => [
                    'cakupan_fasyankes' => [
                        'pemerintah'  => ['ada' => 451, 'lapor' => 442, 'persentase' => 98.0],
                        'jaringan'    => ['ada' => 1719, 'lapor' => 1711, 'persentase' => 99.53],
                        'swasta'      => ['ada' => 104, 'lapor' => 102, 'persentase' => 98.08],
                        'pmb_setara'  => ['ada' => 131, 'lapor' => 125, 'persentase' => 95.42],
                        'pmb_jejaring' => ['ada' => 230, 'lapor' => 225, 'persentase' => 97.83],
                    ],
                    'stock_opname' => [
                        'gudang_provinsi'  => ['ada' => 1, 'laporan' => 1, 'persentase' => 100.0],
                        'gudang_kabkota'   => ['ada' => 23, 'laporan' => 23, 'persentase' => 100.0],
                        'gudang_fasyankes' => ['ada' => 653, 'laporan' => 597, 'persentase' => 91.42],
                    ],
                    'kb_baru' => [
                        'pb'                  => ['ppm' => 101342, 'capaian' => 23538, 'persentase' => 23.23],
                        'pb_pasca_persalinan' => ['ppm' => 65118, 'capaian' => 14618, 'persentase' => 22.45],
                        'pb_mkjp'             => ['ppm' => 22543, 'capaian' => 5800, 'persentase' => 25.62],
                        'pb_non_mkjp'         => ['ppm' => 78799, 'capaian' => 17738, 'persentase' => 22.51],
                    ],
                    'kb_aktif' => [
                        'pa_mkjp'         => ['ppm' => 92100, 'capaian' => 78164, 'persentase' => 84.87],
                        'pa_non_mkjp'     => ['ppm' => 409884, 'capaian' => 380617, 'persentase' => 92.86],
                        'pa_modern'       => ['ppm' => 501984, 'capaian' => 458781, 'persentase' => 91.39],
                        'pa_tradisional'  => 6204,
                        'pa_keseluruhan'  => 464985,
                    ],
                    'mcpr_unmet' => [
                        'mcpr'       => ['pus' => 804901, 'pa_modern' => 458781, 'persentase' => 57.07],
                        'unmet_need' => ['pus' => 804792, 'un' => 34313, 'persentase' => 4.27],
                    ],
                ]
            ]
        );

        // 6. Seed Laporan Capaian April 2026 - ELSIMIL
        LaporanCapaian::updateOrCreate(
            ['tipe' => 'elsimil', 'bulan' => 4, 'tahun' => 2026],
            [
                'judul' => 'Laporan Capaian ELSIMIL Kemendukbangga BKKBN Prov Aceh Hingga April 2026',
                'dibuat_oleh' => $admin->id,
                'data' => [
                    'catin' => [
                        '1' => 1577,
                        '2' => 1462,
                        '3' => 1911,
                        '4' => 4094,
                    ],
                    'bumil' => [
                        '1' => 2513,
                        '2' => 4127,
                        '3' => 5503,
                        '4' => 12393,
                    ],
                ]
            ]
        );
    }
}
