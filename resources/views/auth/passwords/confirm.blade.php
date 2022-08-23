@extends('layouts.blanco')

@section('titulo', 'Ingresar nueva contraseña')

@section('js')
<script src="{{ asset('js/nueva_contraseña.js?v='.config('app.version')) }}"></script>
@endsection

@section('contenido')
<div class="container-tight py-4">
    <div class="text-center mb-4">        
        <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">      
    </div>
    <form class="card card-md" method="POST" action="{{ url('/confirmar') }}" onsubmit="return enviar(event);">
        @csrf
        <div id="formulario" class="card-body">
            <h2 class="card-title text-center mb-3">Cambio de contraseña</h2>
            <div class="pb-2">
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
            <p class="text-muted text-justify mb-3">Hola <b>{{$user->email}} </b> ingresa tu nueva contraseña en los siguientes campos, la contraseña debe ser de almenos de 8 digitos.</p>
            <input type="hidden" name="codigo" value="{{$restablecimiento->codigo}}">
            <div class="form-group form-required mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control validar_minimo:8" placeholder="">
            </div>
            <div class="form-group form-required mb-3">
                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control validar_igual:password" placeholder="">
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-pink w-100">
                    Restablecer
                </button>
            </div>
        </div>
    </form>
</div>
@endsection