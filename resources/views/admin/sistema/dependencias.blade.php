@extends('layouts.admin')
@section('titulo', 'Dependencias')
@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sistema/dependencias.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Dependencias</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Dependencias
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
                                    <select id="sede_select" class="form-select" title="SEDES">
                                    <option value="0">SEDES - [TODOS]</option>
                                        @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_dependencias" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>SEDE</th>
                                    <th>ABREV.</th>
                                    <th>NOMBRE</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th title="EMPLEADOS">EMP.</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7">Cargando...</td>
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar" class="modal-title">Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body">  
                <div class="form-group form-required mb-3">
                    <label class="form-label">Sede</label>
                    <select id="sede_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($sedes as $sede)
                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group form-required mb-3">
                        <label class="form-label">Abreviatura</label>
                        <input id="abreviatura" type="text" class="form-control mayuscula" placeholder="" maxlength="20">
                    </div>
                    <div class="col-md-8 form-group form-required mb-3">
                        <label class="form-label">Nombre</label>
                        <input id="nombre" type="text" class="form-control mayuscula" placeholder="">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control mayuscula" rows="3"></textarea>
                </div> 
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Teléfono</label>
                        <input id="telefono" type="text" class="form-control" placeholder="" maxlength="20">
                    </div>
                    <div class="col-md-8 form-group mb-3">
                        <label class="form-label">Correo</label>
                        <input id="correo" type="text" class="form-control" placeholder="">
                    </div>
                </div>                        
            </div>   
            <div class="modal-footer">
                <div>
                    <label class="form-check form-switch mb-0">
                        <input id="estado" class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Estado</span>
                    </label>
                </div>
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection
