<?php

namespace App\Http\Middleware;

use Closure;

class SuspendedAccountMiddleware
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
        if (auth()->check() && ($userSuspension = auth()->user()->suspension) ) {
            return response()->json(['message' => 'Your account was suspended on the basis that: ' . $userSuspension->reason], 400);
        }

        return $next($request);
    }
}
