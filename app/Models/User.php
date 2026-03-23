<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'nip',
        'password',
        'roleId',
        'positionId',
    ];

    protected $hidden = [
        'password',
    ];

    public function mahasiswa()
    {
        return $this->hasOne(\App\Models\Mahasiswa::class, 'userId');
    }

    public function koordinator()
    {
        return $this->hasOne(\App\Models\Koordinator::class, 'userId');
    }

    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        if (Str::startsWith($value, ['$2y$', '$argon2'])) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }
}
