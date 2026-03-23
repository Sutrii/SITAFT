<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koordinator extends Model
{
    use HasFactory;

    protected $table = 'koordinator';
    protected $primaryKey = 'id';
    public $timestamps = false; // Based on other models that use false

    protected $fillable = [
        'userId',
        'name',
        'nip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
