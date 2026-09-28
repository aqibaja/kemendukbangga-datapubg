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
        Schema::create('voting_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_popup_active')->default(false);
            $table->boolean('is_result_visible')->default(false);
            $table->string('golongan_1_name')->default('Golongan II (Pengatur)');
            $table->string('golongan_2_name')->default('Golongan III (Penata)');
            $table->string('golongan_3_name')->default('Golongan IV (Pembina)');
            $table->timestamps();
        });

        Schema::create('voting_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->tinyInteger('golongan')->comment('1=Gol.II Pengatur, 2=Gol.III Penata, 3=Gol.IV Pembina');
            $table->string('foto')->nullable();
            $table->string('unsur')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('pkb_employees', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unsur')->nullable();
            $table->timestamps();
        });

        Schema::create('voting_votes', function (Blueprint $table) {
            $table->id();
            $table->string('voter_name');
            $table->enum('voter_type', ['perwakilan', 'pkb']);
            $table->foreignId('voter_employee_id')->nullable()->constrained('employees')->onDelete('cascade');
            $table->foreignId('voter_pkb_id')->nullable()->constrained('pkb_employees')->onDelete('cascade');
            $table->foreignId('candidate_golongan_1')->constrained('voting_candidates')->onDelete('cascade');
            $table->foreignId('candidate_golongan_2')->constrained('voting_candidates')->onDelete('cascade');
            $table->foreignId('candidate_golongan_3')->constrained('voting_candidates')->onDelete('cascade');
            $table->string('foto_selfie')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_cookie_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_votes');
        Schema::dropIfExists('pkb_employees');
        Schema::dropIfExists('voting_candidates');
        Schema::dropIfExists('voting_settings');
    }
};
