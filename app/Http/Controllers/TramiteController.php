<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Utilidades\Recursos;
use App\Models\Empleado;
use App\Models\Archivo;
use App\Models\Procedimiento;
use App\Models\Sede;
use App\Models\Documento_tipo;
use App\Models\Identidad_documento;
use App\Models\Dependencia;
use App\Models\Movimiento;
use App\Models\Tramite;
use App\Models\Documento;
use App\Models\Accion;
use App\Models\Movimiento_asignacion;
use Carbon\Carbon;
use stdClass;

class TramiteController extends Controller
{
    /**
     * ADMINISTRACIÓN MODULO TRAMITE DOCUMENTARIO
     */
    public function index()
    {
        $user = Auth::user();
        if($user->persona_id != null){
            $origenes = Empleado::with('dependencia.sede')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();
        } else {
            $origenes = collect();
        }
        return view('admin.tramite.index',compact('origenes'));
    }

    /**
     * ENVIO DE DOCUMENTOS
     */
    public function nuevo(Request $request)
    {
        $procedimientos = Procedimiento::where('tipo', 0)->where('estado', 1)->has('pasos')->get();
        $user = Auth::user();
        $ahora = Carbon::now();
        //posee datos de persona
        if($user->persona_id == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No posees datos de persona", 'mensaje' => "No tienes registrado datos de PERSONA, ponte en contacto con el administrador del sistema para su registro.", 'accion' => "back" )]);  
        }
        //dependencias a la cual pertenece el usuario
        $origenes = Empleado::with('dependencia')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();
        $origen_actual = $request->has('origen') ? $request->origen : $origenes[0]->dependencia_id;        
        if(!$origenes->contains('dependencia_id', $origen_actual)){//el origen actual esta dentro de los origenes
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }
        //empleados de la dependencia actual
        $empleados = Empleado::with(['persona.identidad_documento'])->where('estado', 1)->where('dependencia_id', '=', $origen_actual)->get();
        $empleado_actual = null;
        foreach ($empleados as $empleado) {
            if($empleado->persona_id == $user->persona_id){
                $empleado_actual = $empleado;
                break;
            }
        }

        if($empleado_actual == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        //sedes y dependencias
        $sedes = Sede::where('estado', 1)->get();
        if(count($sedes) > 0)
            $dependencias = Dependencia::where('sede_id', $sedes[0]->id)->where('estado', 1)->orderBy('nombre', 'asc')->get();
        else
            $dependencias = collect();   
        //tipo de documento (gestion e identidad)
        $identidad_tipos = Identidad_documento::where('estado', 1)->get();
        $documento_tipos = Documento_tipo::where('estado', 1)->get();
        return view('admin.tramite.nuevo',compact('procedimientos','origenes','origen_actual','empleados','empleado_actual','sedes','dependencias','identidad_tipos','documento_tipos','user'));
    }

    /**
     * DOCUMENTOS EMITIDOS (NUEVO, EDIRVADO)
     */
    public function emitidos(Request $request)
    {
        $user = Auth::user();
        $ahora = Carbon::now();

        if($user->persona_id == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No posees datos de persona", 'mensaje' => "No tienes registrado datos de PERSONA, ponte en contacto con el administrador del sistema para su registro.", 'accion' => "back" )]);  
        }

        $documento_tipos = Documento_tipo::where('estado', 1)->get();
        $origenes = Empleado::with('dependencia')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();
        $origen_actual = $request->has('origen') ? $request->origen : $origenes[0]->dependencia_id;

        //el origen actual esta dentro de los origenes
        if(!$origenes->contains('dependencia_id', $origen_actual)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        $empleados = Empleado::with(['persona.identidad_documento'])->where('estado', 1)->where('dependencia_id', '=', $origen_actual)->get();
        $empleado_actual = null;
        foreach ($empleados as $empleado) {
            if($empleado->persona_id == $user->persona_id){
                $empleado_actual = $empleado;
                break;
            }
        }

        if($empleado_actual == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        return view('admin.tramite.emitidos', compact('origenes','origen_actual','empleados','empleado_actual','ahora','user','documento_tipos'));
    }

    /**
     * RECEPCION DE DOCUMENTOS
     */
    public function recibir(Request $request)
    {
        $user = Auth::user();
        $ahora = Carbon::now();

        if($user->persona_id == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No posees datos de persona", 'mensaje' => "No tienes registrado datos de PERSONA, ponte en contacto con el administrador del sistema para su registro.", 'accion' => "back" )]);  
        }

        $destinos = Empleado::with('dependencia')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();
        $destino_actual = $request->has('destino') ? $request->destino : $destinos[0]->dependencia_id;

        //el destino actual esta dentro de los origenes
        if(!$destinos->contains('dependencia_id', $destino_actual)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        $empleados = Empleado::with(['persona.identidad_documento'])->where('estado', 1)->where('dependencia_id', '=', $destino_actual)->get();
        $empleado_actual = null;
        foreach ($empleados as $empleado) {
            if($empleado->persona_id == $user->persona_id){
                $empleado_actual = $empleado;
                break;
            }
        }

        if($empleado_actual == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        return view('admin.tramite.por_recibir',compact('destinos','destino_actual','empleados','empleado_actual','ahora','user'));
    }

    /**
     * RECIBIR DOCUMENTO EXTERNO SIMPLE
     */
    public function externo(Request $request)
    {
        $procedimientos = Procedimiento::where('tipo', 2)->where('estado', 1)->has('pasos')->get();//tipo: 0:interno, 1:universitario, 2:externo
        $user = Auth::user();
        $ahora = Carbon::now();

        if($user->persona_id == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No posees datos de persona", 'mensaje' => "No tienes registrado datos de PERSONA, ponte en contacto con el administrador del sistema para su registro.", 'accion' => "back" )]);  
        }

        $destinos = Empleado::with('dependencia')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();
        $destino_actual = $request->has('destino') ? $request->destino : $destinos[0]->dependencia_id;

        //el destino actual esta dentro de los origenes
        if(!$destinos->contains('dependencia_id', $destino_actual)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        $empleados = Empleado::with(['persona.identidad_documento'])->where('estado', 1)->where('dependencia_id', '=', $destino_actual)->get();
        
        //tipo de documento (gestion e identidad)
        $identidad_tipos = Identidad_documento::where('estado', 1)->get();
        $documento_tipos = Documento_tipo::where('estado', 1)->get();  
        
        return view('admin.tramite.externo',compact('procedimientos','destinos','empleados','destino_actual','identidad_tipos','documento_tipos'));
    }

    /**
     * CUADERNO DE REGISTRO
     */
    public function recibidos(Request $request)
    {        
        $user = Auth::user();
        $ahora = Carbon::now();

        if($user->persona_id == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No posees datos de persona", 'mensaje' => "No tienes registrado datos de PERSONA, ponte en contacto con el administrador del sistema para su registro.", 'accion' => "back" )]);  
        }

        $destinos = Empleado::with('dependencia')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();
        $destino_actual = $request->has('destino') ? $request->destino : $destinos[0]->dependencia_id;
        //el destino actual esta dentro de los origenes
        if(!$destinos->contains('dependencia_id', $destino_actual)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        $empleados = Empleado::with(['persona.identidad_documento'])->where('estado', 1)->where('dependencia_id', '=', $destino_actual)->get();
        $empleado_actual = null;
        foreach ($empleados as $empleado) {
            if($empleado->persona_id == $user->persona_id){
                $empleado_actual = $empleado;
                break;
            }
        }

        if($empleado_actual == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
        }

        return view('admin.tramite.recibidos', compact('destinos','destino_actual','empleados','empleado_actual','ahora','user'));
    }

    /**
     * DERIVAR DOCUMENTO
     */
    public function derivar(Request $request, $id)
    {
        $movimiento = Movimiento::with(['d_dependencia','d_persona','documento.documento_tipo','tramite'])->find($id);
        if($movimiento == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se pudo encontrar el movimiento", 'mensaje' => "El registro seleccionado ya no se encuentra disponible.", 'accion' => "close" )]);  
        
        if($movimiento->estado == 2 || $movimiento->estado == 3)//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado     
        {
            $procedimientos = Procedimiento::where('tipo', 0)->where('estado', 1)->has('pasos')->get();
            $user = Auth::user();
            $ahora = Carbon::now();

            if($user->persona_id == null){
                return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se datos de Persona", 'mensaje' => "No tienes registrado datos de PERSONA, ponte en contacto con el administrador del sistema para su registro.", 'accion' => "close" )]);  
            }
           
            $sedes = Sede::where('estado', 1)->get();
            if(count($sedes) > 0)
                $dependencias = Dependencia::where('sede_id', $sedes[0]->id)->where('estado', 1)->orderBy('nombre', 'asc')->get();
            else
                $dependencias = collect();  

            $origenes = Empleado::with('dependencia')->where('persona_id', $user->persona_id)->where('estado', 1)->orderBy('created_at', 'desc')->get();

            //empleados de la dependencia actual
            $empleados = Empleado::with(['persona.identidad_documento'])->where('estado', 1)->where('dependencia_id', '=', $movimiento->d_dependencia_id)->get();
            $empleado_actual = null;
            foreach ($empleados as $empleado) {
                if($empleado->persona_id == $user->persona_id){
                    $empleado_actual = $empleado;
                    break;
                }
            }

            if($empleado_actual == null){
                return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No perteneces a la dependencia", 'mensaje' => "El usuario actual no pertenece a la dependencia seleccionada.", 'accion' => "back" )]);  
            }

            $identidad_tipos = Identidad_documento::where('estado', 1)->get();
            $documento_tipos = Documento_tipo::where('estado', 1)->get();
            $acciones = Accion::where('estado',1)->orderBy('orden')->get();

            return view('admin.tramite.derivar', compact('movimiento','user','sedes','origenes','empleados','empleado_actual','dependencias','ahora','identidad_tipos','documento_tipos','acciones'));
        }
        else {
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encuentra habilitado para derivar", 'mensaje' => "El registro seleccionado debe estar en estado pendiente o derivado.", 'accion' => "close" )]);  
        }        
    }

    /**
     * EDITAR DERIVACIONES
     */
    public function derivaciones(Request $request, $id)
    {
        $movimiento = Movimiento::with(['d_dependencia','documento.documento_tipo','tramite'])->find($id);
        if($movimiento == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se pudo encontrar el movimiento", 'mensaje' => "El registro seleccionado ya no se encuentra disponible.", 'accion' => "close" )]);  

        $siguientes = Movimiento::with(['d_dependencia','documento.documento_tipo','tramite'])->where('anterior_id', $movimiento->id)->get();
        return view('admin.tramite.derivaciones', compact('movimiento','siguientes'));
    }

    public function seguimiento(Request $request, $id)
    {
        $tramite = Tramite::with(['user','o_dependencia','procedimiento'])->where('id', $id)->first();
        if($tramite == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se pudo encontrar el trámite", 'mensaje' => "El registro seleccionado ya no se encuentra disponible.", 'accion' => "close" )]);  

        $documentos = Documento::with(['documento_tipo','archivo','anexos'])->where('tramite_id', $tramite->id)->orderBy('id', 'desc')->get();
        $movimientos = Movimiento::with(['documento','accion','o_user','d_user','f_user','d_dependencia.sede','d_persona','observaciones.user'])->where('tramite_id', $tramite->id)->get();
        $ordenado = $this->ordenar($movimientos, null);
        return view('admin.tramite.seguimiento', compact('tramite','documentos','ordenado'));
    }

    public function hoja(Request $request, $id)
    {
        $tramite = Tramite::with(['user','o_dependencia.sede','procedimiento'])->where('id', $id)->first();
        if($tramite == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se pudo encontrar el trámite", 'mensaje' => "El registro seleccionado ya no se encuentra disponible.", 'accion' => "close" )]);  

        $documento = Documento::with(['documento_tipo','archivo','anexos'])->where('tramite_id', $tramite->id)->orderBy('id', 'asc')->first();
        $movimientos = Movimiento::with(['d_dependencia.sede'])->where('tramite_id', $tramite->id)->where('tipo', 0)->get();        
        return view('reporte.hoja_tramite', compact('tramite','documento','movimientos'));
    }
   
    protected function ordenar($movimientos, $anterior)
    {
        $resultado = collect();        
        foreach ($movimientos as $movimiento) {
            if($movimiento->anterior_id == $anterior)
            {
                //destinos
                $elemento = new stdClass();
                $elemento->id = $movimiento->id;
                $elemento->documento = $movimiento->documento->codigo;
                $elemento->enviado = $movimiento->o_fecha;//fecha de envio
                //destino (0:interno, 1:externo)
                if($movimiento->d_tipo == 0)//interno
                {
                    $elemento->personal = ($movimiento->d_persona ? $movimiento->d_persona->nombre.' '.$movimiento->d_persona->apaterno.' '.$movimiento->d_persona->amaterno : '');
                    $elemento->nombre = $movimiento->d_dependencia->nombre;
                    $elemento->detalle = $movimiento->d_dependencia->sede->nombre;
                }
                else//externo
                {
                    $elemento->personal = '';
                    $elemento->nombre = $movimiento->d_nombre;
                    $elemento->detalle = $movimiento->d_nro_documento;
                }
                //quien envia
                if($movimiento->o_user_id != null) {
                    $elemento->envia_nombre = $movimiento->o_user->nombre.' '.$movimiento->o_user->apaterno.' '.$movimiento->o_user->amaterno;
                    $elemento->envia_siglas = $movimiento->o_user->siglas;
                }
                else {
                    $elemento->envia_nombre = "ORIGEN EXTERNO";
                    $elemento->envia_siglas = "EX";
                }
                //quien recibe 
                if($movimiento->d_user_id != null) {
                    $elemento->recibe_nombre = $movimiento->d_user->nombre.' '.$movimiento->d_user->apaterno.' '.$movimiento->d_user->amaterno;
                    $elemento->recibe_siglas = $movimiento->d_user->siglas;
                }
                else {
                    $elemento->recibe_nombre = "-";
                    $elemento->recibe_siglas = "-";
                }
                //quien atiende
                if($movimiento->f_user_id != null) {
                    $elemento->atendido_nombre = $movimiento->f_user->nombre.' '.$movimiento->f_user->apaterno.' '.$movimiento->f_user->amaterno;
                    $elemento->atendido_siglas = $movimiento->f_user->siglas;
                    $elemento->atendido_observacion =  $movimiento->f_observacion;
                }
                else {
                    $elemento->atendido_nombre = "-";
                    $elemento->atendido_siglas = "-";
                    $elemento->atendido_observacion =  null;
                }
                //observaciones
                if($movimiento->estado == 5){
                    $elemento->observaciones = $movimiento->observaciones;
                } else {
                    $elemento->observaciones = null;
                }

                //acciones
                if($movimiento->accion_id != null){
                    $elemento->accion = $movimiento->accion->nombre;
                    $elemento->accion_otros = $movimiento->accion_otros;
                } else {
                    $elemento->accion = null;
                    $elemento->accion_otros = null;
                }

                $elemento->recibido = $movimiento->d_fecha;
                $elemento->atendido = $movimiento->f_fecha;                
                $elemento->estado = $movimiento->estado;
                $elemento->despues = $this->ordenar($movimientos, $movimiento->id);
                $resultado->push($elemento);
            }
        }

        $count = 0;
        foreach ($resultado as $elemento) {
            $elemento->numero = $count;
            $elemento->total = count($resultado);
            $count++;
        }

        return $resultado;
    }
    
    public function documento(Request $request, $id)
    {
        $documento = Documento::with(['documento_tipo', 'archivo','anexos.archivo'])->where('id', $id)->first();
        if($documento == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se pudo encontrar el documento", 'mensaje' => "El registro seleccionado ya no se encuentra disponible.", 'accion' => "close" )]);  

        return view('admin.tramite.documento', compact('documento'));
    }
    
    public function asignaciones(Request $request, $id)
    {
        $movimiento = Movimiento::with(['d_dependencia','documento.documento_tipo','tramite'])->find($id);
        if($movimiento == null)
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se pudo encontrar el movimiento", 'mensaje' => "El registro seleccionado ya no se encuentra disponible.", 'accion' => "close" )]);  
       
        $empleados = Empleado::with('persona')->where('dependencia_id',$movimiento->d_dependencia_id)->where('estado',1)->get();
        $acciones = Accion::where('estado',1)->orderBy('orden')->get();
        return view('admin.tramite.asignaciones', compact('movimiento','empleados','acciones'));
    }

    /**
     * ARCHIVOS DIGITALES
     */    
    public function archivos(Request $request)
    {
        $ubicacion = $request->has('ubicacion') ? $request->ubicacion : "m";//m:mis archivos por defecto
        $user = Auth::user();
        $empleos = Empleado::where('persona_id',$user->persona_id)->where('estado',1)->with('dependencia')->get();
        return view('admin.tramite.archivos',compact('ubicacion','empleos','user'));
    }

    public function firma(Request $request, $id)
    {
        $archivo = Archivo::where('codigo',$id)->first();
        $disco = config('app.almacenamiento');
        $recursos = new Recursos;

        if($archivo == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado no se encuentra en nuestros registros.", 'accion' => "back" )]);  
        }

        if($archivo->para_firma != 1) {
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Archivo no válido", 'mensaje' => "El archivo no esta en estado para firma.", 'accion' => "back" )]);  
        }

        $archivo->informacion = $recursos->datos_firma($archivo);
        $firma_dimenciones = $recursos->firma_dimenciones;
        return view('admin.tramite.firma',compact('archivo','firma_dimenciones'));
    }





    /**
     * VISTA PREVIA PDF (solo requiere login)
     */    
    public function vista_previa(Request $request, $codigo)
    {
        $archivo = Archivo::where('codigo',$codigo)->first();
        $disco = config('app.almacenamiento');

        if($archivo == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado no se encuentra en nuestros registros.", 'accion' => "close" )]);  
        }

        $ruta = Storage::disk($disco)->path($archivo->ruta);

        if(!file_exists($ruta)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado ya no se encuentra en nuestro almacenamiento.", 'accion' => "close" )]); 
        }

        return response()->file($ruta);   
    }

    /**
     * DESCARGAR ARCHIVO (solo requiere login)
     */
    public function descargar(Request $request, $id)
    {
        $archivo = Archivo::where('id', $id)->first();
        $disco = config('app.almacenamiento');
        $headers = array();

        if($archivo == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado no se encuentra en nuestros registros.", 'accion' => "close" )]);   
        }

        $ruta = Storage::disk($disco)->path($archivo->ruta);

        if(!file_exists($ruta)){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "No se encontro el archivo", 'mensaje' => "El archivo seleccionado ya no se encuentra en nuestro almacenamiento.", 'accion' => "close" )]); 
        }

        return response()->download($ruta, $archivo->nombre, $headers);
    }

}
