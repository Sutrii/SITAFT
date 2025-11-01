<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'userId',
        'name',
        'nik',
        'bidang',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'userId');
    }
}
