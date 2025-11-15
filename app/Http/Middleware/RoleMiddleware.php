<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        $user = $request->user();
        
        if (!$user->hasRole($roles)) {
            abort(403, 'Unauthorized access');
        }
        
        return $next($request);
    }
}
