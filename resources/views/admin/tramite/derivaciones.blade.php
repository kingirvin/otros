@extends('layouts.admin')
@section('titulo', 'Derivaciones')

@section('js')
<script src="{{ asset('js/tramite/derivaciones.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/tramite/recepcion/recibidos') }}">Recibidos</a></li>                        
                        <li class="breadcrumb-item active" aria-current="page">Derivaciones</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Derivaciones
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-lg">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Trámite Documentario</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label class="form-label">Trámite</label>
                                <input type="text" class="form-control text-purple" value="T-{{ $movimiento->tramite->codigo}}" readonly>
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label class="form-label">Documento</label>
                                <input type="text" class="form-control" value="{{ $movimiento->documento->documento_tipo->abreviatura}} {{ $movimiento->documento->numero}}" readonly>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Asunto</label>
                            <input type="text" class="form-control" value="{{ $movimiento->documento->asunto}}" readonly>
                        </div> 
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Ubicación </label>
                            <input type="text" class="form-control" value="{{ $movimiento->d_dependencia->nombre}}" readonly>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Derivaciones</h4>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                        @foreach($siguientes as $siguiente)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                @if($siguiente->documento_id != $movimiento->documento_id)
                                <div class="col-auto" title="{{ $siguiente->documento->documento_tipo->abreviatura }} {{ $siguiente->documento->numero }}">                                
                                    <span class="avatar bg-blue-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                                    </span>                                
                                </div>
                                @endif
                                <div class="col text-truncate">
                                    <div class="text-body d-block">
                                        @if($siguiente->d_tipo == 0)
                                        {{ $siguiente->d_dependencia->nombre }}
                                        @else
                                        {{ $siguiente->d_nombre }}
                                        @endif
                                    </div>
                                    <small class="d-block text-muted text-truncate mt-n1">
                                        {{ $siguiente->o_fecha->format('d/m/Y H:i') }} h &#183;
                                        @if($siguiente->estado > 1)
                                        <span  class="text-primary">RECEPCIONADO</span>                                        
                                        @else
                                        <span class="text-warning">PENDIENTE</span>
                                        @endif
                                    </small>                                    
                                </div>
                                <div class="col-auto">
                                    @if($siguiente->estado > 1)
                                    <span class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="3" x2="21" y2="21" /><path d="M4 7h3m4 0h9" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="14" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l.077 -.923" /><line x1="18.384" y1="14.373" x2="19" y2="7" /><path d="M9 5v-1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </span>
                                    @else
                                    <a href="javascript:void(0);" onclick="anular_movimiento({{ $siguiente->id }});" class="list-group-item-actions show">                                       
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </a>
                                    @endif
                                </div>    
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection