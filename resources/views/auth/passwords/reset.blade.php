@extends('layouts.blanco')

@section('titulo', 'Restablecer contraseña')

@section('js')
<script src="{{ asset('js/restablecer.js?v='.config('app.version')) }}"></script>
@endsection


@section('contenido')
<div class="container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
        </a>
    </div>
    <form class="card card-md" method="POST" action="{{ url('/restablecer') }}" onsubmit="return enviar(event);">
        @csrf
        <div id="formulario" class="card-body">
            <h2 class="card-title text-center mb-3">Restablecer contraseña</h2>
            <div class="pb-2">
                @if(session('restablecer'))
                <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">                
                    <div>Se ha enviado un nuevo enlace de restablecimiento a <b>{{ session('restablecer') }}</b>.</div>                
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">                
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
            <p class="text-muted text-justify mb-3">Ingresa el correo asociado a tu cuenta de usuario y te enviaremos un enlace para el restablecimiento de tu contraseña.</p>
            <div class="form-group form-required mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control validar_correo" placeholder="Ingrese su correo" autofocus>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-pink w-100">
                    <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="3" y="5" width="18" height="14" rx="2"></rect><polyline points="3 7 12 13 21 7"></polyline></svg>
                    Enviar correo de restablecimiento
                </button>
            </div>
        </div>
    </form>
    <div class="text-center text-muted mt-3">
        Ya he restablecido mi contraseña, <a href="{{url('login')}}">Ingresar</a>.
    </div>
</div>
@endsection