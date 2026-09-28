<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PkbEmployee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NipDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Import NIP data from PDF-extracted JSON files into employees and pkb_employees tables.
     */
    public function run(): void
    {
        // ============================================================
        // 1. Pegawai Perwakilan Provinsi (employees)
        // ============================================================
        $perwakilanData = json_decode(
            file_get_contents(database_path('seeders/data/perwakilan_nip.json')),
            true
        );

        $this->command->info('Importing ' . count($perwakilanData) . ' pegawai perwakilan...');
        $matched = 0;
        $created = 0;

        foreach ($perwakilanData as $item) {
            $nip  = $item['nip'];
            $nama = $item['nama'];

            // Try to find existing employee by name (case-insensitive, trimmed)
            $employee = Employee::whereRaw('TRIM(UPPER(nama)) = ?', [trim(strtoupper($nama))])->first();

            if ($employee) {
                $employee->update(['nip' => $nip]);
                $matched++;
            } else {
                // Create new record if not found
                Employee::updateOrCreate(
                    ['nip' => $nip],
                    ['nama' => $nama, 'nip' => $nip]
                );
                $created++;
            }
        }

        $this->command->info("  Matched & updated: $matched");
        $this->command->info("  Newly created: $created");

        // ============================================================
        // 2. PKB Employees (pkb_employees)
        // ============================================================
        $pkbData = json_decode(
            file_get_contents(database_path('seeders/data/pkb_nip.json')),
            true
        );

        $this->command->info('Importing ' . count($pkbData) . ' PKB employees...');
        $matchedPkb = 0;
        $createdPkb = 0;

        foreach ($pkbData as $item) {
            $nip  = $item['nip'];
            $nama = $item['nama'];

            // Try to find by name first
            $pkb = PkbEmployee::whereRaw('TRIM(UPPER(nama)) = ?', [trim(strtoupper($nama))])->first();

            if ($pkb) {
                $pkb->update(['nip' => $nip]);
                $matchedPkb++;
            } else {
                PkbEmployee::updateOrCreate(
                    ['nip' => $nip],
                    ['nama' => $nama, 'nip' => $nip]
                );
                $createdPkb++;
            }
        }

        $this->command->info("  Matched & updated: $matchedPkb");
        $this->command->info("  Newly created: $createdPkb");

        $this->command->info('NIP import selesai!');
    }
}
