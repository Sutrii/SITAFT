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
        Schema::table('skripsi', function (Blueprint $table) {
            $table->foreignId('dosen_penguji_1')->nullable()->constrained('dosen')->onDelete('set null');
            $table->foreignId('dosen_penguji_2')->nullable()->constrained('dosen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi', function (Blueprint $table) {
            $table->dropForeign(['dosen_penguji_1']);
            $table->dropForeign(['dosen_penguji_2']);
            $table->dropColumn(['dosen_penguji_1', 'dosen_penguji_2']);
        });
    }
};
