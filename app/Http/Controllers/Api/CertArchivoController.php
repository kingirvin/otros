<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Utilidades\Recursos;
use App\Utilidades\Compresor;
use Validator;
use App\Models\Cert_carpeta;
use App\Models\Cert_archivo;
use App\Models\Cert_repositorio_user;

class CertArchivoController extends Controller
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
            'cert_repositorio_id' => 'required',
            'cert_carpeta_id' => 'required'
        ]);
     
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $user = Auth::user();
        
        $query_carpeta = Cert_carpeta::with(['user'])->withCount(['archivos','subcarpetas']);
        $query_archivo = Cert_archivo::with(['user']);

        $seleccionado = null;     

        $query_carpeta->where('cert_repositorio_id', $request->cert_repositorio_id);
        $query_archivo->where('cert_repositorio_id', $request->cert_repositorio_id);                
       

        //carpeta
        if($request->cert_carpeta_id != 0){
            $query_carpeta->where('cert_carpeta_id', $request->cert_carpeta_id);
            $query_archivo->where('cert_carpeta_id', $request->cert_carpeta_id);
            //obtenemos los datos de la carpeta
            $seleccionado = Cert_carpeta::find($request->cert_carpeta_id);
            $seleccionado->ruta = $seleccionado->ruta();
        }
        else {
            $query_carpeta->whereNull('cert_carpeta_id');
            $query_archivo->whereNull('cert_carpeta_id');                
        }

        //buscar texto
        if($request->has('texto')){
            if($request->texto != "") {
                $query_carpeta->where('nombre', 'like', '%'.$request->texto.'%');
                $query_archivo->where(function ($query) use ($request) {
                    $query->where('nombre','like', '%'.$request->texto.'%')
                        ->orWhere('codigo','like', '%'.$request->texto.'%')
                        ->orWhere('descripcion','like', '%'.$request->texto.'%');
                });
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

        return response()->json(['seleccionado'=>$seleccionado, 'carpetas'=>$carpetas, 'archivos'=>$archivos], 200);
    }

    /**
     * NUEVO ARCHIVO
     */
    public function nuevo(Request $request)
    {        
        $validator = Validator::make($request->all(), [ 
            'cert_repositorio_id' => 'required',
            'cert_carpeta_id' => 'required',   
            'descripcion' => 'required'             
        ]);            

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            if($request->hasFile('archivos')) 
            {
                $user = Auth::user(); 
                $archivos = $request->file('archivos');

                foreach ($archivos as $archivo_nuevo) {
                    //obtenemos la extension
                    if($archivo_nuevo->getClientOriginalExtension()!="")
                        $extension = $archivo_nuevo->getClientOriginalExtension();
                    else
                        $extension = $archivo_nuevo->extension();
                    //obtenemos el tamaño
                    $size = $archivo_nuevo->getSize();
                    //obtenemos el nombre del archivo
                    $archivo_nombre = $archivo_nuevo->getClientOriginalName();
                    //subimos el archivo y obtenemos la ruta
                    $ruta = Storage::disk($this->disco)->putFile('archivos', $archivo_nuevo);
                
                    $archivo = new Cert_archivo;  
                    $archivo->cert_repositorio_id = $request->cert_repositorio_id;
                    $archivo->user_id = $user->id; 
                    $archivo->cert_carpeta_id = ($request->cert_carpeta_id != 0 ? $request->cert_carpeta_id : null); 
                    $archivo->nombre = $archivo_nombre;
                    $archivo->formato = strtolower($extension);
                    $archivo->size = $size;
                    $archivo->ruta = $ruta;
                    $archivo->nombre_real = basename($ruta);
                    $archivo->descripcion = $request->descripcion;
                    $archivo->para_firma = 1;
                    $archivo->estado = 0;
                    $archivo->publico = 0; 

                    if($archivo->save())
                    {
                        $archivo->codigo = $this->recursos->codigo_alpha($archivo->id + 1000);
                        //incrustar codigo
                        if($this->recursos->incrustar_codigo_certificado($archivo)){
                            $archivo->estado = 1;//incrustado                            
                        }

                        $archivo->save();
                    }
                }

                return response()->json(['message'=>'Cargado correctamente'], 200);              
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
            'cert_archivo_id' => 'required',
            'destino_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        //obtenemos el archivo
        $archivo_mover = Cert_archivo::find($request->cert_archivo_id);
        $user = Auth::user(); 

        //validamos
        if($archivo_mover == null)
            return response()->json(['message'=>'No se pudo encontrar el archivo'], 500);
        
        if($request->destino_id != 0)//no se mueve a la carpeta raiz
            $carpeta_destino = Cert_carpeta::find($request->destino_id);
        else
            $carpeta_destino = null;

        if($request->destino_id != 0)//si no se mueve a la carpeta raiz se valida la carpeta destino 
        {
            if($carpeta_destino == null)
                return response()->json(['message'=>'No se pudo encontrar la carpeta destino'], 500);

            if($archivo_mover->cert_carpeta_id == $carpeta_destino->id)
                return response()->json(['message'=>'El archivo ya se encuentra en la carpeta destino'], 500);
            
            if($archivo_mover->cert_repositorio_id != $carpeta_destino->cert_repositorio_id)
                return response()->json(['message'=>'La carpeta destino debe pertencer al mismo repositorio'], 500);             
        }
        else {
            if($archivo_mover->cert_carpeta_id == null)
                return response()->json(['message'=>'El archivo ya se encuentra de dicha ubicación'], 500);  
        }

        $destino_id = ($request->destino_id != 0 ? $request->destino_id : null );
        $archivo_mover->cert_carpeta_id = $destino_id;

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
        $archivo = Cert_archivo::find($id);
        $user = Auth::user();

        if(!$archivo)//no se encontro la archivo
            return response()->json(['message'=>'No se encontro el archivo'], 500);

        if($archivo->publico == 1)
            return response()->json(['message'=>'No se pudo eliminar: El archivo ya esta publicado'], 500);
     
        $repositorio_user = Cert_repositorio_user::where('cert_repositorio_id', $archivo->cert_repositorio_id)->where('user_id', $user->id)->count();
        if($repositorio_user <= 0){
            return response()->json(['message'=>'El usuario no tiene permisos sobre el archivo'], 500);        
        }

        try 
        {
            //eliminamos el archivo pdf
            Storage::disk($this->disco)->delete($archivo->ruta);          
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


    /**
     * OBTENER ARGUMENTOS PARA REFIRMA PCX
     */
    public function obtener_argumentos(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'primero_id' => 'required',
            'zip_name' => 'required',
            'motivo' => 'required',
            'pos_pagina' => 'required',
            'apariencia'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $archivo = Archivo::find($request->primero_id);
        $zip_code = str_replace("zip_","",str_replace(".7z","",$request->zip_name));

        if ($archivo == null) {
            return response()->json(['message'=>"No se encontro el primer archivo!"], 500);
        }

        if (!file_exists(public_path().'/temp/'.$request->zip_name)) {
            return response()->json(['message'=>"No se encontro el archivo comprimido!"], 500);
        }
       
        $reniec_id = config('app.reniec_id');
        $reniec_secret = config('app.reniec_secret');

        if ($reniec_id == '' | $reniec_secret == '') {
            return response()->json(['message'=>"No se encontraron las claves reniec!"], 500);
        }

        $recursos = new Recursos;
        $ubicacion = $recursos->obtener_pagina($archivo, 0, 1, $request->pos_pagina, $request->apariencia);
        
        $parametros ='{
            "app":"pcx",
            "mode":"lot-p",
            "clientId":"'.$reniec_id.'",
            "clientSecret":"'.$reniec_secret.'",
            "idFile":"archivo_subir",
            "type":"W",
            "protocol":"T",
            "fileDownloadUrl":"'.asset('temp/'.$archivo->zip_name).'",
            "fileDownloadLogoUrl":"",
            "fileDownloadStampUrl":"'.asset('img/unamad_firma.png').'",
            "fileUploadUrl":"'.url('json/repositorios/archivos/firma/'.$zip_code).'/cargar",
            "contentFile":"'.$archivo->zip_name.'",
            "reason":"'.$request->motivo.'",
            "isSignatureVisible":"true",
            "stampAppearanceId":"'.$request->apariencia.'",
            "pageNumber":"'.$ubicacion["pagina"].'",
            "posx":"'.$ubicacion["x"].'",
            "posy":"'.$ubicacion["y"].'",
            "fontSize":"7",		
            "dcfilter":".*FIR.*|.*FAU.*",
            "signatureLevel":"0",
            "maxFileSize":"15728640"
        }';

        //error_log('ENVIO');
        return base64_encode($parametros);
        //return $parametros;
    }


    public function cargar_firmado(Request $request, $id)
    {
        /*$archivo = Archivo::find($id);

        if(!$archivo)
        {
            error_log('No se encontro el archivo');
            return response()->json(['message'=>'No se encontro el archivo'], 500);
        }*/

        if(!$request->hasFile('archivo_subir')) {
            error_log('No se encontro el archivo (FILESYSTEM)');
            return response()->json(['message'=>'No se encontro el archivo (FILESYSTEM)'], 500);
        }
        
        try 
        {
            //cargamos el comprimido
            $ruta = Storage::disk("public_upload")->putFile('temp', $request->file('archivo_subir'));
            //desomcprimimos
            $zip = new Compresor;
            //$zip->
           
           
           
            //actualizamos el original
            $ruta = Storage::disk($this->disco)->putFile('archivos', $request->file('archivo_subir'));
            $archivo->formato = strtolower($extension);
            $archivo->size = $size;
            $archivo->ruta = $ruta;
            $archivo->nombre_real = basename($ruta);
            $archivo->estado = 2;//0:inicial 1:incrustado 2:firmado
            $archivo->save();        

            return response()->json(['archivo'=>$archivo, 'message'=>'Cargado correctamente'], 200);
        }
        catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

}
