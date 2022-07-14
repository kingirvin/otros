@extends('layouts.blanco')

@section('titulo', '403 - Acceso restringido')

@section('contenido')
<div class="container-tight py-4">
    <div class="empty">
        <div class="empty-header">403</div>
        <p class="empty-title">Acceso restringido</p>
        <p class="empty-subtitle text-muted">
            No cuentas con los privilegios necesarios para acceder a esta sección...
        </p>
        <div class="empty-action">           
            <a href="{{ url('/') }}" class="btn btn-pink">                
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>
                Ir al inicio
            </a>
        </div>
    </div>
</div>
@endsection