@extends('layouts.admin')

@section('titulo', 'Perfil de usuario')

@section('js')
<script src="{{ asset('lib/tabler/libs/litepicker/dist/litepicker.js') }}" type="text/javascript"></script>
<script>
    var elUser = {!! $user !!};
</script>
<script src="{{ asset('js/perfil.js') }}" type="text/javascript"></script>
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
                    <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                    </ol>
                </div>
                <h2 class="page-title">Perfil de usuario</h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-3">
                    <div class="card-body p-4 text-center">
                        <span class="avatar avatar-xl mb-3 avatar-rounded">{{ $user->siglas }}</span>
                        <h3 class="m-0 mb-1">{{ $user->nombre.' '.$user->apaterno }}</h3>
                        <div class="mt-3">
                            @if($user->rol)
                            <span class="badge bg-green-lt">{{ $user->rol->nombre }}</span>
                            @else
                            <span class="badge bg-purple-lt">PÚBLICO</span>
                            @endif
                        </div>
                    </div>                    
                </div>
                <div class="card mb-3">
                    <ul class="list-group list-group-flush">
                        <a href="javascript:void(0);" onclick="modificar_password();" class="list-group-item list-group-item-action"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                            Cambiar contraseña
                        </a>
                        @if($user->rol_id == null)
                        <a href="javascript:void(0);" onclick="modificar();" class="list-group-item list-group-item-action"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Actualizar datos
                        </a>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-9">                
                <div class="card">
                    <ul class="nav nav-tabs" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tabs-home-9" class="nav-link active" data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            Datos personales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-profile-9" class="nav-link" data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                Datos de usuario
                            </a>
                        </li>
                    </ul>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="tabs-home-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tipo de documento</label>
                                            @if($user->tipo_documento == 1)
                                            <input type="text" class="form-control" value="DNI" readonly>
                                            @elseif($user->tipo_documento == 2)
                                            <input type="text" class="form-control" value="CARNET DE EXTRANJERIA" readonly>
                                            @else
                                            <input type="text" class="form-control" value="NO DEFINIDO" readonly>
                                            @endif
                                        </div>    
                                        <div class="mb-3">
                                            <label class="form-label">Número de documento</label>
                                            <input type="text" class="form-control" value="{{ $user->nro_documento }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" class="form-control" value="{{ $user->nombre }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Apellido paterno</label>
                                            <input type="text" class="form-control" value="{{ $user->apaterno }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Apellido materno</label>
                                            <input type="text" class="form-control" value="{{ $user->amaterno }}" disabled>
                                        </div>

                                    </div> 
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Correo</label>
                                            <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" value="{{ $user->telefono }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" class="form-control" value="{{ $user->direccion }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha de nacimiento</label>
                                            <input type="text" class="form-control" value="{{ $user->nacimiento ? $user->nacimiento->format('d/m/Y') : '' }}" disabled>
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-profile-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Rol</label>
                                            <input type="text" class="form-control" value="{{ $user->rol ? $user->rol->nombre : 'PÚBLICO' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Fecha de registro</label>
                                            <input type="text" class="form-control" value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                @if($user->rol_id != null)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>MÓDULO</th>
                                            <th>SUBMODULO</th>
                                            <th class="w-1">ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($privilegios as $privilegio)                                       
                                        <tr>
                                            <td>{{ $privilegio->submodulo->modulo->titulo }}</td>
                                            <td>{{ $privilegio->submodulo->titulo }}</td>                                            
                                            <td class="text-center">
                                                <span class="text-success">                                                        
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                </span>
                                            </td>
                                        </tr>                                          
                                    @endforeach                                            
                                    </tbody>
                                </table>    
                                @else
                                <div class="alert alert-important" role="alert">
                                    Usuario sin acceso a módulos
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal')
<!-- MODAL CAMBIAR PASSWORD -->
<div id="renovar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_renovar" class="modal-body pb-1">
                <div class="form-group form-required mb-3">
                    <label class="form-label">
                        Contraseña anterior   
                    </label>
                    <input id="p_password_old" type="password" placeholder="" maxlength="191" class="form-control">
                </div>
                <div class="row">
                    <div class="form-group form-required col-md-6 mb-3">
                        <label class="form-label">
                            Nueva Contraseña
                        </label>
                        <input id="p_password" type="password" placeholder="" maxlength="191" class="form-control validar_minimo:8">
                    </div>
                    <div class="form-group form-required col-md-6 mb-3">
                        <label class="form-label">
                            Confirmar Contraseña
                        </label>
                        <input id="p_password_confirmation" type="password" placeholder="" maxlength="191" class="form-control validar_igual:p_password">
                    </div>
                </div> 
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary ms-auto" onclick="guardar_password()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@if($user->rol_id == null)
<!-- MODAL MODIFICAR -->
<div id="modificar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar datos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_modificar" class="modal-body">               
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="m_tipo_documento">Tipo de documento</label>
                        <select id="m_tipo_documento" class="form-select validar_select" autofocus>
                            <option value="0">Seleccione...</option>
                            <option value="1">DNI - Documento Nacional de Identidad</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="m_nro_documento">Número de documento</label>
                        <input type="text" id="m_nro_documento" name="m_nro_documento" class="form-control validar_numero validar_minimo:8" placeholder="">
                    </div>
                </div>      
                <div class="row">            
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="m_nombre">Nombres</label>
                        <input type="text" id="m_nombre" class="form-control mayuscula" placeholder="">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label" for="m_nacimiento">Fecha de nacimiento</label>
                        <input type="text" id="m_nacimiento" class="form-control validar_fecha" placeholder="">
                    </div>
                </div>        
                <div class="row">
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="m_apaterno">Primer Apellido</label>
                        <input type="text" id="m_apaterno" class="form-control mayuscula" placeholder="">
                    </div>
                    <div class="col-md-6 form-group form-required mb-3">
                        <label class="form-label" for="m_amaterno">Segundo Apellido</label>
                        <input type="text" id="m_amaterno" class="form-control mayuscula" placeholder="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Correo</label>
                        <input id="m_email" type="email" class="form-control" readonly>
                    </div> 
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label" for="m_telefono">Teléfono</label>
                        <input type="text" id="m_telefono" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="m_direccion">Dirección</label>
                    <input type="text" id="m_direccion" class="form-control mayuscula" placeholder="">
                </div>                              
            </div>   
            <div class="modal-footer">                
                <button type="button" class="btn btn-link link-secondary ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_modificar()">Guardar</button>
            </div>       
        </div>
    </div>
</div>
@endif
@endsection