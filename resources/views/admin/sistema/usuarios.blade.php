@extends('layouts.admin')
@section('titulo', 'Usuarios de sistema')
@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sistema/usuarios.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Usuarios de sistema
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
                    <div id="t_principal">
                        <table id="t_usuario" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>   
                                    <th>TIPO</th>                                 
                                    <th>DOCUMENTO</th>
                                    <th>NOMBRE</th>
                                    <th></th>
                                    <th></th>
                                    <th>ROL</th>
                                    <th>EMAIL</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9">Cargando...</td>
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
<!-- MODAL NUEVO -->
<div id="editar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body pb-1">
                <div class="form-group form-required mb-3">
                    <label class="form-label">Persona</label>
                    <select id="persona_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
                </div>
                <div class="form-group mb-3 form-required">
                    <label class="form-label">Rol</label>
                    <select id="rol_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>  
                <div class="form-group form-required mb-3">
                    <label class="form-label">Correo</label>
                    <input id="email" type="email" class="form-control validar_correo" >
                </div>                 
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label">Contraseña</label>
                        <input id="password" type="password" class="form-control validar_minimo:8" >
                    </div> 
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input id="password_confirmed" type="password" class="form-control validar_igual:password" >
                    </div> 
                </div>                 
            </div>   
            <div class="modal-footer">                
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_nuevo()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
<!-- MODAL MODIFICAR -->
<div id="modificar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifcar registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_modificar" class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Correo</label>
                    <input id="m_email" type="text" class="form-control" readonly>
                </div>              
                <div class="form-group form-required">
                    <label class="form-label">Rol</label>
                    <select id="m_rol_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>                             
            </div>   
            <div class="modal-footer">
                <div>
                    <label class="form-check form-switch mb-0">
                        <input id="estado" class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Activo</span>
                    </label>
                </div>
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_modificar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
<!-- MODAL PASSWORD -->
<div id="m_password" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_password" class="modal-body"> 
                <div class="form-group mb-3">
                    <label class="form-label">Correo</label>
                    <input id="p_email" type="text" class="form-control" readonly>
                </div> 
                <div class="form-group form-required mb-3">
                    <label class="form-label">Contraseña</label>
                    <input id="pchange" type="password" class="form-control validar_minimo:8" >
                </div> 
                <div class="form-group form-required">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input id="pchange_confirmed" type="password" class="form-control validar_igual:pchange" >
                </div> 
            </div>   
            <div class="modal-footer">                
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_password()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection
