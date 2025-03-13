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
        // $admin_id = session('ADMIN_ID');


        if (auth()->guard('staff')->check()) {
            return $next($request);
        } else
        if (auth()->guard('admin')->check()) {
            return $next($request);
        } else
        
        {
            return redirect('login');
        }


    }
}
