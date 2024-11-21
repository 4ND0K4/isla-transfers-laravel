<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCheck
{
    /**
     * Maneja la solicitud de entrada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $adminId = "94556257"; // Credenciales fijas
        $adminKey = "iJNpF5RU";

        if (
            session('admin_id') !== $adminId ||
            session('admin_key') !== $adminKey
        ) {
            return redirect()->route('admin.login')->withErrors('Credenciales incorrectas.');
        }

        return $next($request);
    }
}
