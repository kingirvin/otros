@extends('layouts.admin')
@section('titulo', 'Documentos emitidos')
@section('js')
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/datatables/DataTables-1.11.5/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script>
    const elUser = {{$user->id}};
</script>
<script src="{{ asset('js/tramite/documentos_emitidos.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Emitidos</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Documentos emitidos
                </h2>
            </div> 
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <a href="{{url('admin/tramite/emision')}}" class="btn btn-success d-sm-inline-block" >
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Agregar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $modulos =  request('modulos', array());
    $jefe = false;
    if(array_key_exists('TRAMITE', $modulos)){
        if(in_array('GESTDOC', $modulos['TRAMITE'])){
            $jefe = true;
        }
    }
@endphp
<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-2 select_label_container" style="padding-bottom: .5rem;">
                                    <div class="select_label_min">AÑO DE REGISTRO</div>
                                    <select id="year_select" class="form-select">
                                        @for ($i = 0; $i < 10; $i++)
                                        <option value="{{$ahora->year - $i}}">{{$ahora->year - $i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 select_label_container" style="padding-bottom: .5rem;">
                                    <div class="select_label_min">DEPENDENCIA DE ORIGEN</div>
                                    <select id="dependencia_select" class="form-select">
                                    @if(count($origenes) > 0)
                                        @foreach ($origenes as $origen)
                                        <option value="{{ $origen->dependencia_id }}" {{$origen->dependencia_id == $origen_actual ? 'selected' : ''}}>{{ $origen->dependencia->nombre }}</option>
                                        @endforeach
                                    @else
                                        <option value="0">NO TIENES ASIGNADO UNA DEPENDENCIA</option>
                                    @endif
                                    </select>
                                </div>   
                                <div class="col-md-3 select_label_container" style="padding-bottom: .5rem;">
                                    <div class="select_label_min">REMITENTE</div>
                                    <select id="persona_select" class="form-select">
                                    @if($jefe)
                                        <option value="0">TODOS</option>
                                        @foreach ($empleados as $empleado)
                                        <option value="{{ $empleado->persona_id }}">{{ $empleado->persona->nombre.' '.$empleado->persona->apaterno.' '.$empleado->persona->amaterno }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $empleado_actual->persona_id }}">{{ $empleado_actual->persona->nombre.' '.$empleado_actual->persona->apaterno.' '.$empleado_actual->persona->amaterno }}
                                    @endif
                                    </select>
                                </div>   
                                <!--
                                <div class="col-md-6 text-end" style="padding-bottom: .5rem;">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="17" cy="17" r="4" /><path d="M17 13v4h4" /><path d="M12 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-6.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v2m0 3v4" /></svg>
                                            Reportes
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                -->                             
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_emitidos" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>NÚMERO</th>
                                    <th>TRÁMITE</th>
                                    <th>DOCUMENTO</th>
                                    <th></th>
                                    <th>REMITENTE</th>
                                    <th>DESTINO</th>
                                    <th>REGISTRA</th>
                                    <th>FECHA</th>
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
<!-- MODAL MODIFICAR DOCUMENTO -->
<div id="modificar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo-carpeta" class="modal-title">Modificar documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body">  
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label">Tipo de documento</label>
                        <select id="documento_tipo_id" class="form-select validar_select">
                            <option value="0">Seleccione...</option>                                    
                            @foreach ($documento_tipos as $documento_tipo)
                            <option value="{{ $documento_tipo->id }}">{{ $documento_tipo->nombre }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label">N° de documento</label>
                        <input id="numero" type="text" class="form-control mayuscula" placeholder="ejem. 001-2021-GOREMAD/GR">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group form-required mb-3">
                        <label class="form-label">Remitente</label>
                        <input id="remitente" type="text" class="form-control mayuscula" placeholder="">
                    </div>
                    <div class="col-md-4 form-group form-required mb-3">
                        <label class="form-label">Folios</label>
                        <input id="folios" type="text" class="form-control validar_entero" placeholder="">
                    </div>
                </div>
                <div class="form-group form-required mb-3">
                    <label class="form-label">Asunto</label>
                    <textarea id="asunto" class="form-control mayuscula" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <textarea id="observaciones" class="form-control mayuscula" rows="3"></textarea>
                </div>                      
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_modificar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endsection
