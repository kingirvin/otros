@extends('layouts.admin')
@section('titulo', 'Asignaciones')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script>
    const elMovimiento = {{$movimiento->id}};
</script>
<script src="{{ asset('js/tramite/asignaciones.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Asignaciones</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Asignaciones
                </h2>
            </div>  
                  
        </div>
    </div>
    <!-- Page detail -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-auto">                                                    
                <span class="bg-info-lt avatar avatar-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><line x1="9" y1="9" x2="10" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line><line x1="9" y1="17" x2="15" y2="17"></line></svg>
                </span>                                   
            </div>
            <div class="col">                
                <h2 class="page-title">
                    {{$movimiento->documento->documento_tipo->abreviatura}} N° {{$movimiento->documento->numero}}
                </h2>
                <div class="page-subtitle">
                    <div class="row">
                        <div class="col-auto">
                            <div class="text-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 6h9" /><path d="M11 12h9" /><path d="M12 18h8" /><path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" /><path d="M6 10v-6l-2 2" /></svg>
                                {{$movimiento->d_year.'-'.str_pad($movimiento->d_numero, 5, '0', STR_PAD_LEFT);}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-purple">
                                T-{{$movimiento->tramite->codigo}}
                            </div>
                        </div>
                        <div class="col-auto">                        
                            <div class="text-reset">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><rect x="8" y="15" width="2" height="2" /></svg>
                                {{$movimiento->d_fecha->format('d/m/Y H:i')}}h
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <a href="javascript:void(0);" onclick="nuevo();" class="btn btn-success d-sm-inline-block" >
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Agregar
                    </a>
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
                    <div class="card-header">
                        <h3 class="card-title">Personal asignado</h3>   
                    </div>              
                    <div id="t_principal">
                        <table id="t_asignaciones" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>PERSONAL</th>
                                    <th></th>
                                    <th></th>
                                    <th>ACCIÓN</th>                                    
                                    <th>ESTADO</th>
                                    <th>REGISTRA</th>
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
            <div id="form_editar" class="modal-body">  
                <div class="form-group mb-3">
                    <label class="form-label">Dependencia</label>
                    <input type="text" class="form-control" value="{{$movimiento->d_dependencia->nombre}}" readonly>
                </div> 
                <div class="form-group form-required mb-3">
                    <label class="form-label">Personal</label>
                    <select id="empleado_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->persona->nombre.' '.$empleado->persona->apaterno.' '.$empleado->persona->amaterno }}</option>
                        @endforeach                       
                    </select>
                </div>                
                <div class="form-group form-required mb-3">
                    <label class="form-label">Acción</label>
                    <select id="accion_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($acciones as $accion)
                        <option value="{{ $accion->id }}">{{ $accion->nombre }}</option>
                        @endforeach 
                    </select>
                </div> 
                <div class="form-group form-required">
                    <label class="form-label">Detalles</label>
                    <textarea id="detalles" class="form-control mayuscula" rows="2"></textarea>
                </div>
            </div>   
            <div class="modal-footer">                
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection
