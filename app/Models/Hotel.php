<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hotel extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_hotel';
    protected $primaryKey = 'id_hotel';

    public $timestamps = false;

    protected $fillable = [
        'id_zona',
        'comision',
        'usuario',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
