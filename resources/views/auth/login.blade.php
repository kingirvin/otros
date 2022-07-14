@extends('layouts.blanco')

@section('titulo', 'Iniciar sesión')

@section('js')
<script src="{{ asset('js/login.js?v='.config('app.version')) }}"></script>
@endsection

@section('contenido')
<div class="container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
        </a>
    </div>
    <form class="card card-md" method="POST" action="{{ url('login') }}" onsubmit="return login(event);">
        @csrf
        <div id="form_login" class="card-body">
            <h2 class="card-title text-center mb-2">Ingrese sus credenciales</h2>
            <div class="pb-3">
                @if ($errors->any())
                <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">                
                    <div>
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @foreach ($errors->all() as $error)
                            <li class="lh-1 my-1">{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>                
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif
            </div>
            <div class="mb-3 form-group form-required">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" required autocomplete="email" value="{{ old('email') }}" autofocus>
            </div>
            <div class="mb-2 form-group form-required">
                <label class="form-label">Contraseña</label>                
                <input type="password" id="password" class="form-control" name="password" required autocomplete="off">                    
                <div id="may_act" class="form-check-description text-warning mt-1 oculto">Bloq Mayús activado</div>
            </div>
            <div class="mb-2">
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" />
                    <span class="form-check-label">Recordarme en este dispositivo</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-pink w-100">Ingresar</button>
            </div>
        </div>        
    </form>
    <div class="text-center text-muted mt-3">
        ¿Tienes problemas para ingresar? <a href="{{ url('info/acceso') }}" tabindex="-1" target="_blank">Te ayudamos</a>
    </div>
</div>
@endsection
