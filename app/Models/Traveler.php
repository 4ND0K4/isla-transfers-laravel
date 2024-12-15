<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Traveler extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_viajeros';
    protected $primaryKey = 'id_viajero';

    public $timestamps = false; // Deshabilitar timestamps (created_at y updated_at)

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'direccion',
        'codigo_postal',
        'ciudad',
        'pais',
        'email',
        'password'
    ];

    // Ocultar el campo password al serializar el modelo
    protected $hidden = ['password'];

    /**
     * Mutador para hashear la contraseña automáticamente al asignarla.
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password); // Campo en minúscula
    }

    /**
     * Devuelve el campo que contiene la contraseña para la autenticación.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password; // Campo en minúscula
    }

    protected static function boot()
{
    parent::boot();

    // Establecer valores predeterminados al crear un nuevo Traveler
    static::creating(function ($model) {
        $model->apellido2 = $model->apellido2 ?? ''; // Valor predeterminado para apellido2
        $model->direccion = $model->direccion ?? 'Sin especificar'; // Valor predeterminado para dirección
        $model->codigo_postal = $model->codigo_postal ?? '00000'; // Valor predeterminado para código postal
        $model->ciudad = $model->ciudad ?? 'Desconocida'; // Valor predeterminado para ciudad
        $model->pais = $model->pais ?? 'Sin especificar'; // Valor predeterminado para país
    });

    // Establecer valores predeterminados al actualizar un Traveler
    static::updating(function ($model) {
        $model->apellido2 = $model->apellido2 ?? ''; // Valor predeterminado para apellido2
        $model->direccion = $model->direccion ?? 'Sin especificar'; // Valor predeterminado para dirección
        $model->codigo_postal = $model->codigo_postal ?? '00000'; // Valor predeterminado para código postal
        $model->ciudad = $model->ciudad ?? 'Desconocida'; // Valor predeterminado para ciudad
        $model->pais = $model->pais ?? 'Sin especificar'; // Valor predeterminado para país
    });
}

}
