<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthLevel
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (($request->user()->user_role !== $role ) && ($request->user()->user_role !== 'superadmin')) {
            return response()->json(['msg' => 'Can not access this resource!'])->setStatusCode(402);
        }

        return $next($request);
    }
}
