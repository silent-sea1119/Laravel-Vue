<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Models\Permission;

class AuthPermission
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
        $premission = Permission::where('user_id', $request->user()->id)->where('permission', $role);
        if (!$premission->exists()) {
            return response()->json(['msg' => 'Can not access this resource!'])->setStatusCode(402);
        }

        return $next($request);
    }
}
