<?php
/**
 * Script untuk mengupdate Tim Kerja (unsur) pegawai di database.
 * Cara pakai di server:
 * 1. Upload file ini ke folder root laravel (sejajar dengan .env).
 * 2. Jalankan via terminal/SSH: php update_tim.php
 * 3. Hapus file ini jika sudah selesai.
 */

if (php_sapi_name() !== 'cli') {
    echo '<pre>';
}

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Services\ApelSeninService;

$membersMap = ApelSeninService::$teamMembers;

$count = 0;
$notFound = [];

echo "Mulai sinkronisasi Tim Kerja pegawai...\n";

foreach ($membersMap as $team => $members) {
    foreach ($members as $memberName) {
        $cleanName = trim(strtoupper($memberName));
        $emp = Employee::where('nama', $cleanName)->first();
        
        if (!$emp) {
            $emp = Employee::where('nama', rtrim($cleanName, '.'))->first();
        }

        if (!$emp) {
            $baseName = trim(str_ireplace(['dr. ', 'DR. ', 'dr '], '', explode(',', $cleanName)[0]));
            $emp = Employee::where('nama', 'LIKE', '%' . $baseName . '%')->first();
        }

        if ($emp) {
            $emp->unsur = $team;
            $emp->save();
            $count++;
            // echo "Updated {$emp->nama} -> $team\n"; // Uncomment untuk melihat log per orang
        } else {
            $notFound[] = $memberName;
        }
    }
}

echo "Selesai! Total data pegawai yang berhasil di-update: " . $count . "\n";
if (count($notFound) > 0) {
    echo "\nBerikut adalah nama-nama yang gagal ditemukan di database (karena ejaan/singkatan berbeda atau pegawai baru):\n";
    foreach($notFound as $n) {
        echo "- " . $n . "\n";
    }
    echo "\nAnda dapat mengupdate sisa nama di atas secara manual via Halaman Admin > Edit Pegawai.\n";
}
