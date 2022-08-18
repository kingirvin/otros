@extends('layouts.admin')
@section('titulo', 'Consulta el estado de tu trámite')

@section('js')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="{{ asset('js/externo/consulta.js?v='.config('app.version')) }}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center mw-100">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/externo') }}">Ventanilla</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Consulta</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Consulta el estado de tu trámite
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card card-sm mb-3">
                    <img src="{{ asset('img/codigo_tramite.png') }}" alt="" class="w-100 card-img">
                </div>
            </div>
            <div class="col-md-4">
                @if(session('error'))
                <div class="alert alert-important alert-danger alert-dismissible" role="alert">                                      
                    {{ session('error') }}               
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif   

                @if($errors->any())
                <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                    <ul class="m-0 ps-0" style="list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ url('admin/externo/seguimiento') }}" method="POST" onsubmit="return guardar_todo(this);" >
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Datos del trámite</h3>
                        </div>
                        <div class="card-body">
                            <div id="form_principal">
                                <div class="form-group form-required mb-3">
                                    <label class="form-label">Código Único de Trámite</label>
                                    <div class="input-group input-group-flat">
                                        <span class="input-group-text">T-</span>
                                        <input id="cut" name="cut" type="text" placeholder="00000000" class="form-control ps-0 mayuscula validar_exacto:8" maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group form-required mb-3">
                                    <label class="form-label">Fecha de registro de trámite</label>
                                    <input id="fecha" name="fecha" type="text" placeholder="dd/mm/yyyy" class="form-control validar_fecha">
                                </div>
                            </div>
                            <div class="form-group form-required">
                                <label class="form-label">Validación</label>
                                <div class="g-recaptcha" data-callback="capcha_filled"
                                    data-expired-callback="capcha_expired" data-sitekey="{{ config('app.recaptcha_public') }}"></div>   
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                            Consultar
                        </button>
                    </div>
                </form>
            </div>                                    
        </div>
    </div>
</div>
@endsection