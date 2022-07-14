<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rol;
use App\Models\Privilegio;
use DataTables;
use Validator;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(Request $request)
    {
        $query = Rol::withCount(['users','privilegios']); 
        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [              
            'nombre' => 'required',
            'descripcion' => 'required',  
            'estado' => 'required',            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $input = $request->all(); 
            $item = Rol::create($input);

            if($item)
                return response()->json(['data'=>$item, 'message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function modificar(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [ 
            'nombre' => 'required',
            'descripcion' => 'required',  
            'estado' => 'required',   
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Rol::find($id);
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
        $rol = Rol::withCount(['users','privilegios'])->find($id);
      
        if($rol == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($rol->users_count > 0)
            return response()->json(['message'=>'El rol tiene usuarios registrados'], 500);

        if($rol->privilegios_count > 0)
            return response()->json(['message'=>'El rol tiene privilegios registrados'], 500);       

        try 
        {
            if($rol->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }

    public function privilegios(Request $request, $id)
    {
        $rol = Rol::withCount(['users','privilegios'])->find($id);

        if($rol == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        try 
        { 
            $submodulos = $request->input('submodulos');
            //borramos todos
            Privilegio::where('rol_id', $rol->id)->delete();   
            //registramos nuevos
            if($submodulos) {
                foreach ($submodulos as $submodulo) {
                    Privilegio::create(['submodulo_id' => $submodulo["id"], 'rol_id' => $rol->id]);
                }
            }

            return response()->json(['message'=>'Actualizado correctamente'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }
}
