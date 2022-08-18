@extends('layouts.admin')
@section('titulo', 'Ventanilla virtual')
@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center mw-100">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ventanilla</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Ventanilla virtual
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                @if(session('error'))
                <div class="alert alert-important alert-danger alert-dismissible" role="alert">                                      
                    {{ session('error') }}               
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif   

                @if($errors->any())
                <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                    <ul class="m-0 ps-0" style="list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            </div>
        </div>
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mis documentos</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th class="w-1">FECHA</th>
                                    <th class="w-1">TRÁMITE</th>
                                    <th>DOCUMENTO</th>
                                    <th>ASUNTO</th>
                                    <th>PROCEDIMIENTO</th>
                                    <th class="w-1">ESTADO</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($tramites) > 0)
                                    @foreach ($tramites as $tramite)
                                    <tr>
                                        <td>
                                            <div class="">{{ $tramite->created_at->format('d/m/Y') }}</div>
                                            <small class="d-block lh-1 text-muted">{{ $tramite->created_at->format('H:i').'h' }}</small>
                                        </td>
                                        <td class="nowrap text-purple">{{ "T-".$tramite->codigo }}</td>
                                        <td>
                                            <small class="d-block text-muted">{{ $tramite->primero_documento->documento_tipo->nombre }}</small>
                                            <div class=" lh-1" title="{{ $tramite->primero_documento->numero }}">
                                                {{ (strlen($tramite->primero_documento->numero) > 30 ? substr($tramite->primero_documento->numero,0,30)."..." : $tramite->primero_documento->numero) }}
                                            </div>
                                        </td>
                                        <td>
                                            <small class="d-block lh-1" title="{{ $tramite->primero_documento->asunto }}">
                                                {{ (strlen($tramite->primero_documento->asunto) > 30 ? substr($tramite->primero_documento->asunto,0,30)."..." : $tramite->primero_documento->asunto) }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($tramite->procedimiento_id != null)
                                                <span title="{{ $tramite->procedimiento->titulo }}">
                                                    {{ (strlen($tramite->procedimiento->titulo) > 50 ? substr($tramite->procedimiento->titulo,0,50)."..." : $tramite->procedimiento->titulo) }}
                                                </span>                                                
                                            @else
                                                <div class="text-muted">NO SELECCIONADO</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tramite->estado == 0)
                                            <span class="badge bg-secondary">ANULADO</span>
                                            @elseif($tramite->estado == 1)
                                            <span class="badge bg-success">ACTIVO</span>
                                            @elseif($tramite->estado == 2)
                                            <span class="badge bg-danger">OBSERVADO</span>
                                            @else
                                            <span class="badge bg-secondary">UNKNOW</span>
                                            @endif                                            
                                        </td>
                                        <td>                                            
                                            <a href="{{ url('admin/externo/tramite/seguimiento/'.$tramite->codigo) }}" class="btn btn-outline-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                                                Consultar
                                            </a>                                            
                                        </td>
                                    </tr>
                                    @endforeach

                                @else
                                <tr>
                                    <td colspan="6">No se encontraron registros</td>
                                </tr>
                                @endif                         
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">                        
                        {{ $tramites->links('secciones.paginacion') }}                        
                    </div>
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection