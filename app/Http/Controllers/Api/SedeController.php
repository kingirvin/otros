<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sede;
use DataTables;
use Validator;

class SedeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTAR TODAS LAS SEDES
     */
    public function listar(Request $request)
    {
        $query = Sede::with('dependencias');
        return DataTables::of($query)->toJson();
    } 

    /**
     * REGISTRAR NUEVA SEDE
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
            $item = Sede::create($input);

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
     * MODIFICAR SEDE
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
            $item = Sede::find($id);
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
     * ELIMINAR SEDE
     */
    public function eliminar(Request $request, $id)
    {
        $sede = Sede::withCount('dependencias')->find($id);

        if($sede == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($sede->dependencias_count > 0)
            return response()->json(['message'=>'La sede tiene registrado dependencias'], 500);

        try 
        {
            if($sede->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }
}
