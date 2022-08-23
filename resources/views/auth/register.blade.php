@extends('layouts.blanco')

@section('titulo', 'Registro de usuario')

@section('js')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="{{ asset('js/registro.js?v='.config('app.version')) }}"></script>
@endsection

@section('contenido')
<div class="container-narrow py-4">
    <div class="text-center mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
        </a>
    </div>

    <div>
        <div class="alert alert-important alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                </div>
                <div>
                    El registro de nueva cuenta es solo para <b>PERSONAS EXTERNAS</b> a la universidad, el personal administrativo y estudiantes deben solicitar cuenta a traves de la <b title="OFICINA DE TECNOLOGÍAS DE LA INFORMACIÓN">OTI</b>.
                </div>
            </div>
            <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    </div>

    <form class="card card-md" method="POST" action="{{ url('register') }}" autocomplete="off" onsubmit="return registro(event);">
        <div id="form_registro" class="card-body">            
            @csrf
            <h2 class="card-title text-center mb-2">Registrar nueva cuenta</h2>
            <!--MENSAJE-->
            <div class="pb-3">
                @if ($errors->any())
                <div class="alert alert-important alert-danger alert-dismissible py-2 mb-2" role="alert">                
                    <div>
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @foreach ($errors->all() as $error)
                            <li class="lh-1 my-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>                
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif
            </div>
            <!--TIPO-->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3 row">
                        <label for="persona" class="form-label col-6 col-form-label">Tipo de persona</label>
                        <div class="col">
                            <select name="persona" id="persona" class="form-select" onchange="cambio_persona(this);">
                                <option value="0">Natural</option>
                                <option value="1">Jurídica</option>
                            </select>                  
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-0 mb-3">
            <!--PERSONA JURIDICA-->
            <div id="es_juridica" class="oculto">
                <fieldset class="form-fieldset pb-1 mb-3">
                    <div class="row">
                        <div class="col-md-4 form-group form-required mb-3">
                            <label class="form-label" for="ruc">RUC</label>
                            <input type="text" id="ruc" name="ruc" class="form-control validar_numero" placeholder="" autofocus>
                        </div>
                        <div class="col-md-8 form-group form-required mb-3">
                            <label class="form-label" for="razon_social">Razon social</label>
                            <input type="text" id="razon_social" name="razon_social" class="form-control mayuscula" placeholder="">
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label" for="dependencia">Área a la que pertenece</label>
                            <input type="text" id="dependencia" name="dependencia" class="form-control mayuscula" placeholder="">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label" for="cargo">Cargo que desempeña</label>
                            <input type="text" id="cargo" name="cargo" class="form-control mayuscula" placeholder="">
                        </div>
                    </div>
                </fieldset>
            </div>
            <!--PERSONA NATURAL-->
            <div id="es_natural">
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="identidad_documento_id">Tipo de documento</label>
                        <select name="identidad_documento_id" id="identidad_documento_id" class="form-select validar_select">
                            <option value="0">Seleccione...</option>
                            @foreach($identidad_documentos as $documento)
                            <option value="{{ $documento->id }}">{{ $documento->abreviatura }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="identidad_documento_id">Número de documento</label>
                        <input type="text" id="nro_documento" name="nro_documento" class="form-control validar_numero validar_minimo:8" placeholder="" autofocus>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="nombre">Nombres</label>
                        <input type="text" id="nombre" name="nombre" class="form-control mayuscula" placeholder="">
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="apaterno">Primer Apellido</label>
                        <input type="text" id="apaterno" name="apaterno" class="form-control mayuscula" placeholder="">
                    </div>
                </div> 
                <div class="row">               
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="amaterno">Segundo Apellido</label>
                        <input type="text" id="amaterno" name="amaterno" class="form-control mayuscula" placeholder="">
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input type="text" id="email" name="email" class="form-control validar_correo" placeholder="Se enviará email de verificación">                    
                    </div>
                </div>
                <div class="row">               
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label" for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control validar_numero" placeholder="">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label" for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control mayuscula" placeholder="">                    
                    </div>
                </div>
                <hr class="mb-3 mt-2"> 
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="password">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control validar_minimo:8" placeholder="">
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation " name="password_confirmation" class="form-control validar_igual:password" placeholder="">
                    </div>
                </div>
                <div class="form-group form-required mb-4 mt-2">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input">
                        <span class="form-check-label">Estoy de acuerdo con los <a href="{{ url('info/terminos') }}" tabindex="-1" target="_blank">términos y condiciones</a>.</span>
                    </label>
                </div>
                <div class="row align-items-end">
                    <div class="col-md-6">                    
                        <div class="g-recaptcha" data-callback="capcha_filled"
                            data-expired-callback="capcha_expired" data-sitekey="6LfjLl4gAAAAAIX8pPdp5yET1vySl1Y443h8EWLf"></div>  
                    </div>
                    <div class="col-md-6">                    
                        <div class="mt-3" style="margin-bottom: 2px;">
                            <button type="submit" class="btn btn-pink w-100">Registrarse</button>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </form>
    <div class="text-center text-muted mt-3">
        ¿Ya tienes una cuenta? <a href="{{ url('login') }}" tabindex="-1">Ingresar</a>
    </div>
</div>
@endsection
