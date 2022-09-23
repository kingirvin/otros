@extends('layouts.blanco')

@section('titulo', 'UNAMAD - Consultas')

@section('contenido')
<div class="container py-4">
    <div class="row">
        <div class="col-12 text-center mb-4">        
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
            </a>           
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="pb-4 pt-2">
                            <img src="{{ asset('img/consulta_firma.png') }}" alt="" height="150px">
                        </div>
                        <div class="mb-3" style="font-size: 1.25rem; line-height: 1.4; font-weight: 600;">Validar firma digital</div>
                        <p class="mb-3 text-muted">Valida la integridad y autenticidad de la firma digital de un documento impreso.</p>
                        <div>
                            <a href="{{ url('consultas/firmas') }}" class="btn btn-pink">
                                Consultar
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-0 ms-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="13" y1="18" x2="19" y2="12" /><line x1="13" y1="6" x2="19" y2="12" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
@endsection