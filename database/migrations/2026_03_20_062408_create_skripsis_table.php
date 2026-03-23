<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('skripsi')) {
            Schema::create('skripsi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_mahasiswa');
                $table->string('judul_skripsi');
                $table->foreignId('dosen_pembimbing_1')->nullable()->constrained('dosen')->onDelete('set null');
                $table->foreignId('dosen_pembimbing_2')->nullable()->constrained('dosen')->onDelete('set null');
                $table->string('bidang')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('skripsi');
    }
};
