<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AuthenticateOnceWithBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $AUTH_USER = config('admin.auth_user');
        $AUTH_PASS = config('admin.auth_pass');

        $user = $request->getUser();
        $password = $request->getPassword();

        if (!is_string($AUTH_USER) || !is_string($AUTH_PASS) ||
            $user === null || $password === null ||
            !hash_equals($AUTH_USER, $user) || !hash_equals($AUTH_PASS, $password)) {
            return response('', 401, [
                'WWW-Authenticate' => 'Basic realm="Access denied"',
                'Cache-Control' => 'no-cache, must-revalidate, max-age=0',
            ]);
        }

        return $next($request)->header('Cache-Control', 'no-cache, must-revalidate, max-age=0');
    }
}
