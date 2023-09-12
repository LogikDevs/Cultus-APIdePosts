<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Autenticacion
{
    public function handle(Request $request, Closure $next)
    {
        //return $next($request);
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];

        $response = Http::withHeaders($tokenHeader)->get(getenv("API_AUTH_URL") . "/api/v1/validate");

        
        if($response -> successful())
            return $next($request);
        
        return response(['message' => 'Not Allowed'], 403);
    }
}
