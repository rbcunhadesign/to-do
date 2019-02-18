<?php

namespace App\Http\Middleware;

use Closure;

class JsonOnly
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
        if (!$request->wantsJson()) {
            return response()->json([
               'status' => 'error',
               'message' => 'Only JSON requests accepted',
            ], 415);
        }

        return $next($request);
    }
}
