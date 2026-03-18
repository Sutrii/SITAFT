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
        Schema::create('pendaftaran_seminars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('skripsi_id')->constrained('skripsi')->onDelete('cascade');
            $table->enum('jenis_seminar', ['seminar_proposal', 'seminar_hasil', 'sidang_akhir']);
            $table->enum('status', ['pending', 'revisi', 'disetujui', 'ditolak'])->default('pending');
            $table->string('file_persyaratan', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_seminars');
    }
};
