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
        // API consumers must receive a 401 response, even when they omit the
        // Accept: application/json header (for example, when testing in a browser).
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // This application uses Filament for web authentication and does not
        // register Laravel UI's conventional route named "login".
        return route('filament.auth.login');
    }
}
