<?php

namespace App\Http\Middleware;

use Closure;

class Autentica
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
        if( !session()->has('autenticado') ){
            // return redirect()->route('/login');
            return redirect('/login');
        }

        return $next($request);
    }
}
