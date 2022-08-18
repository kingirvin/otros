<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Utilidades\Recursos;
use GuzzleHttp\Client;
use App\Models\Archivo;
use App\Models\Tramite;
use App\Models\Documento;
use App\Models\Documento_anexo;
use App\Models\Movimiento;
use App\Models\Procedimiento;
use App\Models\Documento_tipo;
use App\Models\Dependencia;
use App\Models\Invitado;
use Carbon\Carbon;
use Validator;
use stdClass;

class ExternoController extends Controller
{
    protected $privado;
    protected $disco;
    protected $mesa_partes;

    public function __construct()
    {
        $this->privado = config('app.recaptcha_secret');         
        $this->disco = config('app.almacenamiento');
        $this->mesa_partes = config('app.mesa_partes');
    } 

    public function index()
    { 
        $user = Auth::user();
        $tramites = Tramite::with(['primero_documento.documento_tipo','procedimiento'])->where('o_user_id',$user->id)->orderBy('id','desc')->paginate(50);
        return view('admin.externo.index',compact('tramites'));

    }

    public function ingresar()
    { 
        //0:presencial, 1:virtual //0:interno, 1:universitario, 2:externo
        $procedimientos = Procedimiento::where('tipo', 2)->where('presentar_modalidad', 1)->where('estado', 1)->has('pasos')->get();
        $documento_tipos = Documento_tipo::where('estado', 1)->get();
        $mesa_partes = Dependencia::with('sede')->find($this->mesa_partes);
        return view('admin.externo.ingresar', compact('procedimientos','documento_tipos','mesa_partes'));
    }

