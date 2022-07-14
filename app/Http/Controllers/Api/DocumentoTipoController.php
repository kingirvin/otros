<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Documento_tipo;
use DataTables;
use Validator;

class DocumentoTipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTAR
     */

    public function listar(Request $request)
    {
        $query = Documento_tipo::withCount('documentos'); 
        return DataTables::of($query)->toJson();
    }

    /**
     * NUEVO
     */

    public function nuevo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [              
            'nombre' => 'required',
            'abreviatura' => 'required',            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $input = $request->all(); 
            $item = Documento_tipo::create($input);

            if($item)
                return response()->json(['data'=>$item, 'message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * MODIIFCAR
     */
   
    public function modificar(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [ 
            'nombre' => 'required',
            'abreviatura' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Documento_tipo::find($id);
            $input = $request->all();    

            if($item)
            {                
                if($item->update($input))
                    return response()->json(['data'=>$item, 'message'=>'Actualizado correctamente'], 200);
                else 
                    return response()->json(['message'=>'No se pudo actualizar'], 500);                
            }
            else
                return response()->json(['message'=>'No se pudo encontrar'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * ELIMINAR
     */

    public function eliminar(Request $request, $id)
    {
        $tipo = Documento_tipo::withCount('documentos')->find($id);        
      
        if($tipo == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($tipo->documentos_count > 0)
           return response()->json(['message'=>'El tipo de documento esta asignado a algun documento'], 500);
       
        try 
        {
            if($tipo->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }
}
