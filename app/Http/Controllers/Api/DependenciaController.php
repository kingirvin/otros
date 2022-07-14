<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dependencia;
use DataTables;
use Validator;

class DependenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTAR TODAS LAS DEPENDENCIAS
     */
    public function listar(Request $request)
    {
        $query = Dependencia::with('sede')->withCount('empleados');
        if ($request->has('sede_id')) {
            if($request->sede_id != 0)
                $query->where('sede_id', $request->sede_id);
        }

        return DataTables::of($query)->toJson();
    }

    /**
     * BUSCAR DEPENDENCIAS POR SEDE
     */
    public function buscar(Request $request, $id)
    { 
        $result = Dependencia::where('sede_id', $id)->get();          
        return response()->json([ 'data' => $result ], 200);
    }

    /**
     * REGISTRAR NUEVA DEPENDENCIA
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
            $item = Dependencia::create($input);

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
     * MODIFICAR DEPENDENCIA
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
            $item = Dependencia::find($id);
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

    public function eliminar(Request $request, $id)
    {
        $dependencia = Dependencia::withCount(['empleados'/*,'tramites','documentos','origenes','destinos','personales'*/])->find($id);
      
        if($dependencia == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($dependencia->empleados_count > 0)
            return response()->json(['message'=>'La dependencia tiene registrado empleados'], 500);

        /*if($dependencia->personales_count > 0)
            return response()->json(['message'=>'La dependencia tiene registrado personal'], 500);

        if($dependencia->tramites_count > 0)
            return response()->json(['message'=>'La dependencia tiene registrado trámites'], 500);

        if($dependencia->documentos_count > 0)
            return response()->json(['message'=>'La dependencia tiene registrado documentos'], 500);

        if($dependencia->origenes_count > 0)
            return response()->json(['message'=>'La dependencia tiene registrado origenes'], 500);

        if($dependencia->destinos_count > 0)
            return response()->json(['message'=>'La dependencia tiene registrado destinos'], 500);*/

        try 
        {
            if($dependencia->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }
}
