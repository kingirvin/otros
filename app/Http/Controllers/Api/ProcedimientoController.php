<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Procedimiento;
use App\Models\Procedimiento_paso;
use DataTables;
use Validator;
use Carbon\Carbon;

class ProcedimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTAR PROCEDIMIENTOS
     */

    public function listar(Request $request)
    {   
        $tipo = $request->has('tipo') ? $request->tipo : 0;//0:interno
        $query = Procedimiento::with(['pasos','presentar'])->withCount('tramites')->where('tipo',$tipo);     
        return DataTables::of($query)->toJson();
    }

    /**
     * NUEVO PROCEDIMIENTO
     */

    public function nuevo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [  
            'tipo' => 'required', 
            'titulo' => 'required',          
            'presentar_id' => 'required',     
            'plazo' => 'required',
            'calificacion' => 'required',         
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $input = $request->all(); 
            $item = Procedimiento::create($input);

            if($item)
                return response()->json(['message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * MODIFICAR PROCEDIMIENTO
     */

    public function modificar(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [ 
            'tipo' => 'required', 
            'titulo' => 'required',          
            'presentar_id' => 'required',     
            'plazo' => 'required',
            'calificacion' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Procedimiento::find($id);
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
     * ELIMINAR PROCEDIMIENTO
     */

    public function eliminar(Request $request, $id)
    {
        $procedimiento = Procedimiento::withCount(['pasos','tramites'])->find($id);
      
        if($procedimiento == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($procedimiento->pasos_count > 0)
            return response()->json(['message'=>'El procedimiento tiene pasos registrados'], 500);

        if($rol->tramites_count > 0)
            return response()->json(['message'=>'El procedimiento tiene tramites registrados'], 500);       

        try 
        {
            if($procedimiento->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }

    /**
     * PASOS
     */

    public function pasos(Request $request, $id)
    {
        $procedimiento = Procedimiento::find($id);

        if($procedimiento == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        try 
        { 
            $pasos = $request->input('pasos');
            //borramos todos
            Procedimiento_paso::where('procedimiento_id', $procedimiento->id)->delete();   
            //registramos nuevos
            if($pasos) {
                $orden = 1;
                foreach ($pasos as $paso) {
                    Procedimiento_paso::create([
                        'procedimiento_id' => $procedimiento->id, 
                        'dependencia_id' => $paso["dependencia_id"],
                        'orden' => $orden,
                        'accion' => $paso["accion"],
                        'descripcion' => $paso["descripcion"],
                        'plazo_atencion' => $paso["plazo_atencion"],
                        'plazo_subsanacion' => $paso["plazo_subsanacion"],
                        'estado' => $paso["estado"]
                    ]);
                    $orden++;
                }
            }

            return response()->json(['message'=>'Actualizado correctamente'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }


}
