<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;
use App\Models\Empleado;
use DataTables;
use Validator;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * LISTAR DATOS DE EMPLEADOS
     */
    public function listar(Request $request)
    {   
        $dependencia_id = $request->has('dependencia_id') ? $request->dependencia_id : 0;
        $query = Empleado::with(['persona.identidad_documento','dependencia.sede'])->where('estado', 1);
        
        if($dependencia_id != 0){
            $query->where('dependencia_id', '=', $dependencia_id);
        }

        return DataTables::of($query)->toJson();
    }

    /**
     * REGISTRO DE NUEVO EMPLEADO
     */
    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'persona_id' => 'required',
            'dependencia_id' => 'required',
            'cargo' => 'required',
            'fecha_inicio' => 'required',
            'revocar' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $input = $request->all();  
            $ahora = Carbon::now();

            if($input["revocar"] == 1)//desactivamos los anteriores
            {
                Empleado::where('persona_id', $input["persona_id"])
                ->where('estado', 1)
                ->update(['estado' => 0, 'fecha_termino' => $ahora->format('Y-m-d H:i:s')]);
            }

            $asignados = Empleado::where('persona_id', $input["persona_id"])->where('dependencia_id', $input["dependencia_id"])->where('estado', 1)->count();
            if($asignados > 0){
                return response()->json(['message'=>'Ya esta asignado esta persona a la dependencia seleccionada.'], 500);
            }

            $empleado = new Empleado;
            $empleado->persona_id = $input["persona_id"];
            $empleado->dependencia_id = $input["dependencia_id"];
            $empleado->fecha_inicio = $input["fecha_inicio"];
            $empleado->cargo = $input["cargo"];
            $empleado->estado = 1;
            
            if($empleado->save())
                return response()->json(['message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * CESE DE EMPLEO
     */
    public function finalizar(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if($empleado == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        $ahora = Carbon::now();
        $empleado->fecha_termino = $ahora->format('Y-m-d H:i:s');
        $empleado->estado = 0;

        if($empleado->save())
            return response()->json(['message'=>'Finalizado correctamente'], 200);
        else 
            return response()->json(['message'=>'No se pudo finalizar'], 500);              
            
    }
}
