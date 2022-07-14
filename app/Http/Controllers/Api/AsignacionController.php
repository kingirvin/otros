<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Movimiento_asignacion;
use App\Models\Empleado;
use App\Models\Movimiento;
use DataTables;
use Validator;

class AsignacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(Request $request,$id)
    {
        $query = Movimiento_asignacion::with(['empleado','persona','accion','user'])->where('movimiento_id', $id);
        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [              
            'movimiento_id' => 'required',
            'empleado_id' => 'required',  
            'accion_id' => 'required',
            'detalles' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $movimiento = Movimiento::find($request->movimiento_id);
            if($movimiento == null){
                return response()->json(['message'=>'No se encontro el movimiento'], 500);  
            }

            $empleado = Empleado::find($request->empleado_id);
            if($empleado == null){
                return response()->json(['message'=>'No se encontro el registro de empleado'], 500);  
            }

            if($empleado->estado == 0){
                return response()->json(['message'=>'El empleado no se ecnuentra habilitado'], 500);  
            }

            $user = Auth::user();

            $item = new Movimiento_asignacion;
            $item->movimiento_id = $request->movimiento_id;
            $item->empleado_id = $request->empleado_id;
            $item->persona_id = $empleado->persona_id;
            $item->accion_id = $request->accion_id;
            $item->detalles = $request->detalles;
            $item->estado = 0;
            $item->user_id = $user->id;

            if($item->save()){
                $count = Movimiento_asignacion::where('movimiento_id',$request->movimiento_id)->count();
                $movimiento->asignaciones = $count;
                $movimiento->save();
                return response()->json(['data'=>$item, 'message'=>'Registrado correctamente'], 200);
            }
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function modificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'empleado_id' => 'required',  
            'accion_id' => 'required',
            'detalles' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $asignacion = Movimiento_asignacion::find($request->id);
            if($asignacion == null){
                return response()->json(['message'=>'No se encontro la asignacion'], 500);  
            }

            $empleado = Empleado::find($request->empleado_id);
            if($empleado == null){
                return response()->json(['message'=>'No se encontro el registro de empleado'], 500);  
            }

            if($empleado->estado == 0){
                return response()->json(['message'=>'El empleado no se ecnuentra habilitado'], 500);  
            }

            $user = Auth::user();
            $asignacion->empleado_id = $request->empleado_id;
            $asignacion->persona_id = $empleado->persona_id;
            $asignacion->accion_id = $request->accion_id;
            $asignacion->detalles = $request->detalles;
            $asignacion->user_id = $user->id;

            if($asignacion->save()){
                $count = Movimiento_asignacion::where('movimiento_id',$request->movimiento_id)->count();
                $movimiento->asignaciones = $count;
                $movimiento->save();
                return response()->json(['data'=>$asignacion, 'message'=>'Actualizado correctamente'], 200);
            }
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function estado(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $asignacion = Movimiento_asignacion::find($request->id);
            if($asignacion == null){
                return response()->json(['message'=>'No se encontro la asignacion'], 500);  
            }           

            $user = Auth::user();
            $asignacion->estado = $request->estado;
            $asignacion->user_id = $user->id;

            if($asignacion->save())
                return response()->json(['data'=>$asignacion, 'message'=>'Actualizado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function eliminar(Request $request, $id)
    {
        $asignacion = Movimiento_asignacion::find($request->id);

        if($asignacion == null){
            return response()->json(['message'=>'No se encontro la asignacion'], 500);  
        }         

        try 
        {
            if($asignacion->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }
}
