@extends('layouts.blanco')

@section('titulo', 'Verificación de correo electrónico')

@section('js')
<script src="{{ asset('js/verificar.js?v='.config('app.version')) }}"></script>
@endsection

@section('contenido')
<div class="container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
        </a>
    </div>
    <form class="card card-md" method="POST" action="{{ url('/verificar') }}" onsubmit="return enviar(event);">
        @csrf
        <div id="formulario" class="card-body">
            <h2 class="card-title text-center mb-3">Reenviar correo de verificación</h2>
            <div class="pb-2">
                @if(session('reenvio'))
                <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">                
                    <div>Se ha enviado un nuevo enlace de verificación a <b>{{ session('reenvio') }}</b>.</div>                
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
            <p class="text-muted text-justify">Si no recibiste el correo de verificación es posible que se encuentre en la carpeta de <b>correo no deseado</b> o <b>spam</b>, si no se encuentra intenta reenviar el correo.</p>
            <div class="form-group form-required mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control validar_correo" placeholder="Ingrese su correo" autofocus>
            </div>
            <div class="form-footer">                
                <button type="submit" class="btn btn-pink w-100">                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="3" y="5" width="18" height="14" rx="2"></rect><polyline points="3 7 12 13 21 7"></polyline></svg>
                    Reenviar correo de verificación
                </button>
            </div>
        </div>
    </form>
    <div class="text-center text-muted mt-3">
        Ya he verificado mi correo electrónico, <a href="{{ url('login') }}" tabindex="-1">Ingresar</a>
    </div>
</div>
@endsection
