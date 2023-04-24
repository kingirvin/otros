<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rol;
use App\Models\Modulo;
use App\Models\User;
use App\Models\Sede;
use App\Models\Persona;
use App\Models\Dependencia;
use App\Models\Identidad_documento;
use App\Models\Procedimiento;
use App\Utilidades\Recursos;

class SistemaController extends Controller
{
    /**
     * ADMINISTRACIÓN MODULO ADMINISTRADOR DE SISTEMA
     */

    public function index()
    {   
        //almacenamiento
        $disco = config('app.disco', 'D:');
        $recursos = new Recursos();
        $total_disk = disk_total_space($disco);
        $free_disk = disk_free_space($disco);
        $used_disk = $total_disk - $free_disk;
        $almacenamiento = array(
            'disco' => $disco, 
            'total_disk' => $total_disk, 
            'free_disk' => $free_disk, 
            'used_disk' => $used_disk, 
            'total_disk_text' => $recursos->bytes_format($total_disk),
            'free_disk_text' => $recursos->bytes_format($free_disk),
            'used_disk_text' => $recursos->bytes_format($used_disk),
            'porcentaje' => round($used_disk * 100 / $total_disk)
        );

        //sistema operativo
        $s_operativo = php_uname();

        //usuarios
        $user_activo_count = User::where('estado',1)->count();
        $user_interno_count = User::where('estado',1)->where('tipo', 1)->count();//1:interno, 0:externo
        $usuarios = array(
            'activos' => $user_activo_count, 
            'internos' => $user_interno_count, 
            'externos' => ($user_activo_count - $user_interno_count) 
        );

        //Dependencias
        $dependencias = Dependencia::where('estado',1)->count();
        $sedes = Sede::where('estado',1)->count();
        $lugares = array(
            'dependencias' => $dependencias, 
            'sedes' => $sedes
        );

        return view('admin.sistema.index',compact('almacenamiento','s_operativo','usuarios','lugares'));
    }

    /**
     * ACCESOS
     */

    public function roles(Request $request)
    {
        return view('admin.sistema.roles');
    }

    public function privilegios(Request $request, $id)
    {
        $rol = Rol::with('privilegios')->findOrFail($id);
        $modulos = Modulo::with('submodulos')->get();

        foreach ($modulos as $modulo) {
            foreach ($modulo->submodulos as $submodulo) {
                $encontrado = false;
                foreach ($rol->privilegios as $privilegio) {
                    if($privilegio->submodulo_id == $submodulo->id) {
                        $encontrado = true;
                        break;
                    }
                }               
                $submodulo->encontrado = $encontrado;                
            }
        }

        return view('admin.sistema.privilegios', compact('rol','modulos'));
    }

    public function usuarios(Request $request)
    {
        $roles = Rol::where('estado',1)->get();
        return view('admin.sistema.usuarios',compact('roles'));
    }

    /**
     * MANTENIMIENTO
     */

    public function sedes(Request $request)
    {
        return view('admin.sistema.sedes');
    }

    public function dependencias(Request $request)
    {
        $sedes = Sede::where('estado',1)->get();
        return view('admin.sistema.dependencias', compact('sedes'));
    }

    /**
     * PERSONAS
     */ 

    public function personas(Request $request)
    {
        $identidad_documentos = Identidad_documento::where('estado',1)->get();
        return view('admin.sistema.personas',compact('identidad_documentos'));
    }

    public function empleados(Request $request)
    {        
        $sedes = Sede::with(['dependencias' => function ($query) {
            $query->where('estado', '=', 1);
        }])->get();

        return view('admin.sistema.empleados',compact('sedes'));
    }

    public function persona_detalle(Request $request, $id)
    {
        $persona = Persona::with(['identidad_documento','users.rol','empleos.dependencia','estudiantes','invitados'])->findOrFail($id);  
        return view('admin.sistema.persona_detalle',compact('persona'));
    }

    /**
     * GESTION DOCUMENTAL
     */

    public function documento_tipos(Request $request)
    {
        return view('admin.sistema.documento_tipos');
    }

    public function procedimientos(Request $request)
    {
        return view('admin.sistema.procedimientos');
    }

    public function procedimiento_nuevo(Request $request)
    {
        $procedimiento = null;
        $sedes = Sede::with(['dependencias' => function ($query) {
            $query->where('estado', '=', 1);
        }])->get();

        return view('admin.sistema.procedimiento_editar', compact('procedimiento','sedes'));
    }

    public function procedimiento_modificar(Request $request, $id)
    {
        $procedimiento = Procedimiento::with(['presentar','atender'])->findOrFail($id);
        $sedes = Sede::with(['dependencias' => function ($query) {
            $query->where('estado', '=', 1);
        }])->get();
        return view('admin.sistema.procedimiento_editar', compact('procedimiento','sedes'));
    }

    public function procedimiento_pasos(Request $request, $id)
    {
        $procedimiento = Procedimiento::with(['presentar','atender','pasos.dependencia'])->findOrFail($id);  
        $sedes = Sede::with(['dependencias' => function ($query) {
            $query->where('estado', '=', 1);
        }])->get();      
        return view('admin.sistema.procedimiento_pasos', compact('procedimiento','sedes'));
    }


}
