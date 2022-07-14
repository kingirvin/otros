<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Utilidades\Recursos;
use Validator;
use App\Models\Archivo;
use App\Models\Archivo_historico;
use App\Models\Archivo_compartido;
use App\Models\Carpeta;
use App\Models\Empleado;

//use App\Documento;
use stdClass;

class ArchivoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->disco = config('app.almacenamiento');
        $this->recursos = new Recursos;
    }
    
    /**
     * LISTAR CARPETAS Y ARCHIVOS
     */
    public function listar_todo(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'ubicacion' => 'required',
            'dependencia_id' => 'required',
            'carpeta_id' => 'required'
        ]);
     
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $user = Auth::user();

        if($request->ubicacion == 'm' || $request->ubicacion == 'd') //mis archivos o archivos de dependencia
        {
            $query_carpeta = Carpeta::with(['user'])->withCount(['archivos','subcarpetas']);
            $query_archivo = Archivo::with(['user'])->withCount(['compartidos','historicos','documentos']);
            $seleccionado = null; 

            //ubicacion
            if($request->ubicacion == 'd')//--archivos de dependencia
            {
                $query_carpeta->where('dependencia_id', $request->dependencia_id);
                $query_archivo->where('dependencia_id', $request->dependencia_id);                
            } 
            else //--mis archivos
            {
                $query_carpeta->whereNull('dependencia_id')->where('user_id', $user->id);
                $query_archivo->whereNull('dependencia_id')->where('user_id', $user->id);
            }

            //carpeta
            if($request->carpeta_id != 0){
                $query_carpeta->where('carpeta_id', $request->carpeta_id);
                $query_archivo->where('carpeta_id', $request->carpeta_id);
                //obtenemos los datos de la carpeta
                $seleccionado = Carpeta::find($request->carpeta_id);
                $seleccionado->ruta = $seleccionado->ruta();
            }
            else {
                $query_carpeta->whereNull('carpeta_id');
                $query_archivo->whereNull('carpeta_id');                
            }

            //buscar texto
            if($request->has('texto')){
                if($request->texto != "") {
                    $query_carpeta->where('nombre', 'like', '%'.$request->texto.'%');
                    $query_archivo->where('nombre', 'like', '%'.$request->texto.'%');
                }
            }
            //solo firmado
            if($request->has('firmado')){
                if($request->firmado == "1") {
                    $query_archivo->where('estado', 2);//0:inicial 1:incrustado 2:firmado
                }
            }

            $carpetas = $query_carpeta->orderBy('created_at', 'desc')->get();
            $archivos = $query_archivo->orderBy('created_at', 'desc')->get();
        }
        elseif($request->ubicacion == 'c') //compartidos conmigo
        {           
            $query_archivo = Archivo::with(['user','compartidos'])->withCount(['compartidos','historicos','documentos'])
                            ->whereHas('compartidos', function ($query) use ($user ) {
                                $query->where('user_id', '=', $user->id);
                            });

            //buscar texto
            if($request->has('texto')) {
                if($request->texto != "") {
                    $query_archivo->where('nombre', 'like', '%'.$request->texto.'%');
                }
            }

            //solo firmado
            if($request->has('firmado')){
                if($request->firmado == "1") {
                    $query_archivo->where('estado', 2);//0:inicial 1:incrustado 2:firmado
                }
            }

            $seleccionado = null; 
            $carpetas = collect(); 
            $archivos = $query_archivo->orderBy('created_at', 'desc')->get();
        }
        else {
            $seleccionado = null; 
            $carpetas = collect(); 
            $archivos = collect(); 
        }

        return response()->json(['seleccionado'=>$seleccionado, 'carpetas'=>$carpetas, 'archivos'=>$archivos], 200);
    }

    /**
     * NUEVO ARCHIVO
     */
    public function nuevo(Request $request)
    {        
        $validator = Validator::make($request->all(), [ 
            'dependencia_id' => 'required',
            'carpeta_id' => 'required',
            'nombre' => 'required',
            'motivo' => 'required',//0:anexo, 1:simple, 2:para firma
        ]);            

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            if($request->hasFile('archivo_subir')) 
            {
                $input = $request->all(); 
                $user = Auth::user(); 
                $this->recursos = new Recursos;

                //obtenemos la extension
                if($request->archivo_subir->getClientOriginalExtension()!="")
                    $extension = strtolower($request->archivo_subir->getClientOriginalExtension());
                else
                    $extension = strtolower($request->archivo_subir->extension());

                if($request->motivo == 2 && $extension != 'pdf')//si es para firma debe ser pdf
                    return response()->json(['message'=>'El archivo debe estar en formato PDF'], 500);                

                //obtenemos el tamaño
                $size = $request->archivo_subir->getSize();
                //subimos el archivo y obtenemos la ruta
                $ruta = Storage::disk($this->disco)->putFile('archivos', $request->file('archivo_subir'));
               
                $archivo = new Archivo;
                //si es anexo no se asocia al usuario o dependencia
                if($request->motivo == 0) {//0:anexo
                    $archivo->dependencia_id =  null;
                    $archivo->user_id = null; 
                    $archivo->carpeta_id =  null; 
                }
                else {
                    $archivo->dependencia_id =  ($request->dependencia_id != 0 ? $request->dependencia_id : null);
                    $archivo->user_id = $user->id; 
                    $archivo->carpeta_id = ($request->carpeta_id != 0 ? $request->carpeta_id : null);
                }

                //si es para firma generamos codigo de verificacion
                if($request->motivo == 2){
                    //obtenemos el codigo
                    $archivo->cvd = $this->obtener_codigo_unico();
                    $archivo->para_firma = 1;
                } 

                $archivo->nombre = $request->nombre;
                $archivo->formato = $extension;
                $archivo->size = $size;
                $archivo->ruta = $ruta;
                $archivo->nombre_real = basename($ruta);
                $archivo->descripcion = $request->has('descripcion') ? $request->descripcion : null;
                $archivo->estado = 0;//inicial
                $archivo->publico = 0;

                if($archivo->save())
                {    
                    $archivo->codigo = $this->recursos->codigo_alpha($archivo->id);
                    $archivo->save();
                    //si es para firma se debe incrustar 
                    if($request->motivo == 2 && $extension == "pdf"){
                        //incrustar codigo
                        if($this->recursos->incrustar_codigo($archivo)){
                            $archivo->estado = 1;//incrustado
                            $archivo->save();
                        }
                    }                    
                    return response()->json(['archivo'=>$archivo, 'message'=>'Cargado correctamente'], 200);
                }
                else
                    return response()->json(['message'=>'No se pudo registrar'], 500);                
            }
            else
                return response()->json(['message'=>'No se encontro el Archivo'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }


    /**
     * MOVER ARCHIVO
     */

    public function mover(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'archivo_id' => 'required',
            'destino_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        //obtenemos el archivo
        $archivo_mover = Archivo::find($request->archivo_id);
        $user = Auth::user(); 

        //validamos
        if($archivo_mover == null)
            return response()->json(['message'=>'No se pudo encontrar el archivo'], 500);
        
        //el usuario tiene permisos sobre el archivo a mover
        if($archivo_mover->dependencia_id != null){
            $empleados_archivo = Empleado::where('dependencia_id', $archivo_mover->dependencia_id)->where('persona_id', $user->persona_id)->where('estado',1)->count();
            if($empleados_archivo <= 0){
                return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);        
            }
        }
        else {
            if($archivo_mover->user_id != $user->id)
                return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);
        }

        if($request->destino_id != 0)//no se mueve a la carpeta raiz
            $carpeta_destino = Carpeta::find($request->destino_id);
        else
            $carpeta_destino = null;

        if($request->destino_id != 0)//si no se mueve a la carpeta raiz se valida la carpeta destino 
        {
            if($carpeta_destino == null)
                return response()->json(['message'=>'No se pudo encontrar la carpeta destino'], 500);

            if($archivo_mover->carpeta_id == $carpeta_destino->id)
                return response()->json(['message'=>'El archivo ya se encuentra en la carpeta destino'], 500);

            if($archivo_mover->dependencia_id != null)//es carpeta de dependencia
            {
                if($archivo_mover->dependencia_id != $carpeta_destino->dependencia_id)
                    return response()->json(['message'=>'La carpeta destino debe pertencer a la misma dependencia'], 500); 
            }
            else 
            {
                if($archivo_mover->user_id != $carpeta_destino->user_id)
                    return response()->json(['message'=>'La carpeta destino debe pertencer al mismo usuario'], 500);
            }    
        }
        else {
            if($archivo_mover->carpeta_id == null)
                return response()->json(['message'=>'El archivo ya se encuentra de dicha ubicación'], 500);  
        }

        $destino_id = ($request->destino_id != 0 ? $request->destino_id : null );
        $archivo_mover->carpeta_id = $destino_id;

        if($archivo_mover->save())            
            return response()->json(['message'=>'Actualizado correctamente'], 200);
        else
            return response()->json(['message'=>'No se pudo mover'], 500);
    }

    /**
     * ELIMINAR ARCHIVO
     */

    public function eliminar(Request $request, $id)
    {
        $archivo = Archivo::withCount(['historicos','anexados','compartidos','documentos'])->find($id);
        $user = Auth::user();

        if(!$archivo)//no se encontro la archivo
            return response()->json(['message'=>'No se encontro el archivo'], 500);

        if($archivo->anexados_count > 0)
            return response()->json(['message'=>'No se pudo eliminar: El archivo esta anexado a un documento'], 500);

        if($archivo->compartidos_count > 0)
            return response()->json(['message'=>'No se pudo eliminar: El archivo esta siendo compartido con alguien mas.'], 500);

        if($archivo->documentos_count > 0)
            return response()->json(['message'=>'No se pudo eliminar: El archivo pertenece a un documento registrado.'], 500);       
        
        //si el archivo no es anexo
        if(!($archivo->user_id == null && $archivo->para_firma == 0 && $archivo->estado == 0)){
            //el usuario tiene permisos sobre el archivo a mover
            if($archivo->dependencia_id != null){
                $empleados_archivo = Empleado::where('dependencia_id', $archivo->dependencia_id)->where('persona_id', $user->persona_id)->where('estado',1)->count();
                if($empleados_archivo <= 0){
                    return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);        
                }
            }
            else {
                if($archivo->user_id != $user->id)
                    return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);
            }        
        }
        
        try 
        {
            //eliminamos el archivo pdf
            Storage::disk($this->disco)->delete($archivo->ruta);
            //si tiene historico elimina los historicos
            Archivo_historico::where('archivo_id',$archivo->id)->delete();            
            //si existiera un documento (eliminado) con el archivo
            if($archivo->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else
                return response()->json(['message'=>'No se pudo eliminar'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    protected function obtener_codigo_unico()//asegurar que codigo pcm es unico
    {
        $nuevo_codigo = $this->recursos->codigo_cvd();
        $existe = Archivo::where('codigo', $nuevo_codigo)->count();
        if($existe > 0){
            return $this->obtener_codigo_unico();
        } else {
            return $nuevo_codigo;
        }
    }



    
}
