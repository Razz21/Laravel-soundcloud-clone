<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $allowedOrigins = ['http://127.0.0.1:8000', 'http://localhost:8000'];
        $origin = $request->getSchemeAndHttpHost();

        if (in_array($origin, $allowedOrigins)) {

            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
                ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
        }
        return $next($request);
    }
}
