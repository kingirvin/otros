<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utilidades\Recursos;
use Validator;
use App\Models\Archivo;
use App\Models\Archivo_historico;
use App\Models\Empleado;

class VersionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(Request $request, $id)
    {
        $versiones = Archivo_historico::where('archivo_id',$id)->orderBy('id', 'desc')->get();
        return response()->json([ 'versiones'=>$versiones ], 200);
    }

    public function restaurar(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'archivo_id' => 'required',
            'version_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $user = Auth::user();
        $archivo = Archivo::withCount(['anexados','documentos'])->find($request->archivo_id);
        $version = Archivo_historico::find($request->version_id);      

        if(!$archivo)
            return response()->json(['message'=>'No se encontro el archivo'], 500);

        if(!$version)
            return response()->json(['message'=>'No se encontro la version'], 500);

        if($version->archivo_id != $archivo->id)
            return response()->json(['message'=>'La version no corresponde al archivo'], 500);

        if($archivo->documentos_count > 0)
            return response()->json(['message'=>'El archivo ya ha sido asignado a un documento.'], 500);
       
        if($archivo->anexados_count > 0)
            return response()->json(['message'=>'El archivo esta como anexo de un documento.'], 500);

        //el usuario tiene permisos sobre el archivo a mover
        if($archivo->dependencia_id != null){
            $empleados_archivo = Empleado::where('dependencia_id', $archivo->dependencia_id)->where('persona_id', $user->persona_id)->where('estado',1)->count();
            if($empleados_archivo <= 0){
                return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);        
            }
        }
        else {
            if($archivo->user_id != $user->id)
                return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);
        }

        try 
        {
            $archivo->formato = $version->formato;
            $archivo->size = $version->size;
            $archivo->ruta = $version->ruta;
            $archivo->nombre_real = $version->nombre_real;
            $archivo->estado = $version->estado;     

            if($archivo->save())//tomamos los datos del historico (se pierden el actual)
            {
                //eliminamos el historico actual y los que son posteriores a ese
                Archivo_historico::where('archivo_id', $archivo->id)->where('id', '>=', $version->id)->delete();
                return response()->json(['message'=>'Actualizado correctamente'], 200);  
            }              
            else
                return response()->json(['message'=>'No se pudo actualizar'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

}
