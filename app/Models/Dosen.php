<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen'; // tabel kamu bukan plural
    protected $primaryKey = 'id';
    public $timestamps = true; // karena kita punya created_at dan updated_at

    protected $fillable = [
        'userId',
        'name',
        'nik',
        'bidang',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
