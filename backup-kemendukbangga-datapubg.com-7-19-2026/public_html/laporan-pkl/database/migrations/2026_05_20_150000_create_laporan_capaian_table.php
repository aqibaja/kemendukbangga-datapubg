<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_capaian', function (Blueprint $table) {
            $table->id();
            $table->string('tipe');       // pengendalian_lapangan, capaian_program, elsimil
            $table->string('judul');
            $table->integer('bulan');      // 1-12
            $table->integer('tahun');      // e.g. 2026
            $table->json('data');
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tipe', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_capaian');
    }
};
