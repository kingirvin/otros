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

class CertFirmaController extends Controller
{
    public function __construct()
    {
        $this->disco = config('app.almacenamiento');
        $this->recursos = new Recursos;
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

        $archivo = Cert_archivo::find($request->primero_id);     

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
            "fileDownloadUrl":"'.asset('temp/'.$request->zip_name).'",
            "fileDownloadLogoUrl":"",
            "fileDownloadStampUrl":"'.asset('img/unamad_firma.png').'",
            "fileUploadUrl":"'.url('json/repositorios/archivos/firma/cargar').'",
            "contentFile":"'.$request->zip_name.'",
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


    public function cargar_firmado(Request $request)
    {
        if(!$request->hasFile('archivo_subir')) {           
            return response()->json(['message'=>'No se encontro el archivo (FILESYSTEM)'], 500);
        }
        
        try 
        {
            //cargamos el comprimido
            $ruta = Storage::disk("public_upload")->putFile('temp', $request->file('archivo_subir'));
            $ruta_completa = Storage::disk("public_upload")->path($ruta);//->/temp/aaa.7z         
            $ruta_base = dirname($ruta_completa);//->/temp
            $nombre_archivo = basename($ruta,'.7z');//->aaa
            //descomprimir
            $zip = new Compresor;
            $zip->setRuta($ruta_completa);//ruta de archivo zip -> /temp/aaa.7z   
            $zip->setDestino($ruta_base.'\\');//carpeta destino -> /temp/            
            if(!$zip->descomprimir()){//descomprime en una carpeta con el mismo nombre del archivo zip -> /temp/aaa/*.pdf
                return response()->json(['message'=>'No se pudo descomprimir los archivos firmados.'], 500);
            }
            //iteramos los archivos
            $firmados = Storage::disk('public_upload')->files("temp/".$nombre_archivo);
            foreach ($firmados as $firmado) {    
                //nuevos datos            
                $nombre_firmado = basename($firmado);
                $ruta_firmado = "archivos/".$nombre_firmado;
                //copiamos (se requiere rutas completas de los archivos)
                $ruta_full_firmado = Storage::disk("public_upload")->path($firmado);
                $ruta_full_nuevo = Storage::disk($this->disco)->path($ruta_firmado);
                copy($ruta_full_firmado, $ruta_full_nuevo);
                //si copio correctamente
                if(Storage::disk($this->disco)->exists($ruta_firmado)) {
                    //actualizamos archivo
                    //$old_name = str_replace("[R]", "",$nombre_firmado);//quitamos la R que se le agrego para obtener el nombre original del archivo
                    $old_name = $this->remover("[R]", $nombre_firmado);
                    $cert_archivo = Cert_archivo::where('nombre_real',$old_name)->first();//buscamos el registro por el nombre del archivo
                    if($cert_archivo){
                        $cert_archivo->ruta = $ruta_firmado;
                        $cert_archivo->nombre_real = $nombre_firmado;
                        $cert_archivo->estado = 2;
                        $cert_archivo->save();
                    }      
                }          
            } 

            return response()->json(['message'=>'Cargado correctamente'], 200);
        }
        catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }
    
    public function remover($valor, $texto)//[R], sdsdsd
    {
        $pos = strpos($texto, $valor);
        if($pos !== false){
            $result = substr_replace($texto,"",$pos, strlen($valor));
            return $result;
        } else {
            return $valor;
        }
    }
}
