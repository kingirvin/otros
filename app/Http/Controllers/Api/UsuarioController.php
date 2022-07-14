<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Persona;
use DataTables;
use Validator;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(Request $request)
    {        
        $query = User::with(['rol','identidad_documento'])->whereNotNull('rol_id');
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

        $result = User::where('estado', 1)
        ->where(function ($query) use ($request) {
            $query->where('nro_documento','like', '%'.$request->input('term').'%')
                ->orWhere('nombre','like', '%'.$request->input('term').'%')
                ->orWhere('apaterno','like', '%'.$request->input('term').'%')
                ->orWhere('amaterno','like', '%'.$request->input('term').'%');
        })->get();                              
        return response()->json($result, 200);
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'persona_id' => 'required',
            'rol_id' => 'required',            
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']             
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $persona = Persona::find($request->persona_id);

        if ($persona == null) {
            return response()->json(['message'=>'No se pudo encontrar la persona'], 500);
        }

        try 
        {
            $input = $request->all(); 
            $user = new User;
            $user->rol_id = $input['rol_id'];
            $user->persona_id = $persona->id;            
            $user->identidad_documento_id = $persona->identidad_documento_id;
            $user->nro_documento = $persona->nro_documento;
            $user->nombre = $persona->nombre;
            $user->apaterno = $persona->apaterno;
            $user->amaterno = $persona->amaterno;
            $user->email = $input['email'];            
            $user->password = Hash::make($input['password']); 

            if($user->save())
                return response()->json(['data'=>$user, 'message'=>'Registrado correctamente'], 200);
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
            'rol_id' => 'required', 
            'estado' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = User::find($id);
            $input = $request->all();    

            if($user == null)
                return response()->json(['message'=>'No se pudo encontrar el usuario'], 500); 
                            
            $user->rol_id = $input['rol_id'];
            $user->estado = $input['estado'];
                                    
            if($user->save())
                return response()->json(['data'=>$user, 'message'=>'Actualizado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo actualizar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    //uno mismo
    public function actualizar(Request $request)
    {        
        $validator = Validator::make($request->all(), [      
            'tipo_documento' => 'required',
            'nro_documento' => 'required',  
            'nombre' => 'required',  
            'apaterno' => 'required',
            'amaterno' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = Auth::user();
            $input = $request->all();    

            if($user->rol_id != null)
                return response()->json(['message'=>'Solo se puden actualizar datos de usuario público.'], 500);
                        
            $user->tipo_documento = $input['tipo_documento'];
            $user->nro_documento = $input['nro_documento'];
            $user->nombre = $input['nombre'];
            $user->apaterno = $input['apaterno'];
            $user->amaterno = $input['amaterno'];
            $user->telefono = $input['telefono'];
            $user->direccion = $input['direccion'];
            $user->nacimiento = $input['nacimiento'];
                                    
            if($user->save())
                return response()->json(['data'=>$user, 'message'=>'Actualizado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo actualizar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }
    
    public function cambiar_password(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed']      
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $input = $request->all();
            $user = User::find($id);

            if($user == null)
                return response()->json(['message'=>'No se pudo encontrar'], 500);

            $user->password = Hash::make($input['password']);
            if($user->save())
                return response()->json(['data'=>$user, 'message'=>'Actualizado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo actualizar'], 500);

        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    //uno mismo
    public function renovar_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_old' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed']      
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = Auth::user();
            $input = $request->all();

            if(Hash::check($input["password_old"], $user->password)) {
                $user->password = Hash::make($input['password']); 
                if($user->save())
                    return response()->json(['message'=>'Actualizado correctamente'], 200);
                else 
                    return response()->json(['message'=>'No se pudo actualizar'], 500);
            }
            else
             return response()->json(['message'=>'La contraseña proporcionada no es la correcta'], 500);

        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }


}
