<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    //Permite acesso somente se o usuário for administrador.
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado e se seu tipo é 'admin'
        if (Auth::check() && Auth::user()->tipo === 'admin') {
            return $next($request);
        }
        
        // Caso contrário, redireciona ou aborta com mensagem de erro
        return redirect()->route('home')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
