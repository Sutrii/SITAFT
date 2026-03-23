<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jadwal')) {
            Schema::create('jadwal', function (Blueprint $table) {
                $table->id();
                $table->foreignId('skripsiId')->constrained('skripsi')->onDelete('cascade');
                $table->foreignId('mahasiswaId')->constrained('mahasiswa')->onDelete('cascade');
                $table->foreignId('dosenId1')->nullable()->constrained('dosen')->onDelete('set null');
                $table->foreignId('dosenId2')->nullable()->constrained('dosen')->onDelete('set null');
                $table->dateTime('jadwal_seminar');
                $table->dateTime('jadwal_seminar_selesai');
                $table->string('ruang')->nullable();
                $table->string('status');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
