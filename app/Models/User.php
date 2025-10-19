<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // pakai tabel lama
    protected $primaryKey = 'id';
    public $timestamps = false; // kalau tabel users kamu ga ada created_at & updated_at

    protected $fillable = [
        'name',
        'email',
        'password',
        'roleId',
        'positionId',
    ];

    protected $hidden = [
        'password',
    ];
}
