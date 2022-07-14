@extends('layouts.admin')
@section('titulo', $procedimiento != null ? 'Modificar procedimiento' : 'Nuevo procedimiento')
@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script>
    const elProcedimiento = {{ $procedimiento ? $procedimiento->id : 0}};
</script>
<script src="{{ asset('js/sistema/procedimiento_editar.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ $procedimiento != null ? 'Modificar' : 'Nuevo' }}</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    {{ $procedimiento != null ? 'Modificar procedimiento' : 'Nuevo procedimiento' }}
                </h2>
            </div>             
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div id="form_procedimiento" class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Datos de procedimiento</h3>
                    </div>
                    @php 
                        $procedimiento_tipo = $procedimiento ? $procedimiento->tipo : 0;
                        $procedimiento_estado = $procedimiento ? $procedimiento->estado : 1;
                        $procedimiento_presentar = $procedimiento ? $procedimiento->presentar_id : 0;
                        $procedimiento_presentar_modalidad = $procedimiento ? $procedimiento->presentar_modalidad : 0;
                        $procedimiento_atencion = $procedimiento ? $procedimiento->atender_id : 0;
                        $procedimiento_atencion_modalidad = $procedimiento ? $procedimiento->atender_modalidad : 0;
                        $procedimiento_calificacion = $procedimiento ? $procedimiento->calificacion : 1;                        
                    @endphp

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 form-group form-required mb-3">
                                <label class="form-label">Tipo de procedimiento</label>
                                <select id="tipo" class="form-select">
                                    <option value="0" {{ $procedimiento_tipo == 0 ? 'selected' : '' }}>DE USO INTERNO</option>
                                    <option value="1" {{ $procedimiento_tipo == 1 ? 'selected' : '' }}>MESA DE PARTES - UNIVERSITARIO</option>
                                    <option value="2" {{ $procedimiento_tipo == 2 ? 'selected' : '' }}>MESA DE PARTES - EXTERNO</option>
                                </select>
                            </div> 
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label">Código</label>
                                <input id="codigo" type="text" class="form-control" placeholder="" value="{{$procedimiento ? $procedimiento->codigo : ''}}">
                            </div>
                        </div>                        
                        <div class="form-group form-required mb-3">
                            <label class="form-label">Título</label>
                            <input id="titulo" type="text" class="form-control mayuscula" placeholder="" value="{{$procedimiento ? $procedimiento->titulo : ''}}">
                        </div> 
                        <div class="form-group mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea id="descripcion" rows="4" class="form-control mayuscula">{{$procedimiento ? $procedimiento->descripcion : ''}}</textarea>
                        </div> 
                        <div class="form-group mb-3">
                            <label class="form-label">Norma que regula</label>
                            <input id="normatividad" type="text" class="form-control " placeholder="" value="{{$procedimiento ? $procedimiento->normatividad : ''}}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Requisitos</label>
                            <textarea id="requisitos" rows="5" class="form-control mayuscula">{{$procedimiento ? $procedimiento->requisitos : ''}}</textarea>
                        </div>                                               
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Presentación</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group form-required mb-3">
                            <label class="form-label">Dependencia donde se presenta</label>
                            <select id="presentar_id" class="form-select validar_select">
                                <option value="0">Seleccione...</option>
                                @foreach ($sedes as $sede)
                                    @foreach ($sede->dependencias as $dependencia)
                                    <option value="{{ $dependencia->id }}" {{ $procedimiento_presentar == $dependencia->id ? 'selected' : '' }}>{{ $sede->abreviatura }} | {{ $dependencia->nombre }}</option>
                                    @endforeach                                    
                                @endforeach
                            </select>
                        </div> 
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label">Pago por derecho</label>
                                <input id="pago_monto" type="text" class="form-control validar_decimal" placeholder="" value="{{$procedimiento ? $procedimiento->pago_monto : ''}}">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label">Código de pago</label>
                                <input id="pago_codigo" type="text" class="form-control " placeholder="" value="{{$procedimiento ? $procedimiento->pago_codigo : ''}}">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Agencia bancaria</label>
                            <input id="pago_entidad" type="text" class="form-control mayuscula" placeholder="" value="{{$procedimiento ? $procedimiento->pago_entidad : ''}}">
                        </div>
                        <div class="form-group text-end">    
                            <div class="d-inline-block">                        
                                <label class="form-check form-switch">
                                    <input id="presentar_modalidad" class="form-check-input" type="checkbox" {{ $procedimiento_presentar_modalidad == 1 ? 'checked' : '' }}>
                                    <span class="form-check-label">Presentación no presencial</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Atención</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Dependencia responsable de atender</label>
                            <select id="atender_id" class="form-select">
                                <option value="0">Seleccione...</option>
                                @foreach ($sedes as $sede)
                                    @foreach ($sede->dependencias as $dependencia)
                                    <option value="{{ $dependencia->id }}" {{ $procedimiento_atencion == $dependencia->id ? 'selected' : '' }}>{{ $sede->abreviatura }} | {{ $dependencia->nombre }}</option>
                                    @endforeach                                    
                                @endforeach
                            </select>
                        </div>
                        <div class="row">                            
                            <div class="col-md-4 form-group form-required mb-3">
                                <label class="form-label">Plazo</label>
                                <input id="plazo" type="text" class="form-control validar_numero" placeholder="" value="{{$procedimiento ? $procedimiento->plazo : ''}}">
                            </div>  
                            <div class="col-md-8 form-group form-required mb-3">
                                <label class="form-label">Calificación del procedimiento</label>
                                <select id="calificacion" class="form-select">
                                    <option value="1" {{ $procedimiento_calificacion == 1 ? 'selected' : '' }}>Evaluación previa</option>
                                    <option value="0" {{ $procedimiento_calificacion == 0 ? 'selected' : '' }}>Aprobación automática</option>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group text-end">                            
                            <div class="d-inline-block">
                                <label class="form-check form-switch">
                                    <input id="atender_modalidad" class="form-check-input" type="checkbox" {{ $procedimiento_atencion_modalidad == 1 ? 'checked' : '' }}>
                                    <span class="form-check-label">Atención no presencial</span>
                                </label>
                            </div>                           
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div>                        
                        <label class="form-check form-switch mb-0">
                            <input id="estado" class="form-check-input" type="checkbox" {{ $procedimiento_estado == 1 ? 'checked' : '' }}>
                            <span class="form-check-label">Estado</span>
                        </label>
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