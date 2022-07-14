@extends('layouts.admin')
@section('titulo', 'Archivos digitales')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/para_firma.css?v='.config('app.version')) }}" rel="stylesheet">
@endsection

@section('js')
<script>
    const laUbicacion = '{{$ubicacion}}';
    const elUser = {{$user->id}};

    var datos_firma = {
        archivo_id: 0,
        num_pagina: 0,
        motivo: 'Soy el autor del documento',
        exacto: 1,
        pos_pagina: '0-0',
        apariencia: 0
    };

</script>
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/clientclickonce.js"></script>
<script src="{{ asset('js/tramite/firma_digital.js?v='.config('app.version')) }}" type="text/javascript"></script>
<script src="{{ asset('js/tramite/archivos.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Archivos</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Archivos digitales
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-lg">
        <div class="row ">
            <div class="col-md-3">
                @if($ubicacion == 'm' || $ubicacion == 'd')
                <div class="dropdown mb-3">                    
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        AGREGAR
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="nuevo_archivo();">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><polyline points="9 14 12 11 15 14" /></svg>
                                Subir archivo
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="nueva_carpeta();">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /><line x1="12" y1="10" x2="12" y2="16" /><line x1="9" y1="13" x2="15" y2="13" /></svg>
                                Nueva carpeta
                            </a>
                        </li>                        
                    </ul>                   
                </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Ubicación</h3>
                    </div>
                    <div class="card-body">
                       <div class="px-2">
                            <ul class="nav nav-pills nav-vertical">
                                <li class="nav-item">
                                    <a class="nav-link {{$ubicacion == 'm' ? 'active' : ''}}" href="{{ url('admin/tramite/archivos?ubicacion=m') }}">                                        
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 15h-6a1 1 0 0 1 -1 -1v-8a1 1 0 0 1 1 -1h6" /><rect x="13" y="4" width="8" height="16" rx="1" /><line x1="7" y1="19" x2="10" y2="19" /><line x1="17" y1="8" x2="17" y2="8.01" /><circle cx="17" cy="16" r="1" /><line x1="9" y1="15" x2="9" y2="19" /></svg>
                                        </span>                                        
                                        Mis archivos                                        
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{$ubicacion == 'd' ? 'active' : ''}}" href="{{ url('admin/tramite/archivos?ubicacion=d') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><line x1="9" y1="8" x2="10" y2="8" /><line x1="9" y1="12" x2="10" y2="12" /><line x1="9" y1="16" x2="10" y2="16" /><line x1="14" y1="8" x2="15" y2="8" /><line x1="14" y1="12" x2="15" y2="12" /><line x1="14" y1="16" x2="15" y2="16" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                                        </span>                                        
                                        Archivos de dependencia
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{$ubicacion == 'c' ? 'active' : ''}}" href="{{ url('admin/tramite/archivos?ubicacion=c') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                        </span>                                        
                                        Compartidos conmigo
                                    </a>
                                </li>
                            </ul>    
                       </div>
                    </div>
                </div>                      
            </div>  
            <div class="col-md-9">
                <div class="card mb-3">
                    @if($ubicacion == 'd' && count($empleos) > 0)
                    <div class="card-header">                        
                        <div class="w-100">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h3 class="card-title">Dependencia</h3>
                                </div>
                                <div class="col-md-8">
                                    <select id="dependencia_select" class="form-select">
                                        @foreach ($empleos as $empleo)
                                        <option value="{{$empleo->dependencia_id}}">{{$empleo->dependencia->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                            </div>
                        </div>                                              
                    </div>
                    @endif
                    <div class="card-header pb-1">
                        <div class="w-100">
                            <div class="row align-items-center">
                                <div class="col-md-9 pb-2">
                                    <nav aria-label="breadcrumb">
                                        <ol id="navegacion" class="breadcrumb">
                                            @if($ubicacion == 'd' && count($empleos) == 0)      
                                            <li class="breadcrumb-item active" aria-current="page"><a href="#">El usuario no tiene asignado una dependencia</a></li>
                                            @else
                                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Cargando...</a></li>
                                            @endif  
                                        </ol>
                                    </nav>
                                </div>
                                <div class="col-md-3 pb-2">
                                    <div class="d-flex">                                       
                                        <input id="texto_select" type="text" class="form-control" placeholder="Buscar...">                                       
                                        <button class="btn btn-secondary align-top btn-icon ms-1" onclick="buscar();"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="10" cy="10" r="7"></circle><line x1="21" y1="21" x2="15" y2="15"></line></svg></button>
                                    </div>
                                </div>
                            </div>  
                        </div>                      
                    </div>
                    <div class="table-responsive border-top">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th class="w-1">FECHA</th>
                                    <th class="w-1">ESTADO</th>
                                    <th class="w-1">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_file">
                                <tr>
                                    <td colspan="4">Cargando...</td>
                                </tr>                                                                                                
                            </tbody>
                        </table>
                    </div>
                </div>                  
                <ul class="list-inline">
                    <li class="list-inline-item"><span class="badge bg-yellow"></span> Carpeta</li>
                    <li class="list-inline-item"><span class="badge"></span> Archivo simple</li>
                    <li class="list-inline-item"><span class="badge bg-blue"></span> Documento para firma</li>
                </ul>
            </div>           
        </div>
    </div>
</div>
@endsection
@section('modal')
<!-- MODAL EDITAR CARPETA-->
<div id="editar_carpeta" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar_carpeta" class="modal-title">Nueva carpeta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_carpeta" class="modal-body">
                <div class="form-group form-required">
                    <label class="form-label">Nombre</label>
                    <input id="nombre_carpeta" type="text" class="form-control mayuscula validar_no_especial" placeholder="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_carpeta()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MOVER CARPETA-->
<div id="mover_carpeta" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_mover_carpeta" class="modal-title">Ubicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">  
                <div class="bg-white">                    
                    <div id="lista_carpetas" class="list-group list-group-flush">
                        <div class="list-group-item py-2" style="color: #626976; background: rgb(242, 243, 244);">
                            <div class="row align-items-center">
                                <div class="col">
                                     <div class="h3 mb-0">
                                        Ubicación actual
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            Cargando...
                        </div>
                    </div>
                </div>
                <div id="cargando_mover" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                    </div>
                </div>
            </div>   
            <div class="modal-footer py-2 border-top">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="boton_guardar_mover" type="button" class="btn btn-primary" onclick="guardar_mover()">Mover aqui</button>
            </div>       
        </div>
    </div>
</div>

<!-- MODAL ARCHIVOS -->
<div id="cargar_archivo" class="modal modal-blur fade"  tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cargar archivo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="cargar_contenido" class="modal-body">
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-check form-switch">
                        <input id="para_firma" class="form-check-input" type="checkbox" checked>
                        <span class="form-check-label" style="font-weight: 500;">PARA FIRMA DIGITAL</span>
                    </label>
                </div>             
                <small class="form-hint">
                    Los documentos cargados para firma digital deberán estar en <b>formato PDF</b> y deberán contemplar el <a href="{{ asset('pdf/guia_documento.pdf') }}" target="_blank">diseño recomendado</a> para documentos firmados digitalmente.
                </small>
            </div>                              
            <div class="form-group form-required mb-3">
                <label class="form-label">Archivo</label>
                <input id="input_subir" type="file" class="form-control" accept=".pdf,.PDF">              
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea id="descripcion" class="form-control mayuscula" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary ms-auto" onclick="cargar_archivo();">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" /><polyline points="9 15 12 12 15 15" /><line x1="12" y1="12" x2="12" y2="21" /></svg>
                Enviar
            </button>
        </div>
      </div>
    </div>
</div>


<!-- MODAL COMPARTIR -->
<div id="compartir" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Compartir archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Compartido con</label>            
                <div class="form-fieldset p-0 mb-0">
                    <div id="lista_compartido" class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row align-items-center">                    
                            Cargando...
                            </div>
                        </div>
                    </div>
                </div>
                <div id="cargando_compartir" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                    </div>
                </div>
            </div>
            <div id="form_compartir" class="modal-body">  
                <div class="form-group form-required">
                    <label class="form-label">Usuario</label>
                    <select id="user_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
                </div>            
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_compartir()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL VERSIONES -->
<div id="versiones" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historico de cambios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <div class="form-group mb-3">
                    <label class="form-label">Versión actual</label>
                    <div class="form-fieldset p-0 mb-0">
                        <div class="list-group list-group-flush">                  
                            <div class="list-group-item">
                                <div class="row align-items-center">                                
                                    <div class="col text-truncate">
                                        <div id="version_actual">-</div>
                                        <div id="datos_actual" class="mt-n1">
                                        -
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="form-group">              
                    <label class="form-label">Anteriores</label> 
                    <div class="form-fieldset p-0 mb-0">
                        <div id="lista_cambios" class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="row align-items-center">                    
                                Cargando...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="cargando_cambios" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                    </div>
                </div>
            </div>          
            <div class="modal-footer">
              <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL FIRMAR -->
<div id="firmar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Firmar archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <div class="f_documento shadow-sm d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="1-1">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="1-2">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="1-3" checked="">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="2-1">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="2-2">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="2-3">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="3-1">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="3-2">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="3-3">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="4-1">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="4-2">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="4-3">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <input class="form-check-input" type="radio" name="f_posicion" value="5-1" disabled>
                                    <input class="form-check-input" type="radio" name="f_posicion" value="5-2" disabled>
                                    <input class="form-check-input" type="radio" name="f_posicion" value="5-3" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <div class="form-label">Ubicación de la firma</div>
                            <select id="num_pagina" class="form-select">
                              <option value="1">Primera página</option>
                              <option value="0">Utlima página</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-label">Motivo de la firma</div>
                            <select id="motivo" class="form-select">
                                <option value="Soy el autor del documento">Soy el autor del documento</option>
                                <option value="En señal de conformidad">En señal de conformidad</option>
                                <option value="Doy V° B°">Doy V° B°</option>
                                <option value="Por encargo">Por encargo</option>
                                <option value="Doy fé">Doy fé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Apariencia de la firma</div>
                            <select id="apariencia" class="form-select">
                                <option value="0">Sello + Descripción Horizontal</option>
                                <option value="1">Sello + Descripción Vertical</option>
                                <!--<option value="2">Solo sello</option>
                                <option value="3">Solo descripción</option>-->
                            </select>
                        </div>
                    </div>
                </div>                          
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-ghost-purple" onclick="firma_avanzada();">                    
	                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-4a3 3 0 0 1 3 -3h5" /><path d="M9 17l3 -3l-3 -3" /><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 11v-6a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-9.5" /></svg>
                    Posición personalizada
                </button>
                <button type="button" class="btn btn-primary ms-auto" onclick="enviar_firma();">                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19c3.333 -2 5 -4 5 -6c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 2.877 2.5 4c1.5 2 2.5 2.5 3.5 1c.667 -1 1.167 -1.833 1.5 -2.5c1 2.333 2.333 3.5 4 3.5h2.5" /><path d="M20 17v-12c0 -1.121 -.879 -2 -2 -2s-2 .879 -2 2v12l2 2l2 -2z" /><path d="M16 7h4" /></svg>
                    Enviar
                </button>
            </div>       
        </div>
    </div>
</div>


<input type="hidden" id="argumentos" value="" />
<div id="addComponent"></div>
@endsection