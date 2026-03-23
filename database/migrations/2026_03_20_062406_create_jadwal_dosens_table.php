<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jadwal_dosens')) {
            Schema::create('jadwal_dosens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('userId')->constrained('users')->onDelete('cascade');
                $table->string('hari');
                $table->string('jam');
                $table->string('status');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_dosens');
    }
};
