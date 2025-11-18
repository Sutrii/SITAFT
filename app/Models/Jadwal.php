<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'skripsiId',
        'mahasiswaId',
        'dosenId1',
        'dosenId2',
        'jadwal_seminar',
        'jadwal_seminar_selesai',
        'ruang',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswaId');
    }

    public function skripsi()
    {
        return $this->belongsTo(\App\Models\Skripsi::class, 'skripsiId');
    }

    public function dosen1()
    {
        return $this->belongsTo(\App\Models\Dosen::class, 'dosenId1');
    }

    public function dosen2()
    {
        return $this->belongsTo(\App\Models\Dosen::class, 'dosenId2');
    }
}
