<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dosen')) {
            Schema::create('dosen', function (Blueprint $table) {
                $table->id();
                $table->foreignId('userId')->constrained('users')->onDelete('cascade');
                $table->string('name');
                $table->string('nik')->nullable();
                $table->string('bidang')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
