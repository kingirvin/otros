@extends('layouts.admin')
@section('titulo', 'Documentos recibidos')
@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script>
    const elUser = {{$user->id}};
</script>
<script src="{{ asset('js/tramite/documentos_recibidos.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Recibidos</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Documentos recibidos
                </h2>
            </div> 
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
                                <div class="col-md-2 pb-2" style="padding-bottom: .5rem;" title="ESTADO">
                                    <select id="estado_select" class="form-select">
                                        <option value="0">ESTADO - [TODOS]</option>
                                        <option value="2">PENDIENTE</option>
                                        <option value="3">DERIVADO</option>
                                        <option value="4">ATENDIDO</option>
                                        <option value="5">OBSERVADO</option>
                                    </select>
                                </div>     
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_recepcionados" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>NÚMERO</th>
                                    <th>TRÁMITE</th>
                                    <th>DOCUMENTO</th>
                                    <th></th>
                                    <th>ORIGEN</th>
                                    <th>MOTIVO</th>
                                    <th>RECIBE</th>
                                    <th>FECHA</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10">Cargando...</td>
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
<!-- MODAL ATENDER -->
<div id="atender" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Marcar como atendido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Trámite</label>
                        <input id="tramite_fin" type="text" class="form-control text-purple" readonly="">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Documento</label>
                        <input id="documento_fin" type="text" class="form-control" readonly="">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Asunto</label>
                    <input id="asunto_fin" type="text" class="form-control" readonly="">
                </div>
                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <textarea id="observacion_fin" class="form-control mayuscula" rows="3"></textarea>
                </div>                          
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_atender()">Guardar</button>
            </div>       
        </div>
    </div>
</div>


<!-- MODAL OBSERVAR -->
<div id="observar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar observación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Trámite</label>
                        <input id="tramite_obs" type="text" class="form-control text-purple" readonly="">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Documento</label>
                        <input id="documento_obs" type="text" class="form-control" readonly="">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Asunto</label>
                    <input id="asunto_obs" type="text" class="form-control" readonly="">
                </div>
                <div class="form-group form-required">
                    <label class="form-label">Observaciones</label>
                    <textarea id="detalle_obs" class="form-control mayuscula" rows="2"></textarea>
                </div>                          
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_observacion()">Guardar</button>
            </div>       
        </div>
    </div>
</div>

<!-- MODAL OBSERVACIONES -->
<div id="observaciones" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Observaciones registradas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Trámite</label>
                        <input id="tramite_obnes" type="text" class="form-control text-purple" readonly="">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Documento</label>
                        <input id="documento_obnes" type="text" class="form-control" readonly="">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Asunto</label>
                    <input id="asunto_obnes" type="text" class="form-control" readonly="">
                </div>   
                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <div id="lista_obnes" class="list-group list-group-hoverable">                    
                    </div>
                </div>
                <div id="cargando_observaciones" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                    </div>
                </div>                          
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>       
        </div>
    </div>
</div>

@endsection
