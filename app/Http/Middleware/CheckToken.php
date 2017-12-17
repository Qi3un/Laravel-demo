<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;

class CheckToken
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
        $token = $request->header("Authorization");
        if(empty($token)) {
            return response([ "error" => "unauthorized request" ], 401);
        }

        $token = substr($token, 7);
        $user = DB::table('users')->where("api_token", $token);
        if($user->count() == 0) {
            return response([ "error" => "invalid token" ], 401);
        }
        else {
            $request->user_id = $user->value('id');
            return $next($request);
        }
    }
}
