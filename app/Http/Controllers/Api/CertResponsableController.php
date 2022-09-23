<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cert_repositorio;
use App\Models\Cert_repositorio_user;
use DataTables;
use Validator;

class CertResponsableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(Request $request, $id)
    {
        $responsables = Cert_repositorio_user::with(['user.identidad_documento'])
                        ->where('cert_repositorio_id', '=', $id)
                        ->get();

        return response()->json($responsables, 200);
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'cert_repositorio_id' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $cantidad = Cert_repositorio_user::where('cert_repositorio_id', $request->cert_repositorio_id)->where('user_id', $request->user_id)->count();

            if($cantidad > 0) {
                return response()->json(['message'=>"El responsables ya esta asignado"], 500);
            }

            $responsable = new Cert_repositorio_user;
            $responsable->cert_repositorio_id = $request->cert_repositorio_id;
            $responsable->user_id = $request->user_id;            
            if($responsable->save())
                return response()->json(['message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function eliminar(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'cert_repositorio_id' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $cantidad = Cert_repositorio_user::where('cert_repositorio_id', $request->cert_repositorio_id)->where('user_id', $request->user_id)->count();

        if($cantidad == 0) {
            return response()->json(['message'=>"No se encontro el registro de responsable"], 500);
        }

        try 
        {
            $eliminados = Cert_repositorio_user::where('cert_repositorio_id', $request->cert_repositorio_id)->where('user_id', $request->user_id)->delete();  
            if($eliminados > 0)
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }

}
