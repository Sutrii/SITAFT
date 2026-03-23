<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            if (!Schema::hasTable('mahasiswa')) {
                Schema::create('mahasiswa', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('userId');
                    $table->string('name');
                    $table->string('nim');
                    $table->timestamps();
                });
            }
            if (!Schema::hasTable('dosen')) {
                Schema::create('dosen', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('userId');
                    $table->string('name');
                    $table->string('nik')->nullable();
                    $table->string('bidang')->nullable();
                    $table->timestamps();
                });
            }
            if (!Schema::hasTable('koordinator')) {
                Schema::create('koordinator', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('userId');
                    $table->string('name');
                    $table->string('nip')->nullable();
                });
            }
            if (!Schema::hasTable('skripsi')) {
                Schema::create('skripsi', function (Blueprint $table) {
                    $table->id();
                    $table->string('nama_mahasiswa');
                    $table->string('judul_skripsi');
                    $table->unsignedBigInteger('dosen_pembimbing_1')->nullable();
                    $table->unsignedBigInteger('dosen_pembimbing_2')->nullable();
                    $table->string('bidang')->nullable();
                    $table->timestamps();
                });
            }
            if (!Schema::hasTable('jadwal')) {
                Schema::create('jadwal', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('skripsiId');
                    $table->unsignedBigInteger('mahasiswaId');
                    $table->unsignedBigInteger('dosenId1')->nullable();
                    $table->unsignedBigInteger('dosenId2')->nullable();
                    $table->dateTime('jadwal_seminar');
                    $table->dateTime('jadwal_seminar_selesai');
                    $table->string('ruang')->nullable();
                    $table->string('status');
                    $table->timestamps();
                });
            }
            if (!Schema::hasTable('jadwal_dosens')) {
                Schema::create('jadwal_dosens', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('userId');
                    $table->string('hari');
                    $table->string('jam');
                    $table->string('status');
                    $table->timestamps();
                });
            }
        }
    }
}
