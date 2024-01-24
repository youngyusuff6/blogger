<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('api')->guest()) {
            return response()->json(['error' => 'Unauthenticated, kindly try again!'], 401);
        }

        return parent::authenticate($request, $guards);
    }
}
