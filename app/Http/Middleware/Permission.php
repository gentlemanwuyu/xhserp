<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $permissions = explode('|', $permission);

        foreach ($permissions as $p) {
            if (app('auth')->user()->hasPermissionTo($p)) {
                return $next($request);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response('Forbidden.', 403);
        } else {
            abort(403);
        }
    }
}
