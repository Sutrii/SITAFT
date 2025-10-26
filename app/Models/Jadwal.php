<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'skripsiId',
        'mahasiswaId',
        'dosenId1',
        'dosenId2',
        'jadwal_seminar',
        'status',
    ];

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswaId');
    }

    public function skripsi() {
        return $this->belongsTo(Skripsi::class, 'skripsiId');
    }

    public function dosen1() {
        return $this->belongsTo(Dosen::class, 'dosenId1');
    }

    public function dosen2() {
        return $this->belongsTo(Dosen::class, 'dosenId2');
    }
}
