<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skripsi extends Model
{
    use HasFactory;

    protected $table = 'skripsi';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_mahasiswa',
        'judul_skripsi',
        'bidang',
        'dosen_pembimbing_1',
        'dosen_pembimbing_2',
    ];

    public function dosen1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_1');
    }

    public function dosen2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2');
    }
}
