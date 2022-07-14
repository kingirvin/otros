@extends('layouts.admin')
@section('titulo', 'Documento')

@section('js')
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
                        <li class="breadcrumb-item active" aria-current="page">Documento</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Documento
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-lg">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Detalles de documento</h3>
                    </div>
                    <div class="card-body">
                        <dl class="mb-2">                       
                            <dt>Tipo de documento</dt>
                            <dd>{{ $documento->documento_tipo->nombre }}</dd>
                            <dt>Número de documento</dt>
                            <dd>{{ $documento->numero }}</dd>     
                            <dt>Remitente</dt>
                            <dd>{{ $documento->remitente }}</dd>                             
                        </dl>  
                        <div class="row">
                            <div class="col">
                                <dl class="mb-2">                                    
                                    <dt>Fecha de registro</dt>
                                    <dd>{{ $documento->created_at->format('d/m/Y') }}</dd>                                     
                                </dl>
                            </div>
                            <div class="col">
                                <dl class="mb-2"> 
                                    <dt>Folios</dt>
                                    <dd>{{ $documento->folios }}</dd>                                     
                                </dl>
                            </div>
                        </div>
                        <dl>                            
                            <dt>Asunto</dt>
                            <dd>{{ $documento->asunto }}</dd>                   
                        </dl>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Anexos</h3>
                    </div>
                    @if($documento->anexos_url != null)
                    <div class="card-body">
                        <a href="{{ $documento->anexos_url }}" target="_blank">{{ $documento->anexos_url }}</a>
                    </div>
                    @endif
                    @if(count($documento->anexos) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($documento->anexos as $anexo)
                        <div class="list-group-item">
                            <div class="row align-items-center">                              
                                <div class="col-auto">                                   
                                    <span class="avatar">                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                    </span>                                    
                                </div>
                                <div class="col">
                                    <a href="{{ url('admin/archivos/download/'.$anexo->archivo->id) }}" class="text-body d-block text-truncate" >{{ $anexo->archivo->nombre }}</a>
                                    <small class="d-block text-muted text-truncate mt-n1">{{ strtoupper($anexo->archivo->formato) }} | {{ $anexo->archivo->formatsize }}</small>
                                </div>                              
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif 
                    @if(count($documento->anexos) == 0 && $documento->anexos_url == null)
                    <div class="card-body text-muted">
                        SIN ANEXOS
                    </div>
                    @endif                    
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Vista previa</h3>
                    </div>
                    @if($documento->archivo_id != null)
                    <div class="p-0" style="border-radius: 0;">
                        <div class="movil text-center py-3">
                            <a href="{{ url('admin/archivos/download/'.$documento->archivo->id) }}" class="btn btn-primary" download>Descargar</a>
                        </div>
                        <div class="escritorio">
                            <embed src="{{ url('admin/archivos/stream/'.$documento->archivo->codigo).'?t='.time() }}" width="100%" height="600px" type="application/pdf">
                        </div>
                    </div>
                    @else
                    <div class="card-body text-muted">
                        NO TIENE ARCHIVO DIGITALIZADO
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection