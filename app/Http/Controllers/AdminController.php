<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Modulo;
use App\Models\Privilegio;

class AdminController extends Controller
{
    /**
     * ADMINISTRACION PANEL ADMIN 
     */

    public function index()
    {
        $user = Auth::user();
        if($user->rol_id !== null)
            $modulos = $user->rol->modulos();
        else
            $modulos = array();

        return view('admin.index',compact('modulos'));
    }

    public function perfil()
    {
        $user = Auth::user();
        $privilegios = Privilegio::with('submodulo.modulo')->where('rol_id', $user->rol_id)->get();   
        return view('admin.perfil',compact('user','privilegios'));
    }

}
