<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DealerAuthenticate
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('dealer')->guest())
        {            
            return redirect('dealer');
        }
        return $next($request);
    }
}
