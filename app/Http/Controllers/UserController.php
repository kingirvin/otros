<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\Rol;
use App\Models\User;
use App\Models\Persona;
use App\Models\Invitado;
use App\Models\User_verificacion;
use App\Models\User_restablecimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Identidad_documento;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificacionEmail;
use App\Mail\RestablecerEmail;
use GuzzleHttp\Client;
use Carbon\Carbon;

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
    public function reenviar_verificacion()
    {
        return view('auth.verify');
    }

    public function reenviar_verificacion_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email']
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = User::where('email',$request->email)->first();

        if($user == null) {
            return back()->withErrors(['El correo ingresado no se encuentra registrado.'])->withInput();
        }

        if($user->email_verified_at != null) {
            return back()->withErrors(['El correo del usuario ya ha sido verificado.'])->withInput();
        }

        try {
            //desactivamos los otros
            User_verificacion::where('user_id', $user->id)->update(['estado' => 0]);
            //creamos uno nuevo
            $verificacion = new User_verificacion;
            $verificacion->user_id = $user->id;
            $verificacion->codigo = Str::random(32);
            $verificacion->estado = 1;
            if($verificacion->save()){
                Mail::to($user)->send(new VerificacionEmail($user, $verificacion));
                return back()->with('reenvio', $user->email);
            } else {
                return back()->withErrors(['Ocurrio un error durante el envio del correo.'])->withInput();
            } 
        } 
        catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()])->withInput();
        }

    }

    public function verificar(Request $request, $codigo)
    { 
        $verificacion = User_verificacion::where('codigo',$codigo)->where('estado',1)->first();

        if($verificacion == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error", 'mensaje' => "El código de verificación no esta disponible o la verificación ya se ha realizado.", 'accion' => "close" )]);  
        }      

        $user_temp = User::find($verificacion->user_id);

        if($user_temp == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error", 'mensaje' => "No se encontro el usuario.", 'accion' => "close" )]);  
        }  

        if($user_temp->email_verified_at != null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error", 'mensaje' => "El correo del usuario ya se encuentra verificado.", 'accion' => "home" )]);  
        }

        $user_temp->email_verified_at = Carbon::now();
        if($user_temp->save()) {
            User_verificacion::where('user_id', $user_temp->id)->update(['estado' => 0]);
            return view('paginas.mensaje', ['datos' => array('tipo' => 2, 'titulo' => "Verificación exitosa", 'mensaje' => "El correo <b>".$user_temp->email."</b> ha sido verificado.", 'accion' => "admin" )]); 
        } else {
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error", 'mensaje' => "Ocurrio un error durante el registro de validación.", 'accion' => "close" )]); 
        }
    }

    /**
     * PAGINA RESTABLECER CONTRASEÑA
     */
    public function restablecer()
    {
        return view('auth.passwords.reset');
    }

    public function restablecer_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email']
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = User::where('email',$request->email)->first();

        if($user == null) {
            return back()->withErrors(['El correo ingresado no se encuentra registrado.'])->withInput();
        }

        if($user->estado == 0) {
            return back()->withErrors(['El usuario se encuentra deshabilitado.'])->withInput();
        }

        try {
            $ahora = Carbon::now();
            //desactivamos los otros
            User_restablecimiento::where('user_id', $user->id)->update(['estado' => 0]);
            //creamos uno nuevo
            $restablecimiento = new User_restablecimiento;
            $restablecimiento->user_id = $user->id;
            $restablecimiento->codigo = Str::random(32);
            $restablecimiento->fecha_inicio = $ahora;
            $restablecimiento->fecha_vencimiento = $ahora->copy()->addDay();
            $restablecimiento->estado = 1;
            if($restablecimiento->save()){
                Mail::to($user)->send(new RestablecerEmail($user, $restablecimiento));
                return back()->with('restablecer', $user->email);
            } else {
                return back()->withErrors(['Ocurrio un error durante el envio del correo.'])->withInput();
            } 
        } 
        catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function confirmar($codigo)
    {       
        $restablecimiento = User_restablecimiento::where('codigo',$codigo)->where('estado',1)->first();
        if($restablecimiento == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error de validación", 'mensaje' => "El enlace no es válido o ha caducado.", 'accion' => "close" )]);  
        }

        $ahora = Carbon::now();
        //aun no ha pasado la fecha de vencimiento
        if($restablecimiento->fecha_vencimiento->gt($ahora)){   
            $user = User::find($restablecimiento->user_id);         
            return view('auth.passwords.confirm',compact('user','restablecimiento'));
        }
        else {
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error de validación", 'mensaje' => "El enlace no es válido o ha caducado.", 'accion' => "close" )]);  
        }
    }

    public function confirmar_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $restablecimiento = User_restablecimiento::where('codigo',$request->codigo)->where('estado',1)->first();
        if($restablecimiento == null){
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error de validación", 'mensaje' => "El enlace no es válido o ha caducado.", 'accion' => "close" )]);  
        }

        $ahora = Carbon::now();
        //aun no ha pasado la fecha de vencimiento
        if($restablecimiento->fecha_vencimiento->gt($ahora)){   

            $user = User::find($restablecimiento->user_id);
            if($user == null) {
                return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error de validación", 'mensaje' => "No se pudo encontrar el usuario.", 'accion' => "close" )]);  
            }    

            $user->password = Hash::make($request->password);

            if($user->save()){
                $restablecimiento->estado = 0;
                $restablecimiento->save();
                return view('paginas.mensaje', ['datos' => array('tipo' => 2, 'titulo' => "Registro exitoso", 'mensaje' => "Se ha restablecido existosamente la contraseña de tu cuenta de usuario.", 'accion' => "admin" )]);  
            }
            else {
                return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error de validación", 'mensaje' => "Ocurrio un error durante el restablecimiento de tu contraseña.", 'accion' => "close" )]);  
            }
        }
        else {
            return view('paginas.mensaje', ['datos' => array('tipo' => 0, 'titulo' => "Error de validación", 'mensaje' => "El enlace no es válido o ha caducado.", 'accion' => "close" )]);  
        }
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
    public function registro_post(Request $request)
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

            $rol = Rol::find($rol_externo);

            if($rol == null) {
                return redirect('register')->withErrors(["Rol no configurado."]);  
            }

            $data = $request->all();

            //registramos los datos personales
            $persona = new Persona;
            $persona->tipo = $request->persona;
            $persona->identidad_documento_id = $request->identidad_documento_id;
            $persona->nro_documento = $request->nro_documento;
            $persona->nombre = $request->nombre;
            $persona->apaterno = $request->apaterno;
            $persona->amaterno = $request->amaterno;
            $persona->correo = $request->email;
            $persona->telefono = $request->telefono;
            $persona->direccion = $request->direccion;
            $persona->registro = 0;//0:externo, 1:interno

            if(!$persona->save()) {
                return redirect('register')->withErrors(["Ocurrio un error durante el registro."]);
            }

            //si es persona juridica se registra organismo
            if($request->persona == 1) {//1:juridico            
                $invitado = new Invitado;
                $invitado->persona_id = $persona->id;
                $invitado->ruc = $request->ruc;
                $invitado->razon_social = $request->razon_social;
                $invitado->dependencia = $request->dependencia;
                $invitado->cargo = $request->cargo;
                $invitado->save();
            }            

            //registramos usuario externo
            $user = new User;
            $user->tipo = 0;//0:externo
            $user->rol_id = $rol->id;
            $user->persona_id = $persona->id;
            $user->identidad_documento_id = $request->identidad_documento_id;
            $user->nro_documento = $request->nro_documento;
            $user->nombre = $request->nombre;
            $user->apaterno = $request->apaterno;
            $user->amaterno = $request->amaterno;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            if($user->save()) {                
                //enviar correo de verificacion
                $verificacion = new User_verificacion;
                $verificacion->user_id = $user->id;
                $verificacion->codigo = Str::random(32);
                $verificacion->estado = 1;
                if($verificacion->save()){
                    Mail::to($user)->send(new VerificacionEmail($user, $verificacion));
                }               
                return view('paginas.mensaje', ['datos' => array(
                        'tipo' => 2, 
                        'titulo' => "Usuario registrado exitosamente", 
                        'mensaje' => "El usuario <b>".$user->email."</b> ha sido registrado exitosamente, te hemos enviado un mensaje para que verifiques tu correo electrónico.", 
                        'accion' => "home" 
                    )]);  
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
