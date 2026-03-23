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
        Schema::create('koordinator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('nip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koordinator');
    }
};
