<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Archivo_compartido;
use App\Models\Archivo;
use App\Models\Empleado;
use App\Models\User;

class CompartidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * LISTAR USUARIOS A QUIENES SE COMPARTIO UN ARCHIVO
     */
    public function listar(Request $request, $id)
    {
        $compartidos = Archivo_compartido::with(['user'])->where('archivo_id', $id)->get();
        return response()->json([ 'compartidos'=>$compartidos ], 200);
    }
    
    /**
     * COMPARTIR UN ARCHIVO CON USUARIO
     */
    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'archivo_id' => 'required',
            'user_id' => 'required',
        ]);            

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = Auth::user(); 
            $archivo_compartir = Archivo::find($request->archivo_id);
            
            if($archivo_compartir == null){
                return response()->json(['message'=>'No se encontro el archivo'], 500);
            }

            //el usuario tiene permisos sobre el archivo a compartir
            if($archivo_compartir->dependencia_id != null){
                $empleados_archivo = Empleado::where('dependencia_id', $archivo_compartir->dependencia_id)->where('persona_id', $user->persona_id)->where('estado',1)->count();
                if($empleados_archivo <= 0){
                    return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);        
                }
            }
            else {
                if($archivo_compartir->user_id != $user->id){
                    return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);
                }
            }

            $destino = User::find($request->user_id);
            if($destino == null){
                return response()->json(['message'=>'No se encontro el usuario destino'], 500);
            }

            if($destino->tipo == 0){//1:interno, 0:externo
                return response()->json(['message'=>'No puedes compartir con un usuario externo'], 500);
            }

            //ya ha sido compartido
            $existe = Archivo_compartido::where('archivo_id', $request->archivo_id)->where('user_id', $request->user_id)->count();
            if($existe > 0) {
                return response()->json(['message'=>'El archivo ya a sido compartido con el usuario'], 500);
            }

            $compartir = new Archivo_compartido;
            $compartir->archivo_id = $request->archivo_id;
            $compartir->user_id = $request->user_id;
            $compartir->estado = 1;

            if($compartir->save())            
                return response()->json(['message'=>'Registrado correctamente'], 200);
            else
                return response()->json(['message'=>'No se pudo registrar'], 500);

        }    
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * ELIMINAR COMPARTIDO
     */
    public function eliminar(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'archivo_id' => 'required',
            'user_id' => 'required',
        ]);            

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = Auth::user(); 

            $existe = Archivo_compartido::with(['archivo'])->where('archivo_id', $request->archivo_id)->where('user_id', $request->user_id)->first();
            if($existe == null){
                return response()->json(['message'=>'No se encontro el registro'], 500);
            }

            //el usuario tiene permisos sobre el archivo a compartir
            $tiene_permiso = false;            
            if($existe->archivo->dependencia_id != null){//el archivo pertenece a una dependencia de la cual el usuario es miembro
                $empleados_archivo = Empleado::where('dependencia_id', $existe->archivo->dependencia_id)->where('persona_id', $user->persona_id)->where('estado',1)->count();
                if($empleados_archivo > 0){
                    $tiene_permiso = true;   
                }
            }
            else {
                if($existe->archivo->user_id == $user->id){//el usuario es quien creo el archivo
                    $tiene_permiso = true;   
                }
            }

            if($existe->user_id == $user->id)//el usuario es a quien se le compartio el archivo
                $tiene_permiso = true;
            
            if($tiene_permiso == false){
                return response()->json(['message'=>'No tienes los permisos necesarios para realizar esta acción'], 500);
            }
            
            if(Archivo_compartido::where('archivo_id', $request->archivo_id)->where('user_id', $request->user_id)->delete())            
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else
                return response()->json(['message'=>'No se pudo eliminar'], 500);
        }    
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }   


}
