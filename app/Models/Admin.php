<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'transfer_admin';

    protected $fillable = [
        'usuario',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function isAdmin()
{
    return true; // Puedes personalizar esta lógica si tienes más roles en el futuro
}




}
