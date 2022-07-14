@extends('layouts.admin')
@section('titulo', 'Recibir documentos')
@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script>
    const elUser = {{$user->id}};
</script>
<script src="{{ asset('js/tramite/recibir_documentos.js') }}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/tramite') }}">Trámite</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Recibir</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Recibir documentos
                </h2>
            </div> 
            <!--<div class="col-auto ms-auto">
                <div class="btn-list">
                    <a href="{{url('admin/tramite/emision/nuevo')}}" class="btn btn-success d-sm-inline-block" >
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Agregar
                    </a>
                </div>
            </div>-->
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-4" style="padding-bottom: .5rem;">
                                    <select id="dependencia_select" class="form-select" title="DEPENDENCIA DESTINO">
                                        @if(count($destinos) > 0)
                                            @foreach ($destinos as $destino)
                                            <option value="{{ $destino->dependencia_id }}">{{ $destino->dependencia->nombre }}</option>
                                            @endforeach
                                        @else
                                            <option value="0">NO TIENES ASIGNADO UNA DEPENDENCIA</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-2 pb-2" style="padding-bottom: .5rem;" title="AÑO DE REGISTRO">
                                    <select id="year_select" class="form-select">
                                        @for ($i = 0; $i < 10; $i++)
                                        <option value="{{$ahora->year - $i}}">{{$ahora->year - $i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_por_recepcionar" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>TRÁMITE</th>
                                    <th>DOCUMENTO</th>
                                    <th></th>
                                    <th>ORIGEN</th>
                                    <th>MOTIVO</th>
                                    <th>REGISTRA</th>
                                    <th>FECHA</th>
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>   
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal')
<!-- MODAL RECIBIR DOCUMENTO -->
<div id="recibir" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo-carpeta" class="modal-title">Recibir documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Tramite</label>
                        <input id="r_tramite" type="text" class="form-control text-purple" readonly="">
                    </div>
                    <div class="col-md-8 form-group mb-3">
                        <label class="form-label">Documento</label>
                        <input id="r_documento" type="text" class="form-control" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group mb-3">
                        <label class="form-label">Remitente</label>
                        <input id="r_remitente" type="text" class="form-control" readonly="">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Folios</label>
                        <input id="r_folios" type="text" class="form-control" readonly="">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Asunto</label>
                    <input id="r_asunto" type="text" class="form-control" readonly="">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Motivo</label>
                    <input id="r_motivo" type="text" class="form-control" readonly="">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">Observaciones</label>
                    <textarea id="d_observacion" class="form-control mayuscula" rows="2"></textarea>
                </div> 
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection
