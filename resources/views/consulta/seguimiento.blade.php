@extends('layouts.blanco')

@section('titulo', 'UNAMAD - Consultas de Trámite Documentario - Seguimiento')

@section('css')
<link href="{{ asset('css/seguimiento.css?v='.config('app.version')) }}" rel="stylesheet" >
@endsection

@section('js')
<script src="{{ asset('js/tramite/seguimiento.js?v='.config('app.version')) }}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="container py-4">
    <div class="row">
        <div class="col-12 text-center mb-4">        
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
            </a>           
        </div>
    </div>
    <div class="page-header mb-4">
        <div class="row align-items-center mw-100">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('consultas') }}">Consultas</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('consultas/tramites') }}">Tramites</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seguimiento</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Seguimiento de trámite
                </h2>
            </div>  
            <div class="col-auto ms-auto">                
                <a href="{{ url('consultas/tramites') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="11" y2="18" /><line x1="5" y1="12" x2="11" y2="6" /></svg>
                    Regresar
                </a>                  
            </div>          
        </div>
    </div>    
    <div class="row row-cards">
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Trámite</h3>
                </div>
                <div class="card-body">
                    <dl>
                        <dt>Código Único de Trámite</dt>
                        <dd class="text-purple">T-{{ $tramite->codigo }}</dd>  
                        <dt>Origen</dt>
                        @if($tramite->o_tipo == 0)
                        <dd>INTERNO</dd>
                        @else
                        <dd>EXTERNO</dd>
                        @endif
                        
                        @if($tramite->procedimiento_id != null)                             
                        <dt>Procedimiento</dt>
                        <dd>{{ $tramite->procedimiento->titulo }}</dd>
                        @endif  

                        @if($tramite->observaciones != null)                             
                        <dt>Observaciones</dt>
                        <dd>{{ $tramite->observaciones }}</dd>
                        @endif

                        <dt>Estado</dt>
                        @if($tramite->estado == 1)   
                        <dd><span class="badge bg-green">ACTIVO</span></dd>
                        @elseif($tramite->estado == 2)
                        <dd><span class="badge bg-red">OBSERVADO</span></dd>
                        @else
                        <dd><span class="badge bg-secondary">ANULADO</span></dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Documentos</h3>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($documentos as $documento)
                    <div class="list-group-item">
                        <div class="row align-items-center">                              
                          <div class="col-auto">
                                @if($documento->archivo_id != null)                                    
                                <span class="bg-blue-lt avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                                </span>                                   
                                @else
                                <span class="avatar" title="SIN ARCHIVO DIGITAL">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="3" x2="21" y2="21" /><path d="M7 3h7l5 5v7m0 4a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14" /></svg>
                                </span>
                                @endif
                          </div>
                          <div class="col text-truncate">
                            <span class="text-body d-block lh-1" target="_blank">{{ $documento->documento_tipo->abreviatura }} {{ $documento->numero }}</span>
                            <small class="d-block text-muted text-truncate lh-1" title="{{ $documento->asunto }}">{{ $documento->asunto }}</small>
                            <h6 class="d-block lh-1 m-0" style="font-weight: 500;">
                                <span class="text-azure">D-{{ $documento->codigo }}</span> -
                                <span>{{ $documento->created_at->format('d/m/Y H:i') }}h</span>                               
                            </h6>
                          </div>
                          @if(count($documento->anexos) > 0)
                          <div class="col-auto" title="{{count($documento->anexos)}} ARCHIVOS ANEXOS">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 3v4a1 1 0 0 0 1 1h4" /><path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" /><path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>                                
                          </div>
                          @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-header">   
                    <div class="w-100">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">Movimientos</h3>
                            </div>
                            <div class="col-auto ms-auto">                                    
                                <div class="input-group">
                                    <label class="input-group-text" for="zoom">ZOOM</label>
                                    <select class="form-select" id="zoom">
                                        <option value="100">100%</option>
                                        <option value="90">90%</option>
                                        <option value="80">80%</option>
                                        <option value="70">70%</option>
                                        <option value="60">60%</option>
                                        <option value="50">50%</option>
                                    </select>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>                
                <div class="card-body">
                    <div id="s_helper" class="imprimible">
                        <div class="paso">
                            <div class="primero">
                                <div class="card s_box"> 
                                    <div class="card-header" style="padding: 0.75rem 1rem !important">
                                        <div class="w-100">
                                            <div class="row align-items-center">
                                                <div class="col-auto"> 
                                                    @if($tramite->o_tipo == 0)
                                                    <span class="bg-blue-lt avatar" title="ORIGEN INTERNO">                                                   
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><line x1="9" y1="8" x2="10" y2="8" /><line x1="9" y1="12" x2="10" y2="12" /><line x1="9" y1="16" x2="10" y2="16" /><line x1="14" y1="8" x2="15" y2="8" /><line x1="14" y1="12" x2="15" y2="12" /><line x1="14" y1="16" x2="15" y2="16" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                                                    </span>
                                                    @else
                                                    <span class="bg-pink-lt avatar" title="ORIGEN EXTERNO">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="3.6" y1="9" x2="20.4" y2="9" /><line x1="3.6" y1="15" x2="20.4" y2="15" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                                                    </span>
                                                    @endif
                                                </div>    
                                                <div class="col">
                                                    @if($tramite->o_tipo == 0)
                                                    <div class="fw-bold lh-1 text-truncate" title="{{ $tramite->o_dependencia->nombre }}">{{ $tramite->o_dependencia->nombre }} </div> 
                                                    <small class="text-muted text-truncate" title="{{ $tramite->o_dependencia->sede->nombre }}">{{ $tramite->o_dependencia->sede->nombre }}</small>
                                                    @else                                                            
                                                        @if($tramite->ruc != null)
                                                        <div class="fw-bold lh-1 text-truncate" title="{{ $tramite->ruc }}">{{ $tramite->ruc }} </div> 
                                                        <small class="text-muted text-truncate" title="{{ $tramite->razon_social }}">{{ $tramite->razon_social }}</small>
                                                        @else
                                                        <div class="fw-bold lh-1 text-truncate" title="{{ $tramite->o_nro_documento }}">{{ $tramite->o_nro_documento }} </div> 
                                                        <small class="text-muted text-truncate" title="{{ $tramite->o_nombre." ".$tramite->o_apaterno." ".$tramite->o_amaterno }}">{{ $tramite->o_nombre." ".$tramite->o_apaterno." ".$tramite->o_amaterno }}</small>
                                                        @endif                                                            
                                                    @endif
                                                </div>
                                            </div>
                                            @if($tramite->o_tipo == 1)
                                            <hr class="my-2">  
                                            <small class="lh-1 d-block text-muted">{{ $tramite->o_correo }}</small>
                                            <small class="lh-1 d-block text-muted">{{ ($tramite->o_telefono != null ? "Tel. ".$tramite->o_telefono : "") }}</small>
                                            <small class="lh-1 d-block text-muted">{{ ($tramite->o_direccion != null ? "Dir. ".$tramite->o_direccion : "") }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body" style="padding: 0.75rem 1rem; !important">
                                        <div class="w-100">
                                            <div class="row align-items-center">
                                                <div class="col">                   
                                                    <small class="d-block text-muted lh-1">Inicia trámite</small>
                                                    <div class="text-body lh-1">{{ $tramite->created_at->format('d/m/Y H:i') }}h</div>
                                                </div>
                                                <div class="col-auto">                   
                                                    <span class="avatar" title="{{ $tramite->user->nombre.' '.$tramite->user->apaterno.' '.$tramite->user->amaterno }}">{{ $tramite->user->siglas }}</span> 
                                                </div>
                                            </div>     
                                        </div>         
                                    </div>                                  
                                </div>
                            </div>
                            <div class="siguientes">
                                <div class="cv_linea"></div>
                                @each('secciones.movimiento', $ordenado, 'item')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                    Pendiente de recibir
                </li>
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18" /><line x1="13" y1="8" x2="15" y2="8" /><line x1="13" y1="12" x2="15" y2="12" /></svg>
                    Recibido
                </li>
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>
                    Derivado
                </li>
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="4" rx="2" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><line x1="10" y1="12" x2="14" y2="12" /></svg>
                    Atendido
                </li>
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                    Observado
                </li>
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-dark" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                    Anulado
                </li>
            </ul>
        </div>  
    </div>
</div>
@endsection