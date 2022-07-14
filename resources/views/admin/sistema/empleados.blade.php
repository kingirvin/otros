@extends('layouts.admin')
@section('titulo', 'Empleados')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/tabler/libs/litepicker/dist/litepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script>
    var sedes = {!! $sedes !!};
</script>
<script src="{{ asset('js/sistema/empleados.js') }}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/sistema') }}">Sistema</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Empleados</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Empleados
                </h2>
            </div> 
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <button onclick="nuevo();" class="btn btn-success d-sm-inline-block" >
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-4" style="padding-bottom: .5rem;">
                                    <select id="dependencia_select" class="form-select" title="DEPENDENCIAS">
                                        <option value="0">DEPENDENCIA - [TODOS]</option>
                                        @foreach ($sedes as $sede)
                                            @foreach ($sede->dependencias as $dependencia)
                                            <option value="{{ $dependencia->id }}">{{ $sede->abreviatura }} | {{ $dependencia->nombre }}</option>
                                            @endforeach                                    
                                        @endforeach
                                    </select>
                                </div>                             
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_empleado" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>                                                                  
                                    <th>DOCUMENTO</th>
                                    <th>NOMBRE</th>
                                    <th></th>
                                    <th></th>
                                    <th>DEPENDENCIA</th>
                                    <th>CARGO</th>
                                    <th>INICIO</th>
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
<!-- MODAL MODIFICAR -->
<div id="editar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar" class="modal-title">Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body pb-0">  
                <div class="form-group form-required mb-3">
                    <label class="form-label">Persona</label>
                    <select id="persona_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
                </div>
                <div class="form-group form-required mb-3">
                    <label class="form-label">Sede</label>
                    <select id="sede_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($sedes as $sede)
                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                        @endforeach 
                    </select>
                </div> 
                <div class="form-group form-required mb-3">
                    <label class="form-label">Dependencia</label>
                    <select id="dependencia_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                    </select>
                </div> 
                <div class="form-group form-required mb-3">
                    <label class="form-label">Cargo</label>
                    <input id="cargo" type="text" class="form-control mayuscula" placeholder="">
                </div>
                <div class="row">
                    <div class="form-group col-md-6 form-required mb-3">
                        <label class="form-label">Fecha de inicio</label>
                        <input id="fecha_inicio" type="text" class="form-control validar_fecha" placeholder="">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Fecha de término</label>
                        <input id="fecha_termino" type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>   
            <div class="modal-footer">
                <div>
                    <label class="form-check form-switch mb-0">
                        <input id="revocar_anterior" class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Finalizar anteriores</span>
                    </label>
                </div>
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection
