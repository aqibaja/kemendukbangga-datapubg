<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom nip ke employees
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nip', 30)->nullable()->unique()->after('nama');
        });

        // Tambah kolom nip ke pkb_employees
        Schema::table('pkb_employees', function (Blueprint $table) {
            $table->string('nip', 30)->nullable()->unique()->after('nama');
        });

        // Hapus kolom foto_selfie dari voting_votes (tidak diperlukan lagi)
        Schema::table('voting_votes', function (Blueprint $table) {
            $table->dropColumn('foto_selfie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voting_votes', function (Blueprint $table) {
            $table->string('foto_selfie')->nullable();
        });

        Schema::table('pkb_employees', function (Blueprint $table) {
            $table->dropColumn('nip');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('nip');
        });
    }
};
