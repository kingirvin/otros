<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSubmodulo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $submodulo)
    {
        $user = Auth::user();
        //modulos a los que tiene acceso el rol del usuario [modulo] => [submodulos|...]
        if($user->rol_id != null)
            $modulos = $user->rol->modulos();
        else
            $modulos = array();

        $existe = false;
        foreach ($modulos as $key => $value) {
            if(in_array($submodulo, $value) !== false){//exste el submodulo en "submodulo1,submodulo2,submodulo3"
                $existe = true;
                break;
            }
        }

        if($existe)
        {
            $request->merge(['modulos' => $modulos]);
            return $next($request);
        }
        else
            return abort(403);
    }
}
