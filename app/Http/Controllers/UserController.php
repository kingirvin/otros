<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Persona;
use App\Models\Invitado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Identidad_documento;
use GuzzleHttp\Client;

class UserController extends Controller
{
    /**
     * PAGINA LOGIN
     */
    public function ingreso()
    {
        if (Auth::check()) {
            return redirect('admin');
        } else {
            return view('auth.login');
        }
    }  

    /**
     * PAGINA REGISTRO
     */
    public function registro()
    {
        if (Auth::check()) {
            return redirect('admin');
        } else {
            $identidad_documentos = Identidad_documento::where('estado',1)->get();
            return view('auth.register', compact('identidad_documentos'));
        }
    }

    /**
     * PAGINA VERIFICAR CORREO
     */
    public function verificar()
    {
        return view('auth.verify');
    }

    /**
     * PAGINA RESTABLECER CONTRASEÑA
     */
    public function restablecer()
    {
        return view('auth.passwords.reset');
    }

    /**
     * POST INGRESO
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
 
        if($validator->fails()) {
            return redirect('login')->withErrors($validator);
        }
       
        //obteniendo usuario
        $user = User::where('email',$request->email)->first();

        if($user == null) {
            return redirect('login')->withErrors(['Estas credenciales no coinciden con nuestros registros.'])->withInput();
        }

        if($user->estado == 0) {
            return redirect('login')->withErrors(['Su cuenta de usuario se encuentra deshabilitada.'])->withInput();
        }
        //1:interno, 0:externo
        if($user->tipo == 0 && $user->email_verified_at == null) {
            return redirect('login')->withErrors(['Debes <a href="/verificar" class="alert-link">verificar</a> tu cuenta de correo electrónico antes de ingresar.'])->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, ($request->input('remember') == 'on') ? true : false)) {
            return redirect()->intended('admin');
        }

        return redirect("login")->withErrors(['Estas credenciales no coinciden con nuestros registros.'])->withInput();
    }

    /**
     * POST REGISTRO DE NUEVO USUARIO EXTERNO
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persona' => ['required'],
            'ruc' => 'required_if:persona,1',
            'razon_social' => 'required_if:persona,1',
            'identidad_documento_id' => ['required'],
            'nro_documento' => ['required','numeric','min:8'],
            'nombre' => ['required'],
            'apaterno' => ['required'],
            'amaterno' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
 
        if($validator->fails()) {
            return redirect('register')->withErrors($validator);
        }

        try {

            //validando recaptcha
            $privado = config('app.recaptcha_secret');
            $rol_externo = config('app.rol_externo');

            $client = new Client;
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'form_params' =>
                    [
                        'secret' => $privado,
                        'response' => $request->get('g-recaptcha-response')
                    ]
                ]
            );
    
            $body = json_decode((string)$response->getBody());

            if(!$body->success) {
                return redirect('register')->withErrors(["Captcha incorrecto."]);  
            }

            $data = $request->all();

            //registramos los datos personales
            $persona = new Persona;
            $persona->tipo = $data['persona'];
            $persona->identidad_documento_id = $data['identidad_documento_id'];
            $persona->nro_documento = $data['nro_documento'];
            $persona->nombre = $data['nombre'];
            $persona->apaterno = $data['apaterno'];
            $persona->amaterno = $data['amaterno'];
            $persona->correo = $data['email'];
            $persona->telefono = $data['telefono'];
            $persona->direccion = $data['direccion'];

            if(!$persona->save()) {
                return redirect('register')->withErrors(["Ocurrio un error durante el registro."]);
            }

            //si es persona juridica se registra organismo
            if($persona->tipo == 1) {//1:juridico            
                $invitado = new Invitado;
                $invitado->persona_id = $persona->id;
                $invitado->ruc = $data['ruc'];
                $invitado->razon_social = $data['razon_social'];
                $invitado->dependencia = $data['dependencia'];
                $invitado->cargo = $data['cargo'];
                $invitado->save();
            }            

            //registramos usuario externo
            $user = new User;
            $user->tipo = 0;//0:externo
            $user->rol_id = $rol_externo;
            $user->persona_id = $persona->id;
            $user->identidad_documento_id = $data['identidad_documento_id'];
            $user->nro_documento = $data['nro_documento'];
            $user->nombre = $data['nombre'];
            $user->apaterno = $data['apaterno'];
            $user->amaterno = $data['amaterno'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);

            if($user->save()) {                
                //enviar correo de verificacion

                return redirect('verificar')->with('registro', $user->email);
            }
            else {
                return redirect('register')->withErrors(["Ocurrio un error durante el registro."]);
            }
        }
        catch (\Exception $e) {
            return redirect('register')->withErrors([$e->getMessage()]);
        }
    }

    /**
     * CERRAR SESION
     */

    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }

}
