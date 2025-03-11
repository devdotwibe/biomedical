<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuthenticate
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
        $admin_id = session('ADMIN_ID');
        
        if (!isset($admin_id) || $admin_id == 0) {
            return redirect('admin');
        }
        return $next($request);
    }
}
