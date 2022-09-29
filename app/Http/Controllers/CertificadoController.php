<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Utilidades\Recursos;
use App\Models\Cert_repositorio;
use App\Models\Cert_repositorio_user;
use App\Models\Cert_archivo;
use Carbon\Carbon;
use stdClass;
use App\Utilidades\Compresor;

class CertificadoController extends Controller
{
    public function index()
    { 
        $user = Auth::user();
        $repositorios = Cert_repositorio_user::with('repositorio')->where('user_id',$user->id)->get();
        return view('admin.certificado.index', compact('repositorios'));        
    }

    public function administrar(Request $request)
    {
        return view('admin.certificado.administrar');
    }

    public function publicar(Request $request)
    {
        $user = Auth::user();
        $repositorios = Cert_repositorio_user::with('repositorio')->where('user_id',$user->id)->get();
        return view('admin.certificado.publicar', compact('repositorios'));
    }

    public function vista_previa(Request $request, $codigo)
    {
        $archivo = Cert_archivo::where('codigo',$codigo)->first();
        $disco = config('app.almacenamiento');
        $headers = array();

        if($archivo == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado no se encuentra en nuestros registros.", 'accion' => "close" )]);  
        }

        $ruta = Storage::disk($disco)->path($archivo->ruta);

        if(!file_exists($ruta)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado ya no se encuentra en nuestro almacenamiento.", 'accion' => "close" )]); 
        }

        if($archivo->formato == 'pdf')
            return response()->file($ruta);   
        else
            return response()->download($ruta, $archivo->nombre, $headers);       
        
    }

    public function firma(Request $request)
    {
        //validamos parametros
        if(!$request->has('arch')) {
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "No se enviaron los parámetros necesarios.", 'accion' => "back" )]);  
        }

        $todos = $request->arch;
        if(count($todos) == 0){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "No se enviaron los parámetros necesarios.", 'accion' => "back" )]);  
        }

        //validamos archivos
        $archivos = Cert_archivo::whereIn('id', $todos)->get();

        if(count($todos) != count($archivos)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error en los archivos", 'mensaje' => "No se encontro alguno de los archivos seleccionados en los registros.", 'accion' => "back" )]);  
        }

        //error_log(count($todos)." ---------- ".count($archivos));

        foreach ($archivos as $archivo) {
            if($archivo->estado == 0){
                return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error en los archivos", 'mensaje' => "Alguno de los archivos seleccionados se encuentra eb estado ERROR.", 'accion' => "back" )]);  
            }

            if($archivo->publico == 1){
                return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error en los archivos", 'mensaje' => "Alguno de los archivos seleccionados ya ha sido publicado.", 'accion' => "back" )]);  
            }
        }        
        
        $disco = config('app.almacenamiento');
        //generamos el zip
        $carpeta_publica = public_path();        
        $ahora = Carbon::now();
        $zip_nombre = 'zip_'.$ahora->format('YmdHisu').'.7z';
        $zip_full_nombre = public_path().'/temp/'.$zip_nombre;

        $zip = new Compresor;
        $zip->setRuta($zip_full_nombre);
        foreach ($archivos as $archivo) {
            $ruta = Storage::disk($disco)->path($archivo->ruta);
            $zip->addArchivo($ruta);
        }
       
        if(!$zip->comprimir()){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error en la compresión", 'mensaje' => "No se pudo generar el archivo 7z.", 'accion' => "back" )]);  
        } 

        $primero = $archivos[0];
        $recursos = new Recursos;
        $primero->informacion = $recursos->datos_firma($primero);
        $firma_dimenciones = $recursos->firma_dimenciones;
        return view('admin.certificado.firma',compact('primero','todos','zip_nombre','firma_dimenciones'));
    }
}
