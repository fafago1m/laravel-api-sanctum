<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // Jika request bukan expectsJson (misalnya langsung buka di browser), kembalikan response 401
        if (! $request->expectsJson()) {
            abort(401, 'Unauthenticated.');
        }
    }
}
