@extends('layouts.admin')
@section('titulo', 'Privilegios')
@section('js')
<script>
    const elRol = {{$rol->id}};
</script>
<script src="{{ asset('js/sistema/privilegios.js') }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/sistema/accesos/roles') }}">Roles</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Privilegios</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Privilegios de rol
                </h2>
            </div>             
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Rol</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" readonly value="{{ $rol->nombre }}">
                        </div> 
                        <div class="">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" rows="3" readonly>{{ $rol->descripcion }}</textarea>
                        </div>   
                    </div>
                </div>                
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Modulos</h3>
                    </div>
                    <div id="lista_modulos" class="list-group list-group-flush ">
                        @foreach($modulos as $modulo)
                        <div class="list-group-header">{{$modulo->titulo}}</div>     
                            @foreach($modulo->submodulos as $submodulo)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto"><input id="{{$submodulo->id}}" type="checkbox" class="form-check-input" {{ $submodulo->encontrado ? 'checked' : '' }} ></div>
                                    <div class="col text-truncate">
                                        <div class="text-body lh-1">{{$submodulo->titulo}}</div>
                                        <small class="d-block text-muted text-truncate lh-1 mt-1">{{$submodulo->descripcion}}</small>
                                    </div>
                                </div>
                            </div>                            
                            @endforeach   
                        @endforeach                        
                    </div>
                </div>   
                <div class="text-end">
                    <a href="{{ url('admin/sistema/accesos/roles') }}" class="btn btn-link">Cancelar</a>
                    <button class="btn btn-primary" onclick="guardar();">
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><circle cx="12" cy="14" r="2" /><polyline points="14 4 14 8 8 8 8 4" /></svg>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection