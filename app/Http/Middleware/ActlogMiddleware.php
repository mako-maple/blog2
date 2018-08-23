<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Actlog;
use \Route;

class ActlogMiddleware
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
        $response = $next($request);

        $data = [
            'user_id' => Auth::id() > 0 ? Auth::id() : null,
            'route' => Route::currentRouteName(),
            'url' => $request -> path(),
            'method' => $request -> method(),
            'status' => $response->status(),
            'message' => count($request->toArray()) != 0 ? json_encode($request->toArray()) : null, 
            'remote_addr' => $request -> ip(),
            'user_agent' => $request -> userAgent(),
        ];
        Actlog::create($data);

        return $response;
    }
}
