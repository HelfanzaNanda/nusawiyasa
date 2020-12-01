<?php 

namespace App\Http\Middleware;

use Closure;

class PrimaryAuth {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->get('_login')) {
            return $next($request);
        }

        return redirect('/login');
    }
}