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
}
