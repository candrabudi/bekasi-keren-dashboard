<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    public function handle($request, Closure $next, $menu, $permission)
    {
        if (!auth()->user()?->hasPermission($menu, $permission)) {
            abort(403, 'Unauthorized action.');
        }
    
        return $next($request);
    }
    
}
