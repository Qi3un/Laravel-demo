<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Response;


class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
  
    public function handle($request, Closure $next) {
        error_log(print_r("CORS", true));
        // return response("", 200)
        //     ->header("Access-Control-Allow-Origin", "*")
        //     ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
        //     ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
        return $next($request)
            ->header("Access-Control-Allow-Origin", "*")
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
            ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
    }
}