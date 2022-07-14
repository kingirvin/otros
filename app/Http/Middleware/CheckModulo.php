<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckModulo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $modulo)
    {
        $user = Auth::user();
        //modulos a los que tiene acceso el rol del usuario [modulo] => [submodulos,...]
        if($user->rol_id != null)
            $modulos = $user->rol->modulos();
        else
            $modulos = array();

        if(isset($modulos[$modulo]))
        {
            $request->merge(['modulos' => $modulos]);
            return $next($request);
        }
        else
            return abort(403);
    }
}
