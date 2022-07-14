@extends('layouts.admin')
@section('titulo', 'Pasos de procedimiento')
@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script>
    const elProcedimiento = {{ $procedimiento->id }};
    var losPasos = {!! $procedimiento->pasos !!};
</script>
<script src="{{ asset('js/sistema/procedimiento_pasos.js') }}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/sistema') }}">Sistema</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/sistema/documental/procedimientos') }}">Procedimientos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pasos</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Pasos de procedimiento
                </h2>
            </div>             
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div id="form_procedimiento" class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Datos de procedimiento</h3>
                    </div>
                    <div class="card-body">
                        <dl class="mb-2">
                            @if($procedimiento->codigo != null)
                            <dt>Código</dt>    
                            <dd>{{ $procedimiento->codigo }}</dd>
                            @endif
                            <dt>Titulo</dt>    
                            <dd>{{ $procedimiento->titulo }}</dd>
                            <dt>Tipo de procedimiento</dt>
                            @if($procedimiento->tipo == 0)
                            <dd>DE USO INTERNO</dd>
                            @elseif($procedimiento->tipo == 1)
                            <dd>MESA DE PARTES - UNIVERSITARIO</dd>
                            @else
                            <dd>MESA DE PARTES - EXTERNO</dd>
                            @endif
                            <dt>Dependencia donde se presenta</dt>    
                            <dd>{{ $procedimiento->presentar->nombre }}</dd>
                            @if($procedimiento->atender != null)
                            <dt>Dependencia responsable de atender</dt>    
                            <dd>{{ $procedimiento->atender->nombre }}</dd>
                            @endif
                        </dl>  
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Plazo establecido</dt>    
                                    <dd>{{ $procedimiento->plazo }} dias</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Plazo calculado</dt>    
                                    <dd><span id="plazo_calculado">0</span> dias</dd>
                                </dl>
                            </div>
                        </div>                                           
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title flex-fill">Pasos</h3>
                        <div style="margin: -5px -5px -5px 0">
                            <button onclick="nuevo();" class="btn btn-success" >
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                Agregar
                            </button>
                        </div>
                    </div>
                    <div id="lista_pasos" class="list-group list-group-flush">
                        <div class="list-group-item bg-muted-lt">Cargando...</div>                                          
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div>
                                          
                    </div>
                    <div class="ms-auto">
                        <a href="{{ url('admin/sistema/documental/procedimientos') }}" class="btn btn-link link-secondary">Cancelar</a>
                        <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<!-- MODAL PASOS -->
<div id="editar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar" class="modal-title">Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body pb-0">  
                <div class="form-group form-required mb-3">
                    <label class="form-label">Dependencia de paso</label>
                    <select id="dependencia_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($sedes as $sede)
                            @foreach ($sede->dependencias as $dependencia)
                            <option value="{{ $dependencia->id }}">{{ $sede->abreviatura }} | {{ $dependencia->nombre }}</option>
                            @endforeach                                    
                        @endforeach
                    </select>
                </div>
                <div class="form-group form-required mb-3">
                    <label class="form-label">Acción a realizar</label>
                    <input id="accion" type="text" class="form-control mayuscula" placeholder="">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control mayuscula" rows="3"></textarea>
                </div>   
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label">Plazo de atención (dias)</label>
                        <input id="plazo_atencion" type="text" class="form-control validar_entero" placeholder="">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Plazo de subsanación (dias)</label>
                        <input id="plazo_subsanacion" type="text" class="form-control validar_entero" placeholder="">
                    </div>
                </div>                         
            </div>   
            <div class="modal-footer">
                <div>
                    <label class="form-check form-switch mb-0">
                        <input id="estado" class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Estado</span>
                    </label>
                </div>
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="agregar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection