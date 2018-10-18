<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isNotCliente
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
        if (Auth::check() && (Auth::user()->cliente_id  != null || Auth::user()->cliente_id != "" || Auth::user()->cliente_id != 0))
            return redirect('clientes/')->withInput();
        else
        {
            return $next($request);
        }
    }
}