    public function ingresar_post(Request $request)
    {
        //validamos campos
        $validator = Validator::make($request->all(), [
            'documento_tipo_id' => 'required', 
            'numero' => 'required', 
            'remitente' => 'required', 
            'folios' => 'required', 
            'asunto' => 'required', 
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        if(!$request->hasFile('archivo_subir')) {
            return back()->with('error', 'No se encontro el archivo principal');   
        }

        if($request->archivo_subir->getClientOriginalExtension()!="")
            $archivo_extension = $request->archivo_subir->getClientOriginalExtension();
        else
            $archivo_extension = $request->archivo_subir->extension();

        if(strtolower($archivo_extension) != 'pdf') {
            return back()->with('error', 'El archivo principal debe ser en formato PDF');   
        }

        $d_dependencia = Dependencia::find($this->mesa_partes);

        if($d_dependencia == null) {
            return back()->with('error', 'El destinatario no esta definido');   
        }

        //validamos recaptcha
        $client = new Client;
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => $this->privado,
                    'response' => $request->get('g-recaptcha-response')
                ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        if(!$body->success) {
            return back()->with('error', 'Captcha incorrecto.');    
        }

        //
        try {

            $recursos = new Recursos;
            $ahora = Carbon::now();
            $user = Auth::user();
            $persona = $user->persona;
            $invitado = Invitado::where('persona_id',$user->persona_id)->where('estado',1)->first();

            //REGISTRAMOS EL ARCHIVO PRINCIPAL
            $archivo_size = $request->archivo_subir->getSize();            
            $archivo_nombre = $request->archivo_subir->getClientOriginalName();
            $archivo_ruta = Storage::disk($this->disco)->putFile('archivos', $request->file('archivo_subir'));

            $archivo = new Archivo;
            $archivo->nombre = $archivo_nombre;
            $archivo->formato = $archivo_extension;
            $archivo->size = $archivo_size;
            $archivo->ruta = $archivo_ruta;
            $archivo->nombre_real = basename($archivo_ruta);
            $archivo->descripcion = "CARGADO POR MESA DE PARTES";
            $archivo->para_firma = 0;//simple
            $archivo->estado = 0;//inicial
            $archivo->publico = 0;

            if($archivo->save()) {
                $archivo->codigo = $recursos->codigo_alpha($archivo->id);
                $archivo->save();
            } else {
                return back()->with('error', 'No se pudo registrar el archivo principal.');   
            }

            //REGISTRAMOS EL TRÁMITE
            $tramite = new Tramite;
            $tramite->year = $ahora->year;
            $tramite->o_tipo = 1;//1:externo
            $tramite->o_externo_tipo = 1;//1:persona externa con usuario  
            $tramite->o_user_id = $user->id;
            //los datos del usuario
            $tramite->o_identidad_documento_id = $persona->identidad_documento_id;
            $tramite->o_nro_documento = $persona->nro_documento;
            $tramite->o_nombre = $persona->nombre;
            $tramite->o_apaterno = $persona->apaterno;
            $tramite->o_amaterno = $persona->amaterno;
            $tramite->o_telefono = $persona->telefono;
            $tramite->o_correo = $persona->correo;
            $tramite->o_direccion = $persona->direccion;
            if($invitado != null){
                $tramite->ruc = $invitado->ruc;
                $tramite->razon_social = $invitado->razon_social;
            }

            if($request->has('procedimiento_id')){
                $tramite->procedimiento_id = ($request->procedimiento_id != 0 ? $request->procedimiento_id : null);
            }

            $tramite->observaciones = "CREADO POR MESA DE PARTES";
            $tramite->user_id = $user->id;
            $tramite->estado = 1;//1:activo

            if (!$tramite->save()) {
                return back()->with('error', 'No se pudo registrar el trámite.');   
            }

            $tramite->codigo = $recursos->codigo_alpha($tramite->id);
            $tramite->save();

            //REGISTRAMOS EL DOCUMENTO
            $documento = new Documento;
            $documento->year = $ahora->year;
            $documento->tramite_id = $tramite->id;
            $documento->documento_tipo_id = $request->documento_tipo_id;
            $documento->numero = $request->numero;
            $documento->remitente = $request->remitente;
            $documento->asunto = $request->asunto;
            $documento->folios = $request->folios;            
            $documento->observaciones = "CARGADO POR MESA DE PARTES";   
            $documento->anexos_url = $request->anexos_url;       
            $documento->archivo_id = $archivo->id;
            $documento->user_id = $user->id;

            if (!$documento->save()) {
                return back()->with('error', 'No se pudo registrar el documento.');
            }

            $documento->codigo = $recursos->codigo_alpha($documento->id);
            $documento->save();          

            //REGISTRAMOS LOS ARCHIVOS ANEXOS
            if($request->hasFile('archivo_anexos'))
            {
                $anexos = $request->file('archivo_anexos');
                foreach ($anexos as $anexo) {

                    //obtenemos la extension
                    if($anexo->getClientOriginalExtension()!="")
                        $a_extension = $anexo->getClientOriginalExtension();
                    else
                        $a_extension = $anexo->extension();
                    //obtenemos el tamaño
                    $a_size = $anexo->getSize();
                    //obtenemos el nombre del archivo
                    $a_archivo_nombre = $anexo->getClientOriginalName();
                    //subimos el archivo y obtenemos la ruta
                    $a_ruta = Storage::disk($this->disco)->putFile('archivos', $anexo);
                
                    $a_archivo = new Archivo;  
                    $a_archivo->user_id = null;  
                    $a_archivo->carpeta_id = null;
                    $a_archivo->nombre = $a_archivo_nombre;
                    $a_archivo->formato = strtolower($a_extension);
                    $a_archivo->size = $a_size;
                    $a_archivo->ruta = $a_ruta;
                    $a_archivo->nombre_real = basename($a_ruta);
                    $a_archivo->descripcion = "CARGADO POR MESA DE PARTES";
                    $a_archivo->estado = 1;//CARGADO
                    $a_archivo->publico = 1;

                    if($a_archivo->save())
                    {
                        $a_archivo->codigo = $recursos->codigo_alpha($archivo->id);
                        $a_archivo->save();                
                    }

                    $documento_anexo = new Documento_anexo;
                    $documento_anexo->documento_id = $documento->id;
                    $documento_anexo->archivo_id = $a_archivo->id;
                    $documento_anexo->principal = 1;
                    $documento_anexo->save(); 
                }
            }

            //REGISTRAMOS EL MOVIMIENTO (RECEPCION)
            $movimiento = new Movimiento;
            $movimiento->tramite_id = $tramite->id;
            $movimiento->documento_id = $documento->id;                
            $movimiento->tipo = 0;//0:inicio de trámite
            $movimiento->copia = 0;   
            //-------------------------------------------quien envia        
            $movimiento->o_tipo = 1;//1:externo  

            if($invitado != null){//1:Persona Jurídica            
                $movimiento->o_descripcion = $invitado->ruc." | ".$invitado->razon_social;
            } else {
                $movimiento->o_descripcion = $persona->nro_documento." | ".$persona->nombre." ".$persona->apaterno." ".$persona->amaterno;
            }       
            
            $movimiento->o_fecha = $ahora->format('Y-m-d H:i:s');
            $movimiento->o_user_id = $user->id;
            $movimiento->o_year = $ahora->year;
            //-------------------------------------------quien recibe
            $movimiento->d_tipo = 0;//0:interno
            $movimiento->d_dependencia_id = $d_dependencia->id;
            $movimiento->estado = 1;//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado     
            $movimiento->save();           

            return back()->with('correcto', 'Registrado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        } 
    }

    public function consulta()
    { 
        return view('admin.externo.consulta');
    }

    public function seguimiento(Request $request)
    {
        //validamos campos
        $validator = Validator::make($request->all(), [             
            'cut' => 'required|min:8',
            'fecha' => 'required|date_format:d/m/Y'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        //validamos recaptcha
        $client = new Client;
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => $this->privado,
                    'response' => $request->get('g-recaptcha-response')
                ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        if(!$body->success) {
            return back()->with('error', 'Captcha incorrecto.');    
        }

        //validamos tramite
        $fecha_formato = Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');        
        $tramite = Tramite::with(['user','o_dependencia','procedimiento'])
                    ->where('codigo', $request->cut)
                    ->whereDate('created_at', $fecha_formato)
                    ->first();
        
        if($tramite == null)
            return back()->with('error', 'No se pudo encontrar el trámite con los datos proporcionados.');  
        
        $documentos = Documento::with(['documento_tipo','archivo','anexos'])->where('tramite_id', $tramite->id)->orderBy('id', 'desc')->get();
        $movimientos = Movimiento::with(['documento','accion','o_user','d_user','f_user','d_dependencia.sede','d_persona','observaciones.user'])->where('tramite_id', $tramite->id)->get();
        $ordenado = $this->ordenar($movimientos, null);
        $back = 'admin/externo/consulta';
        return view('admin.externo.seguimiento', compact('tramite','documentos','ordenado','back'));
    }

    public function seguimiento_tramite(Request $request, $codigo)
    {        
        $user = Auth::user();
        $tramite = Tramite::with(['user','o_dependencia','procedimiento'])
                    ->where('codigo', $codigo)
                    ->where('o_user_id',$user->id)
                    ->first();
        
        if($tramite == null)
            return back()->with('error', 'No se pudo encontrar el trámite asociado a tu usuario.');  
        
        $documentos = Documento::with(['documento_tipo','archivo','anexos'])->where('tramite_id', $tramite->id)->orderBy('id', 'desc')->get();
        $movimientos = Movimiento::with(['documento','accion','o_user','d_user','f_user','d_dependencia.sede','d_persona','observaciones.user'])->where('tramite_id', $tramite->id)->get();
        $ordenado = $this->ordenar($movimientos, null);
        $back = 'admin/externo';
        return view('admin.externo.seguimiento', compact('tramite','documentos','ordenado','back'));
    }

    public function validar()
    { 
        return view('admin.externo.validar');
    }

    public function validar_post(Request $request)
    {
        //validamos codigo
        $validator = Validator::make($request->all(), [             
            'codigo' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'El campo código es requerido.');  
        }

        $codigo_value = preg_replace("/[^0-9]/", "", $request->codigo);
        if(strlen($codigo_value) != 16){
            return back()->with('error', 'El campo código debe tener 16 digitos.');  
        }

        //validamos recaptcha
        $client = new Client;
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => $this->privado,
                    'response' => $request->get('g-recaptcha-response')
                ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        if(!$body->success) {
            return back()->with('error', 'Captcha incorrecto.');    
        }

        //validamos archivo
        $archivo = Archivo::where('cvd', $codigo_value)->first();

        if($archivo == null) {
            return back()->with('error', 'No se pudo encontrar el archivo en los registros.');  
        }
        
        if($archivo->formato != 'pdf')
            return back()->with('error', 'El archivo no tiene un formato válido.');   

        $ruta = Storage::disk($this->disco)->path($archivo->ruta);

        if(!file_exists($ruta))
            return back()->with('error', 'No se pudo encontrar el archivo.');    
    
        return response()->file($ruta);
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
}
