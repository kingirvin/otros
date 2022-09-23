@extends('layouts.admin')

@section('titulo', 'Administrar repositorios')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/certificado/administrar.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/certificado') }}">Certificados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Administrar</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Administrar respositorios
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
                        <table id="t_repositorios" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th title="ARCHIVOS">CERT.</th>
                                    <th title="RESPONSABLES">RESP.</th>                                    
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5">Cargando...</td>
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
                    <label class="form-label">Nombre</label>
                    <input id="nombre" type="text" class="form-control mayuscula" placeholder="" maxlength="190">
                </div>
                <div class="form-group form-required">
                    <label class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control mayuscula" rows="3"></textarea>
                </div>                       
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>

<!-- MODAL RESPONSABLES -->
<div id="responsable" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_responsable" class="modal-title">Responsables</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input id="respositorio_users" type="text" class="form-control mb-3" readonly>
                <label class="form-label">Asignados</label>
                <div id="responsables_list" class="list-group list-group-hoverable">
                    <div class="list-group-item bg-white">Cargando...</div>
                </div> 
                <div id="cargando_responsables" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
                    </div>
                </div>                 
            </div>  
            <div id="form_responsable" class="modal-body">
                <div class="form-group form-required">
                    <label class="form-label">Usuario</label>
                    <select id="user_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
                </div>  
            </div>            
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_asignar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>

@endsection