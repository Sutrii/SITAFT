<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('mahasiswa')) {
            Schema::create('mahasiswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('userId')->constrained('users')->onDelete('cascade');
                $table->string('name');
                $table->string('nim');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
