@extends('layouts.admin')
@section('titulo', 'Iniciar nuevo trámite')

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
<script src="{{ asset('js/tramite/nuevo_tramite.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/tramite/emision/emitidos') }}">Emitidos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Iniciar nuevo trámite
                </h2>
            </div>            
        </div>
    </div>
</div>
@php
    $modulos =  request('modulos', array());
    $jefe = false;
    if(array_key_exists('TRAMITE', $modulos)){
        if(in_array('GESTDOC', $modulos['TRAMITE'])){
            $jefe = true;
        }
    }
@endphp
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
                                @if($jefe)
                                <select id="o_empleado_id" class="form-select validar_select">
                                    <option value="0" data-persona="0">Seleccione...</option>
                                    @foreach ($empleados as $empleado)
                                    <option value="{{$empleado->id}}" data-persona="{{$empleado->persona_id}}">{{$empleado->persona->nombre.' '.$empleado->persona->apaterno.' '.$empleado->persona->amaterno}}</option>
                                    @endforeach
                                </select>
                                @else
                                <select id="o_empleado_id" class="form-select validar_select">
                                    <option value="{{$empleado_actual->id}}" data-persona="{{$empleado_actual->persona_id}}">{{$empleado_actual->persona->nombre.' '.$empleado_actual->persona->apaterno.' '.$empleado_actual->persona->amaterno}}</option>                                   
                                </select>
                                @endif
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
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Trámite</h3>
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
                        <div class="form-group form-required mb-3">
                            <label class="form-label">Origen</label>
                            <select id="o_dependencia_id" class="form-select validar_select">
                                @if(count($origenes) > 0)
                                    @foreach ($origenes as $origen)
                                    <option value="{{$origen->dependencia_id}}" {{$origen->dependencia_id == $origen_actual ? 'selected' : ''}} >{{$origen->dependencia->nombre}}</option>
                                    @endforeach
                                @else
                                <option value="0">NO TIENES ASIGNADO UNA DEPENDENCIA</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group form-required">
                            <div class="w-100 mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <label class="form-label">Destino</label>
                                    </div>                                  
                                    <div class="col-auto ms-auto">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success" onclick="nuevo_destino_dependencia()">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                Agregar                                        
                                            </button> 
                                            <div class="btn-group" role="group">
                                                <button data-bs-toggle="dropdown" type="button" class="btn dropdown-toggle dropdown-toggle-split" aria-expanded="false"></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="nuevo_destino_externo()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 12h5a2 2 0 0 1 0 4h-15l-3 -6h3l2 2h3l-2 -7h3z" transform="rotate(-15 12 12) translate(0 -1)" /><line x1="3" y1="21" x2="21" y2="21" /></svg>
                                                        Destino externo
                                                    </a>                                                    
                                                </div>
                                            </div>
                                        </div>                                          
                                    </div>
                                </div>
                            </div>
                            <fieldset class="form-fieldset p-0 m-0">
                                <div id="destinos" class="list-group list-group-flush h-input">
                                </div>
                            </fieldset>
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

                <div class="row">
                    <div class="col-md-6">
                        <a href="{{url('admin/tramite/emision/emitidos')}}" class="btn btn-link link-secondary w-100">
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

