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

class FirmaController extends Controller
{
    protected $disco; 

    public function __construct()
    {
        $this->disco = config('app.almacenamiento');
    } 

    /**
     * OBTENER ARGUMENTOS BASE64 PARA FIRMA RENIEC
     */
    public function obtener_argumentos(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'archivo_id' => 'required',
            'num_pagina' => 'required',
            'motivo' => 'required',
            'exacto' => 'required',
            'pos_pagina' => 'required',
            'apariencia'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $archivo = Archivo::find($request->archivo_id);
        if ($archivo == null) {
            return response()->json(['message'=>"No se encontro el archivo!"], 500);
        }

        if(!Storage::disk($this->disco)->exists($archivo->ruta)) {
            return response()->json(['message'=>"No se encontro el archivo (FILESYSTEM)!"], 500);
        }

        $reniec_id = config('app.reniec_id');
        $reniec_secret = config('app.reniec_secret');

        if ($reniec_id == '' | $reniec_secret == '') {
            return response()->json(['message'=>"No se encontraron las claves reniec!"], 500);
        }

        $recursos = new Recursos;
        $ubicacion = $recursos->obtener_pagina($archivo, $request->num_pagina, $request->exacto, $request->pos_pagina, $request->apariencia);
        
        $parametros ='{
            "app":"pdf",
            "fileUploadUrl":"'.url('json/firma/'.$archivo->id).'/cargar",
            "reason":"'.$request->motivo.'",
            "type":"W",
            "clientId":"'.$reniec_id.'",
            "clientSecret":"'.$reniec_secret.'",
            "dcfilter":".*FIR.*|.*FAU.*",
            "fileDownloadUrl":"'.url('json/firma/'.$archivo->codigo).'/descargar",
            "fileDownloadLogoUrl":"",
            "posx":"'.$ubicacion["x"].'",
            "posy":"'.$ubicacion["y"].'",
            "outputFile":"'.pathinfo($archivo->ruta, PATHINFO_FILENAME).'[R].pdf",
            "protocol":"T",
            "contentFile":"'.$archivo->nombre_real.'",
            "stampAppearanceId":"'.$request->apariencia.'",
            "isSignatureVisible":"true",
            "idFile":"archivo_subir",
            "fileDownloadStampUrl":"'.asset('img/unamad_firma.png').'",
            "pageNumber":"'.$ubicacion["pagina"].'",
            "maxFileSize":"15728640",
            "fontSize":"7",			
            "timestamp":"false"
        }';

        //error_log('ENVIO');
        return base64_encode($parametros);
        //return $parametros;
    }

    /**
     * DESCARGA PARA FIRMA POR RENIEC
     */
    public function descargar_firma(Request $request, $id)
    {
        $archivo = Archivo::where('codigo', $id)->first();
        $headers = array();

        if($archivo == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado no se encuentra en nuestros registros.", 'accion' => "close" )]);  
            
        $ruta = Storage::disk($this->disco)->path($archivo->ruta);

        if(!file_exists($ruta))
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado ya no se encuentra en nuestro almacenamiento.", 'accion' => "close" )]);

        return response()->download($ruta, $archivo->nombre, $headers);

    }

    /**
     * CARGAR DOCUMENTO YA FIRMADO DESDE REFIRMA
     */
    public function cargar_firmado(Request $request, $id)
    {
        $archivo = Archivo::find($id);

        if(!$archivo)
        {
            error_log('No se encontro el archivo');
            return response()->json(['message'=>'No se encontro el archivo'], 500);
        }

        if(!$request->hasFile('archivo_subir')) {
            error_log('No se encontro el archivo (FILESYSTEM)');
            return response()->json(['message'=>'No se encontro el archivo (FILESYSTEM)'], 500);
        }
        
        try 
        {
            if($request->archivo_subir->getClientOriginalExtension()!="")
                $extension = $request->archivo_subir->getClientOriginalExtension();
            else
                $extension = $request->archivo_subir->extension();
        
            $size = $request->archivo_subir->getSize();

            //guardamos un historico
            $historico = new Archivo_historico;
            $historico->archivo_id = $archivo->id;
            $historico->user_id = $archivo->user_id;
            $historico->formato = $archivo->formato;
            $historico->ruta = $archivo->ruta;
            $historico->nombre_real = $archivo->nombre_real;
            $historico->size = $archivo->size;            
            $historico->estado = $archivo->estado;
            $historico->created_at = $archivo->created_at;
            $historico->save();
           
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
