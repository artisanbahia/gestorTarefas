<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(session()->has('username'))
        {
            return redirect()->route('index'); // Ou seja, se existe um username (Setado com session()->put('username', 'admin'); em login_frm, significando que a pessoa está logada), eu redireciono para a rota index quando a pessoa clicar para fazer Logout
        }
        return $next($request);
    }
}
