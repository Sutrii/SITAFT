<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skripsi extends Model
{
    use HasFactory;

    protected $table = 'skripsi'; // pakai tabel lama
    protected $primaryKey = 'id';
    public $timestamps = true; // aktifin biar created_at & updated_at otomatis

    protected $fillable = [
        'nama_mahasiswa',
        'judul_skripsi',
        'bidang',
    ];
}
