@extends('layouts.blanco')

@section('titulo', 'Error')

@section('contenido')
    @php
        if(isset($datos))
            $color = ($datos["tipo"] == 1 ? "info" : "danger");
        else 
            $color = "dark";        
    @endphp
<div class="container-tight py-4">
    <div class="card card-md">      
        <div class="card-status-start bg-{{$color}}"></div>     
        <div class="card-body alert-{{$color}}">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                </div>
                <div>
                    <h3 class="text-{{$color}}" style="line-height: 1.3;">
                        {{ isset($datos) ? $datos["titulo"] : "Mensaje" }}
                    </h3>
                    <p class="text-muted mb-4">
                        {{ isset($datos) ? $datos["mensaje"] : "No definido" }}
                    </p>
                </div>
            </div>
            <div class="text-center">
                @isset($datos)
                    @if($datos["accion"] == "home")
                    <a href="{{ url('/admin') }}" class="btn btn-{{$color}}">                
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>
                        Ir al inicio
                    </a>
                    @elseif($datos["accion"] == "back")
                    <a href="{{ url()->previous() }}" class="btn btn-{{$color}}">                
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>
                        Regresar
                    </a>
                    @else
                    <a href="javascript:void(0);" onclick="window.close();" class="btn btn-{{$color}}">                
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
                        Cerrar
                    </a>
                    @endif
                @else
                <a href="{{ url('/') }}" class="btn btn-{{$color}}">                
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>
                    Ir al inicio
                </a>
                @endisset                
            </div>            
        </div>
    </div>    
</div>
@endsection
