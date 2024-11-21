<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Redirige a la ruta de login para viajeros
            return route('traveler.login'); // Aquí faltaba el punto y coma
        }

        return null; // Aseguramos que retorne algo si no se cumple el condicional
    }
}
