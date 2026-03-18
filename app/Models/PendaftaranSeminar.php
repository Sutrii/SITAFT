<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranSeminar extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_seminar';

    protected $fillable = [
        'mahasiswa_id',
        'skripsi_id',
        'nomor_registrasi',
        'no_hp',
        'jenis_seminar',
        'status',
        'file_persyaratan',
        'catatan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }
}
