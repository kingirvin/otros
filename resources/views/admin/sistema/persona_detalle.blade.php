@extends('layouts.admin')
@section('titulo', 'Detalle de datos personales')
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/sistema/persona/datos') }}">Datos personales</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalles</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Detalle de datos personales
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-status-top bg-secondary"></div>
                    <div class="card-header">
                        <h3 class="card-title">Datos personales</h3>
                    </div>
                    <div class="card-body">
                        <dl>      
                            <dt>Tipo de persona</dt>  
                            @if($persona->tipo == 0)
                            <dd class="text-blue">NATURAL</dd>                   
                            @else  
                            <dd class="text-purple">JURÍDICA</dd>    
                            @endif
                        </dl>
                        @foreach ($persona->invitados as $invitado)
                        <dl>
                            <dt>RUC</dt>  
                            <dd>{{$invitado->ruc}}</dd>  
                            <dt>Razon social</dt>  
                            <dd>{{$invitado->razon_social}}</dd> 
                            @if($invitado->dependencia)
                            <dt>Dependencia</dt>
                            <dd>{{$invitado->dependencia}}</dd>
                            @endif
                            @if($invitado->cargo)
                            <dt>Cargo</dt>
                            <dd>{{$invitado->cargo}}</dd>
                            @endif
                            @if($invitado->correo)
                            <dt>Correo</dt>
                            <dd>{{$invitado->correo}}</dd>
                            @endif
                            @if($invitado->telefono)
                            <dt>Teléfono</dt>
                            <dd>{{$invitado->telefono}}</dd>
                            @endif
                            @if($invitado->direccion)
                            <dt>Dirección</dt>
                            <dd>{{$invitado->direccion}}</dd>
                            @endif
                        </dl>  
                        @endforeach
                        <dl>
                            <dt>Tipo de documento</dt>
                            <dd>{{$persona->identidad_documento->nombre}}</dd>                        
                            <dt>Número de documento</dt>
                            <dd>{{$persona->nro_documento}}</dd>                        
                            <dt>Nombre</dt>
                            <dd>{{$persona->nombre}}</dd>
                            <dt>Primer apellido</dt>
                            <dd>{{$persona->apaterno}}</dd>
                            <dt>Segundo apellido</dt>
                            <dd>{{$persona->amaterno}}</dd>
                            @if($persona->correo)
                            <dt>Correo</dt>
                            <dd>{{$persona->correo}}</dd>
                            @endif
                            @if($persona->telefono)
                            <dt>Teléfono</dt>
                            <dd>{{$persona->telefono}}</dd>
                            @endif
                            @if($persona->nacimiento)
                            <dt>Fecha de nacimiento</dt>
                            <dd>{{$persona->nacimiento->format('d/m/Y')}}</dd>
                            @endif
                            <dt>Registro</dt>
                            @if($persona->registro == 1)
                            <dd class="text-green">INTERNO</dd>                   
                            @else  
                            <dd class="text-yellow">EXTERNO</dd>    
                            @endif     
                        </dl>
                    </div>
                </div>

                @foreach ($persona->users as $user)
                <div class="card mb-3">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header">
                        <h3 class="card-title">Datos de usuario</h3>
                    </div>
                    <div class="card-body">
                        <dl>
                            @if($user->codigo)
                            <dt>Código</dt>
                            <dd>{{$persona->codigo}}</dd>
                            @endif
                            <dt>Tipo de usuario</dt>
                            @if($user->tipo == 1)
                            <dd class="text-green">INTERNO</dd>                   
                            @else  
                            <dd class="text-yellow">EXTERNO</dd>    
                            @endif     
                            <dt>Rol</dt>
                            <dd>{{$user->rol->nombre}}</dd>                        
                            <dt>Email</dt>
                            <dd>{{$user->email}}</dd>
                            <dt>Creado</dt>
                            <dd>{{$user->created_at->format('d/m/Y')}}</dd>
                            <dt>Estado</dt>            
                            @if($user->estado == 1)
                            <dd><span class="badge bg-green">ACIVO</span></dd>                   
                            @else  
                            <dd><span class="badge bg-red">DESHABILITADO</span></dd>    
                            @endif     
                        </dl>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-6">  
                @foreach ($persona->empleos as $empleo)
                <div class="card mb-3">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-header">
                        <h3 class="card-title">Datos de empleado</h3>
                    </div>
                    <div class="card-body">
                        <dl>                            
                            <dt>Dependencia</dt>
                            <dd>{{$empleo->dependencia->nombre}}</dd>
                            <dt>Cargo</dt>
                            <dd>{{$empleo->cargo}}</dd>     
                            @if($empleo->fecha_inicio)
                            <dt>Fecha de inicio</dt>
                            <dd>{{$empleo->fecha_inicio->format('d/m/Y')}}</dd>
                            @endif
                            @if($empleo->fecha_termino)
                            <dt>Fecha de término</dt>
                            <dd>{{$empleo->fecha_termino->format('d/m/Y')}}</dd>
                            @endif
                            <dt>Estado</dt>            
                            @if($empleo->estado == 1)
                            <dd><span class="badge bg-green">ACIVO</span></dd>                   
                            @else  
                            <dd><span class="badge bg-red">FINALIZADO</span></dd>    
                            @endif     
                        </dl>
                    </div>
                </div>
                @endforeach

                @foreach ($persona->estudiantes as $estudiante)
                <div class="card mb-3">
                    <div class="card-status-top bg-warning"></div>
                    <div class="card-header">
                        <h3 class="card-title">Datos de estudiante</h3>
                    </div>
                    <div class="card-body">
                        <dl>                            
                            <dt>Código</dt>
                            <dd>{{$estudiante->codigo}}</dt>
                            <dt>Facultad</dt>
                            <dd>{{$estudiante->facultad}}</dd>     
                            <dt>Condicion</dt>
                            <dd>{{$estudiante->condicion}}</dd>                           
                            <dt>Correo</dt>
                            <dd>{{$estudiante->correo}}</dd>
                            <dt>Estado</dt>            
                            @if($empleo->estado == 1)
                            <dd><span class="badge bg-green">ACIVO</span></dd>                   
                            @else  
                            <dd><span class="badge bg-red">DESHABILITADO</span></dd>    
                            @endif     
                        </dl>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection