@extends('layouts.admin')
@section('titulo', 'Recibir documento externo')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/agregar_anexos.js?v='.config('app.version')) }}" type="text/javascript"></script>
<script src="{{ asset('js/buscar_archivos.js?v='.config('app.version')) }}" type="text/javascript"></script>
<script src="{{ asset('js/buscar_destino.js?v='.config('app.version')) }}" type="text/javascript"></script>
<script src="{{ asset('js/tramite/recibir_externo.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/tramite') }}">Trámite</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/tramite/recepcion') }}">Recibir</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Externo</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Recibir documento externo
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-lg">
        <div id="form-documento" class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="w-100">
                            <div class="row row align-items-center">
                                <div class="col">
                                    <h3 class="card-title">Documento</h3>
                                </div>
                                <div class="col-auto ms-auto">
                                    <button type="button" class="btn btn-secondary" onclick="buscar_archivo();">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" /><circle cx="16.5" cy="17.5" r="2.5" /><line x1="18.5" y1="19.5" x2="21" y2="22" /></svg>
                                        Buscar archivo                                        
                                    </button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="card-body">
                        <div class="form-grou mb-3">
                            <div class="form-label">Archivo digital</div>
                            <fieldset id="archivo_seleccionado" class="form-fieldset p-2 m-0 h-input">  
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group form-required mb-3">                                
                                <div class="form-label">Tipo de documento</div>
                                <select id="documento_tipo_id" class="form-select validar_select">
                                    <option value="0">Seleccione...</option> 
                                    @foreach ($documento_tipos as $documento_tipo)
                                    <option value="{{$documento_tipo->id}}">{{$documento_tipo->nombre}}</option> 
                                    @endforeach                              
                                </select>                                
                            </div>
                            <div class="col-md-6 form-group form-required mb-3">   
                                <div class="form-label">N° de documento</div>
                                <input id="numero" type="text" class="form-control mayuscula" placeholder="">
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-md-9 form-group form-required mb-3">
                                <label class="form-label">Remitente</label>
                                <input id="remitente" type="text" class="form-control mayuscula" placeholder="">
                            </div>
                            <div class="col-md-3 form-group form-required mb-3">
                                <label class="form-label">Folios</label>
                                <input id="folios" type="text" class="form-control validar_entero" placeholder="">
                            </div>
                        </div>
                        <div class="form-group form-required mb-3">
                            <label class="form-label">Asunto</label>
                            <textarea id="asunto" class="form-control mayuscula" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Observaciones</label>
                            <textarea id="observaciones" class="form-control mayuscula" rows="2"></textarea>
                        </div>
                    </div>
                </div> 
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="w-100">
                            <div class="row align-items-center">
                              <div class="col">
                                <h3 class="card-title">Anexos</h3>                                
                              </div>
                              <div class="col-auto ms-auto">
                                <button type="button" class="btn btn-white" onclick="agregar_anexo()">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                  Agregar                                        
                                </button>                               
                              </div>
                            </div>
                            <input type="file" id="nuevo_anexo" class="oculto">
                        </div>
                    </div>
                    <div id="lista_anexos" class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="text-muted">
                                Agregar archivos anexos al documento 
                            </div>
                        </div> 
                    </div>
                </div>               
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="w-100">
                            <div class="row row align-items-center">
                                <div class="col">
                                    <h3 class="card-title">Trámite</h3>
                                </div>
                                <div class="col-auto ms-auto">
                                    <select name="persona" id="persona" class="form-select" onchange="cambio_persona(this);">
                                        <option value="0">Persona Natural</option>
                                        <option value="1">Persona Jurídica</option>
                                    </select>   
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div id="es_juridica" class="card-header oculto pb-1">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-4 form-group form-required mb-3">
                                    <label class="form-label" for="ruc">RUC</label>
                                    <input type="text" id="ruc" name="ruc" class="form-control validar_numero" placeholder="" autofocus>
                                </div>
                                <div class="col-md-8 form-group form-required mb-3">
                                    <label class="form-label" for="razon_social">Razon social</label>
                                    <input type="text" id="razon_social" name="razon_social" class="form-control mayuscula" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        <div id="es_natural">
                            <div class="row">
                                <div class="col-md-6 form-group form-required mb-3">
                                    <label class="form-label" for="identidad_documento_id">Tipo de documento</label>
                                    <select name="identidad_documento_id" id="identidad_documento_id" class="form-select validar_select">
                                        <option value="0">Seleccione...</option>
                                        @foreach ($identidad_tipos as $identidad_tipo)
                                        <option value="{{$identidad_tipo->id}}">{{$identidad_tipo->abreviatura}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group form-required mb-3">
                                    <label class="form-label" for="identidad_documento_id">Número de documento</label>
                                    <input type="text" id="nro_documento" name="nro_documento" class="form-control validar_numero validar_minimo:8" placeholder="" autofocus>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group form-required mb-3">
                                    <label class="form-label" for="nombre">Nombres</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control mayuscula" placeholder="">
                                </div>
                                <div class="col-md-6 form-group form-required mb-3">
                                    <label class="form-label" for="apaterno">Primer Apellido</label>
                                    <input type="text" id="apaterno" name="apaterno" class="form-control mayuscula" placeholder="">
                                </div>
                            </div> 
                            <div class="row">               
                                <div class="col-md-6 form-group form-required mb-3">
                                    <label class="form-label" for="amaterno">Segundo Apellido</label>
                                    <input type="text" id="amaterno" name="amaterno" class="form-control mayuscula" placeholder="">
                                </div>
                                <div class="col-md-6 form-group form-required mb-3">
                                    <label class="form-label" for="email">Correo electrónico</label>
                                    <input type="text" id="email" name="email" class="form-control validar_correo" placeholder="">                    
                                </div>
                            </div>
                            <div class="row">               
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label" for="telefono">Teléfono</label>
                                    <input type="text" id="telefono" name="telefono" class="form-control validar_numero" placeholder="">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label" for="direccion">Dirección</label>
                                    <input type="text" id="direccion" name="direccion" class="form-control mayuscula" placeholder="">                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body"> 
                        <div class="form-group mb-3">                               
                            <label class="form-label">Procedimiento</label>
                            <select id="procedimiento_id" class="form-select">
                                <option value="0">Seleccione...</option>
                                @foreach ($procedimientos as $procedimiento)
                                <option value="{{$procedimiento->id}}">{{$procedimiento->titulo}}</option>
                                @endforeach
                            </select>                               
                        </div>
                        <div class="form-group form-required">
                            <label class="form-label">Destino</label>
                            <select id="d_dependencia_id" class="form-select validar_select">
                                @if(count($destinos) > 0)
                                    @foreach ($destinos as $destino)
                                    <option value="{{$destino->dependencia_id}}">{{$destino->dependencia->nombre}}</option>
                                    @endforeach
                                @else
                                <option value="0">NO TIENES ASIGNADO UNA DEPENDENCIA</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{url('admin/tramite/recepcion')}}" class="btn btn-link link-secondary w-100">
                            Cancelar
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary w-100" onclick="guardar_todo();">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection