<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\Cert_archivo;
use Carbon\Carbon;
use Validator;
use stdClass;

class ConsultaController extends Controller
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
        return view('consulta.index');
    }

    public function firmas()
    {
        return view('consulta.firma');
    }
    
    public function firmas_post(Request $request)
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

    public function constancias()
    {
        return view('consulta.constancia');
    }

    public function constancias_post(Request $request)
    {        
        //validamos codigo
        $validator = Validator::make($request->all(), [             
            'codigo' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'El campo código es requerido.');  
        }

        if(strlen($request->codigo) != 8){
            return back()->with('error', 'El campo código debe tener 8 digitos.');  
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
        $archivo = Cert_archivo::where('codigo', $request->codigo)->first();

        if($archivo == null) {
            return back()->with('error', 'No se pudo encontrar el archivo en los registros.');  
        }
        //publico-->0:no 1:si
        if($archivo->publico != 1) {
            return back()->with('error', 'No se pudo encontrar el archivo en los registros [Publicado].');  
        }
        
        if($archivo->formato != 'pdf')
            return back()->with('error', 'El archivo no tiene un formato válido.');   

        $ruta = Storage::disk($this->disco)->path($archivo->ruta);

        if(!file_exists($ruta))
            return back()->with('error', 'No se pudo encontrar el archivo.');    
    
        return response()->file($ruta);
    }

    public function mesa_partes()
    {
        return view('consulta.mesa_partes');
    }

    public function tramites()
    {
        return view('consulta.consulta');
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
        $back = 'consultas/tramites';
        return view('consulta.seguimiento', compact('tramite','documentos','ordenado','back'));
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
