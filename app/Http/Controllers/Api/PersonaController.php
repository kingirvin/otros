<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;
use App\Models\User;
use App\Models\Invitado;
use DataTables;
use Validator;
use Carbon\Carbon;

class PersonaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * LISTAR DATOS DE PERSONAS
     */
    public function listar(Request $request)
    {
        $query = Persona::withCount(['users','empleos','estudiantes','invitados'])->with(['identidad_documento']);        
        return DataTables::of($query)->toJson();
    }

    public function buscar(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'term' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $result = Persona::where('estado', 1)
        ->where(function ($query) use ($request) {
            $query->where('nro_documento','like', '%'.$request->input('term').'%')
                ->orWhere('nombre','like', '%'.$request->input('term').'%')
                ->orWhere('apaterno','like', '%'.$request->input('term').'%')
                ->orWhere('amaterno','like', '%'.$request->input('term').'%');
        })->take(7)->get();

        return response()->json($result, 200);
    }

    public function probar(Request $request)//probar conexion
    {
        $starttime = microtime(true);
        try 
        {
            $personas = Persona::with(['identidad_documento','empleos','estudiantes','invitados','users'])->get();     
            $endtime = microtime(true);
            return response()->json(['data'=>$personas, 'time'=> ($endtime - $starttime)], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * NUEVA PERSONA
     */

    public function nuevo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [  
            'identidad_documento_id' => 'required',           
            'nro_documento' => ['required', 'unique:personas'],
            'nombre' => 'required',
            'apaterno' => 'required',
            'amaterno' => 'required',            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $input = $request->all(); 
            $item = Persona::create($input);

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
     * ACTUALIZAR DATOS
     */

    public function modificar(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [ 
            'identidad_documento_id' => 'required',           
            'nro_documento' => ['required'],
            'nombre' => 'required',
            'apaterno' => 'required',
            'amaterno' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Persona::find($id);
            $input = $request->all();    

            if($item)
            {        
                if($item->nro_documento != $input["nro_documento"])//se quiere cambiar el dni
                {
                    $existe = Persona::where('nro_documento', $input["nro_documento"])->where('id', '<>', $item->id)->count();

                    if($existe > 0)
                        return response()->json(['message'=>'Ya existe una persona con el mismo número de documento'], 500); 
                }    

                if($item->update($input))//'telefono', 'direccion', 'nacimiento',
                {
                    User::where('persona_id', $item->id)
                            ->update(['identidad_documento_id' => $item->identidad_documento_id,
                                'nro_documento' => $item->nro_documento,  
                                'nombre' => $item->nombre, 
                                'apaterno' => $item->apaterno, 
                                'amaterno' => $item->amaterno]);

                    return response()->json(['data'=>$item, 'message'=>'Actualizado correctamente'], 200);
                }
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
     * ELIMINAR DATOS
     */
    
    public function eliminar(Request $request, $id)
    {
        $persona = Persona::withCount(['users','empleos','estudiantes'])->find($id);
      
        if($persona == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($persona->users_count > 0)
            return response()->json(['message'=>'La persona tiene usuarios registrados'], 500);

        if($persona->empleos_count > 0)
            return response()->json(['message'=>'La persona tiene registrado datos de empleado'], 500);  
            
        if($persona->estudiantes_count > 0)
            return response()->json(['message'=>'La persona tiene registrado datos de estudiante'], 500); 

        try 
        {
            Invitado::where('persona_id', $persona->id)->delete();

            if($persona->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }
}
