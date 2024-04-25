<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class  CheckAppKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = getallheaders();
        if($headers['app_key'] == 'NLbtPEKSnH/Iq9YWRS8TaZSOBUGqAdAL6y9RzHfbueE='){
            return $next($request);
        }
        else{
            return response()->json(['error' => 'You Are Not Authorize For App'], 401);
        }
    }
}