@section('modal')
<!-- MODAL DESTINO -->
<div id="destino_dependencia" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="titulo-editar">Nuevo destino</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="interno" class="modal-body">           
            <div class="form-group form-required mb-3">
                <label class="form-label">Sede</label>
                <select id="sede_id" class="form-select">
                    @foreach ($sedes as $sede)
                    <option value="{{$sede->id}}" data-abreviatura="{{$sede->abreviatura}}">{{$sede->nombre}}</option>
                    @endforeach                        
                </select>
            </div>
            <div class="form-group form-required mb-3">
                <label class="form-label">Dependencia</label>
                <select id="dependencia_select" class="form-select validar_select">
                    <option value="0">Seleccione...</option>
                    @foreach ($dependencias as $dependencia)
                    <option value="{{$dependencia->id}}">{{$dependencia->nombre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Personal</label>
                <select id="empleado_select" class="form-select">
                    <option value="0" data-persona="0">Seleccione...</option>                   
                </select>
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-between">
                    <label class="form-check form-switch m-0">
                        <input id="copia" class="form-check-input" type="checkbox">
                        <span class="form-check-label">Como copia</span>
                    </label>                    
                    <a class="interrogante" tabindex="0" role="button" data-bs-toggle="tooltip" data-bs-placement="left" title="Los documento enviados como copia no podran ser derivados, son solo de conocimiento.">?</a>
                </div>
            </div>
            <div id="dependencia_loading" class="cargando">
                <div class="text-center pt-4">
                  <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                Cancelar
            </a>
            <button id="btn_destino" type="button" class="btn btn-primary ms-auto" onclick="agregar_destino_interno()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Agregar
            </button>
        </div>
      </div>
    </div>
</div>
<!-- MODAL DESTINO EXTERNO-->
<div id="destino_externo" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo-editar">Nuevo destino externo</h5>            
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="externo" class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Tipo de documento</label>
                        <select id="d_identidad_documento_id" class="form-select">
                            <option value="0">Seleccione...</option>
                            @foreach ($identidad_tipos as $identidad_tipo)
                            <option value="{{$identidad_tipo->id}}">{{$identidad_tipo->abreviatura}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">N° de documento</label>
                        <input id="d_nro_documento" type="text" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="form-group form-required">
                    <label class="form-label">Nombre / Razon</label>
                    <input id="d_nombre" type="text" placeholder="" class="form-control mayuscula">
                </div>  
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Cancelar
                </a>
                <button id="btn_destino" type="button" class="btn btn-primary ms-auto" onclick="agregar_destino_externo()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Agregar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BUSCAR ARCHIVO DIGITAL -->
<div id="buscar_modal" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buscar archivo digital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="position-relative overflow-auto">
                <div class="border-bottom pt-2 px-3" >
                    <div class="row">
                        <div class="col-md-4 pb-2">
                            <select id="ubicacion_select" class="form-select">
                                <option value="m">Mis archivos</option>
                                <option value="d">Archivos de dependencia</option>
                                <option value="c">Compartidos conmigo</option>
                            </select>
                        </div>
                        <div class="col-md-3 pb-2">
                            <select id="estado_select" class="form-select">
                                <option value="0">TODOS</option>
                                <option value="1">FIRMADOS</option>                                        
                            </select>
                        </div>
                        <div class="col-md-5 pb-2">
                            <div class="d-flex">                                       
                                <input id="texto_select" type="text" class="form-control" placeholder="Buscar...">                                       
                                <button class="btn btn-secondary align-top btn-icon ms-1" onclick="filtrar_documento();"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg></button>
                            </div>                                    
                        </div>
                    </div>
                </div>  
                <div id="origen_dependencia" class="border-bottom py-2 px-3" style="display: none;">  
                    <select id="dependencia_archivo_select" class="form-select">
                        @if(count($origenes) > 0)
                            @foreach ($origenes as $origen)
                            <option value="{{$origen->dependencia_id}}">{{$origen->dependencia->nombre}}</option>
                            @endforeach
                        @else
                        <option value="0">NO TIENES ASIGNADO UNA DEPENDENCIA</option>
                        @endif
                    </select>
                </div>            
                <div class="border-bottom py-2 px-3" style="color: #626976; background: rgb(242, 243, 244);">
                    <ol id="carpetas_buscar" class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">   
                    -                   
                    </ol>
                </div>
                <div class="">
                    <table class="table table-vcenter mb-1">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody id="tabla_buscar">
                            <tr>
                                <td colspan="4">
                                    Cargando...                                                         
                                </td>
                            </tr>                                       
                        </tbody>
                    </table>
                </div> 
                <div id="loading_buscar" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                    </div>
                </div>
            </div>          
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection