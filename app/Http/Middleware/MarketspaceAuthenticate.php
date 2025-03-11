<?php

namespace App\Http\Middleware;

use Closure;

class MarketspaceAuthenticate
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
        $markespace_id = session('MARKETSPACE_ID');
        
        if (!isset($markespace_id) || $markespace_id == 0) {
            return redirect('marketspace/login');
        }
        return $next($request);
    }
}
