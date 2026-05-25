<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanCapaian extends Model
{
    protected $table = 'laporan_capaian';

    protected $fillable = [
        'tipe',
        'judul',
        'bulan',
        'tahun',
        'data',
        'dibuat_oleh',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public static function namaBulan($bulan)
    {
        $nama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $nama[$bulan] ?? '';
    }

    public static function labelTipe($tipe)
    {
        $labels = [
            'pengendalian_lapangan' => 'Capaian Pengendalian Lapangan',
            'capaian_program'      => 'Laporan Capaian Program',
            'elsimil'              => 'Laporan Capaian ELSIMIL',
        ];
        return $labels[$tipe] ?? $tipe;
    }
}
