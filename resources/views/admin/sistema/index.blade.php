@extends('layouts.admin')
@section('titulo', 'Administración de Sistema')

@section('js')
<script src="{{ asset('js/sistema/pruebas.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Sistema</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Administración de Sistema
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        {{$s_operativo}}
                        <hr class="my-3">
                        <p class="mb-3">Disco [{{ $almacenamiento['disco'] }}], utilizado el <strong>{{ $almacenamiento['porcentaje'] }}% </strong>de {{ $almacenamiento['total_disk_text'] }}</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-green" style="width: {{ $almacenamiento['porcentaje'] }}%" role="progressbar" aria-valuenow="{{ $almacenamiento['porcentaje'] }}" aria-valuemin="0" aria-valuemax="100">
                                <span class="visually-hidden">{{ $almacenamiento['porcentaje'] }}% Completo</span>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-auto d-flex align-items-center pe-2">
                            <span class="legend me-2 bg-green"></span>
                            <span>Usado</span>
                            <span class="d-none d-md-inline  d-xxl-inline ms-2 text-muted">{{ $almacenamiento['used_disk_text'] }}</span>
                          </div>
                          <div class="col-auto d-flex align-items-center ps-2">
                            <span class="legend me-2"></span>
                            <span>Libre</span>
                            <span class="d-none d-md-inline  d-xxl-inline ms-2 text-muted">{{ $almacenamiento['free_disk_text'] }}</span>
                          </div>
                        </div>
                    </div>
                </div>                 
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-sm mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <span class="bg-green-lt avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><line x1="9" y1="8" x2="10" y2="8" /><line x1="9" y1="12" x2="10" y2="12" /><line x1="9" y1="16" x2="10" y2="16" /><line x1="14" y1="8" x2="15" y2="8" /><line x1="14" y1="12" x2="15" y2="12" /><line x1="14" y1="16" x2="15" y2="16" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $lugares['dependencias'] }} Dependencias
                                    </div>
                                    <small class="text-muted">
                                        {{ $lugares['sedes'] }} Sedes
                                    </small>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-sm mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <span class="bg-blue-lt avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11l2 2l4 -4" /></svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $usuarios['activos'] }} Usuarios activos
                                    </div>
                                    <small class="text-muted">
                                        {{ $usuarios['internos'] }} internos / {{ $usuarios['externos'] }} externos 
                                    </small>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-purple-lt avatar avatar-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <h4 class="card-title m-0">
                                            Tiempo de carga
                                        </h4>
                                        <div class="small text-muted">                                    
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" /><polyline points="9 15 12 12 15 15" /><line x1="12" y1="12" x2="12" y2="21" /></svg>
                                            <span id="ajax_time">-</span>
                                        </div>
                                        <div class="small text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="8" rx="3" /><rect x="3" y="12" width="18" height="8" rx="3" /><line x1="7" y1="8" x2="7" y2="8.01" /><line x1="7" y1="16" x2="7" y2="16.01" /></svg>
                                            <span id="php_time">-</span>
                                        </div>
                                        <div class="small text-muted">                                    
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><ellipse cx="12" cy="6" rx="8" ry="3"></ellipse><path d="M4 6v6a8 3 0 0 0 16 0v-6" /><path d="M4 12v6a8 3 0 0 0 16 0v-6" /></svg>
                                            <span id="database_time">-</span>
                                        </div>
                                    </div>                                                 
                                </div>
                                <div id="cargando_evaluar" class="cargando">
                                    <div class="text-center pt-4">
                                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0);" onclick="evaluar_tiempos();" class="card-btn">Volver a cargar</a>
                        </div>  
                    </div>
                </div>
                              
            </div>
        </div>
    </div>
</div>
@endsection