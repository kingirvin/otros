@extends('layouts.admin')

@section('titulo', 'Administrar repositorios')

@section('js')
<script src="{{ asset('js/certificado/publicar.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/certificado') }}">Certificados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Publicar</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Publicar certificados
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6 select_label_container">
                <div class="select_label_min">REPOSITORIO</div>
                <select id="repositorio_select" class="form-select mb-3"> 
                    @if(count($repositorios)>0)    
                    @foreach ($repositorios as $asignado)
                    <option value="{{$asignado->cert_repositorio_id}}">{{$asignado->repositorio->nombre}}</option>    
                    @endforeach                    
                    @else
                    <option value="0">No tienes asignado un repositorio</option>    
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <div class="dropdown mb-3">                    
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        AGREGAR
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="nuevo_archivo();">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><polyline points="9 14 12 11 15 14" /></svg>
                                Subir archivos
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
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary w-100 mb-3" onclick="firmar_seleccionado();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17c3.333 -3.333 5 -6 5 -8c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 4.877 2.5 6c1.5 2 2.5 2.5 3.5 1l2 -3c.333 2.667 1.333 4 3 4c.53 0 2.639 -2 3 -2c.517 0 1.517 .667 3 2" /></svg>
                    FIRMAR
                </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-purple w-100 mb-3" onclick="publicar_seleccionado();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2" /><path d="M4 16v2a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v2" /><path d="M16 20h2a2 2 0 0 0 2 -2v-2" /><rect x="8" y="11" width="8" height="5" rx="1" /><path d="M10 11v-2a2 2 0 1 1 4 0v2" /></svg>
                    PUBLICAR
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header pb-1">
                        <div class="w-100">
                            <div class="row align-items-center">
                                <div class="col-md-9 pb-2">
                                    <nav aria-label="breadcrumb">
                                        <ol id="navegacion" class="breadcrumb">                                        
                                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Cargando...</a></li>
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
                                    <th class="w-1">
                                        
                                    </th>
                                    <th>NOMBRE</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th class="w-1">FECHA</th>
                                    <th class="w-1">ESTADO</th>
                                    <th class="w-1">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_file">
                                <tr>
                                    <td colspan="6">Cargando...</td>
                                </tr>                                                                                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <ul class="list-inline">
                    <li class="list-inline-item"><span class="badge bg-yellow"></span> Carpeta</li>
                    <li class="list-inline-item"><span class="badge"></span> Archivo error</li>
                    <li class="list-inline-item"><span class="badge bg-blue"></span> Archivo para firma</li>
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
            <div class="form-group form-required mb-3">
                <label class="form-label">Archivo</label> 
                <input id="input_subir" name="archivos[]" type="file" class="form-control" multiple="multiple" accept=".pdf,.PDF">
                <small class="form-hint">Puede seleccionar multiples archivos en formato PDF.</small>
            </div>
            <div class="form-group form-required">
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
@endsection
