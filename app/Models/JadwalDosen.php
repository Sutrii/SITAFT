<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDosen extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dosens';
    protected $primaryKey = 'id';

    protected $fillable = [
        'userId',
        'hari',
        'jam',
        'status',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'userId', 'userId');
    }
}
