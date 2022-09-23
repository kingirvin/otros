<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Utilidades\Recursos;
use App\Models\Cert_carpeta;
use Validator;

class CertCarpetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTAR CARPETAS
     */
    public function listar(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'cert_repositorio_id' => 'required',
            'cert_carpeta_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $user = Auth::user();
        //agregar validaciones..........    
        
        //obtenemos el actual
        if($request->cert_carpeta_id != 0)
            $seleccionado = Cert_carpeta::find($request->cert_carpeta_id);
        else
            $seleccionado = null;
        
        //obtenemos carpetas        
        if($request->cert_carpeta_id != 0)//mostrar sub carpetas
            $carpetas = Cert_carpeta::where('cert_repositorio_id',$request->cert_repositorio_id)->where('cert_carpeta_id', $request->cert_carpeta_id)->orderBy('created_at', 'desc')->get();
        else//mostrar carpetas raiz
            $carpetas = Cert_carpeta::where('cert_repositorio_id',$request->cert_repositorio_id)->whereNull('cert_carpeta_id')->orderBy('created_at', 'desc')->get();
    
        return response()->json(['seleccionado'=>$seleccionado, 'carpetas'=>$carpetas ], 200);              
    }

    /**
     * NUEVA CARPETA
     */
    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'cert_repositorio_id' => 'required',
            'cert_carpeta_id' => 'required',
            'nombre' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }
        
        $recursos = new Recursos;
        $user = Auth::user();

        //comprobamos si la carpeta existe con ese mismo nombre en la ubicacion        
        if($request->cert_carpeta_id != 0)
            $repetidos = Cert_carpeta::where('cert_carpeta_id', $request->carpeta_id)->where('cert_repositorio_id', $request->cert_repositorio_id)->where('nombre', $request->nombre)->count();
        else
            $repetidos = Cert_carpeta::whereNull('cert_carpeta_id')->where('cert_repositorio_id', $request->cert_repositorio_id)->where('nombre', $request->nombre)->count();
     
        if($repetidos <= 0)
        {
            $carpeta = new Cert_carpeta;        
            $carpeta->cert_carpeta_id = $request->cert_carpeta_id != 0 ? $request->cert_carpeta_id : null;
            $carpeta->cert_repositorio_id = $request->cert_repositorio_id;
            $carpeta->nombre = $request->nombre;
            $carpeta->user_id = $user->id;
            $carpeta->publico = 0;
            $carpeta->ubicacion = Cert_carpeta::generar_ubicacion($request->cert_carpeta_id);

            if($carpeta->save())
            {
                $carpeta->codigo = $recursos->codigo_alpha($carpeta->id);
                $carpeta->save();
                return response()->json(['carpeta'=>$carpeta, 'message'=>'Registrado correctamente'], 200);
            }
            else
                return response()->json(['message'=>'No se pudo registrar'], 500);
        }
        else
            return response()->json(['message'=>'Ya existe una carpeta con el mismo nombre'], 500);
    }

    /**
     * MODIFICAR CARPETA
     */
    public function modificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [             
            'nombre' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $recursos = new Recursos;
        $carpeta = Cert_carpeta::find($id);
        $user = Auth::user();

        if($carpeta)
        {
            //comprobamos si la carpeta existe con ese mismo nombre en la ubicacion            
            if($carpeta->cert_carpeta_id != null)
                $repetidos = Cert_carpeta::where('cert_carpeta_id', $carpeta->cert_carpeta_id)->where('cert_repositorio_id', $carpeta->cert_repositorio_id)->where('nombre', $request->nombre)->count();
            else
                $repetidos = Cert_carpeta::whereNull('cert_carpeta_id')->where('cert_repositorio_id', $carpeta->cert_repositorio_id)->where('nombre', $request->nombre)->count();
            
            
            if($repetidos <= 0)
            {
                $carpeta->nombre = $request->nombre;
                if($carpeta->save()) 
                    return response()->json(['carpeta'=>$carpeta, 'message'=>'Actualizado correctamente'], 200);                
                else
                    return response()->json(['message'=>'No se pudo registrar'], 500);
            }
            else
                return response()->json(['message'=>'Ya existe una carpeta con el mismo nombre'], 500);
        }
        else
            return response()->json(['message'=>'No se encontro la carpeta'], 500);
    }

    /**
     * MOVER CARPETA
     */
    public function mover(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'cert_carpeta_id' => 'required',
            'destino_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $input = $request->all();        
        $user = Auth::user(); 

        //obtenemos las carpetas
        $carpeta_mover = Cert_carpeta::find($request->cert_carpeta_id);

        if($request->destino_id != 0)//no se mueve a la carpeta raiz
            $carpeta_destino = Cert_carpeta::find($request->destino_id);
        else
            $carpeta_destino = null;
            
        //realizamos validaciones
        if($carpeta_mover == null)
            return response()->json(['message'=>'No se pudo encontrar la carpeta'], 500);

        if($request->destino_id != 0)//si no se mueve a la carpeta raiz se valida la carpeta destino 
        {
            if($carpeta_destino == null)
                return response()->json(['message'=>'No se pudo encontrar la carpeta destino'], 500);

            if($carpeta_mover->id == $carpeta_destino->id)
                return response()->json(['message'=>'La carpeta destino no debe ser la misma carpeta'], 500);
    
            if($carpeta_destino->id == $carpeta_mover->carpeta_id)
                return response()->json(['message'=>'La carpeta ya se encuentra de dicha ubicación'], 500);
            
            //la carpeta destino debe ser del mimsmo tipo            
            if($carpeta_mover->cert_repositorio_id != $carpeta_destino->cert_repositorio_id)
                return response()->json(['message'=>'La carpeta destino debe pertencer al mismo repositorio'], 500);             

            //entre las subcarpetas de la carpeta destino no debe haber una carpeta con el mismo nombre de la carpeta a mover 
            $existentes = Cert_carpeta::where('cert_carpeta_id', $carpeta_destino->id)->where('nombre', $request->nombre)->count();//independientemente si la carpeta destino es de usuario o dependencia
            if($existentes > 0)
                return response()->json(['message'=>'La ubicación contiene una carpeta con el mismo nombre'], 500);

            //no podemos mover la carpeta a una de sus sub carpetas
            $padres_destino = explode(",", $carpeta_destino->ubicacion);
            if(in_array($carpeta_mover->id, $padres_destino))
                return response()->json(['message'=>'La carpeta no debe contener la carpeta detino'], 500);
        }
        else//se mueve a la carpeta raiz
        {
            if($carpeta_mover->cert_carpeta_id == null)
                return response()->json(['message'=>'La carpeta ya se encuentra de dicha ubicación'], 500);

            //entre las subcarpetas de la carpeta destino no debe haber una carpeta con el mismo nombre de la carpeta a mover 
            $existentes = Cert_carpeta::whereNull('cert_carpeta_id')->where('cert_repositorio_id', $carpeta_mover->cert_repositorio_id)->where('nombre', $request->nombre)->count();
                        
            if($existentes>0)
                return response()->json(['message'=>'La ubicación contiene una carpeta con el mismo nombre'], 500);
        }

        //registramos el movimiento
        $carpeta_mover->cert_carpeta_id = ($request->destino_id != 0 ? $request->destino_id : null);
        $carpeta_mover->ubicacion = Cert_carpeta::generar_ubicacion($request->destino_id );

        if($carpeta_mover->save())
        {
            $carpeta_mover->sub_update();
            return response()->json(['message'=>'Actualizado correctamente'], 200);    
        }            
        else
            return response()->json(['message'=>'No se pudo mover'], 500);        
    }

    /**
     * ELIMINAR CARPETA
     */
    public function eliminar(Request $request, $id)
    { 
        $carpeta = Cert_carpeta::withCount(['archivos','subcarpetas'])->find($id);

        if(!$carpeta)//no se encontro la carpeta
            return response()->json(['message'=>'No se encontro la carpeta'], 500);
        
        if($carpeta->archivos_count > 0)//la carpeta tiene archivos
            return response()->json(['message'=>'No se pudo eliminar: La carpeta contiene archivos'], 500);
        
        if($carpeta->subcarpetas_count > 0)//la carpeta tiene sub carpetas
            return response()->json(['message'=>'No se pudo eliminar: La carpeta contiene subcarpetas'], 500);

        if($carpeta->delete())//eliminadmos
            return response()->json(['message'=>'Eliminado correctamente'], 200);                
        else
            return response()->json(['message'=>'No se pudo eliminar'], 500);
    }

}
