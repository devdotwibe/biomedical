<?php

namespace App\Http\Middleware;

use Closure;

class StaffAuthenticate
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
        $staff_id = session('STAFF_ID');
        
        if (!isset($staff_id) || $staff_id == 0) {
            return redirect('staff');
        }
        return $next($request);
    }
}
