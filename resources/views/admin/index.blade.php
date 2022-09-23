@extends('layouts.admin')

@section('titulo', 'Inicio')

@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    BIENVENIDO
                </div>
                <h2 class="page-title">
                    {{ config('app.name', 'Titulo') }}
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!--ADMINISTRACIÓN DE SISTEMA-->
            @if(isset($modulos['SISTEMA']))
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-pink-lt avatar"> 
	                                <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><circle cx="12" cy="12" r="3" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <h4 class="mb-0">ADMINISTRACIÓN DE SISTEMA</h4>
                                <div class="text-muted">
                                    Administra información y acceso en los diferentes módulos.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('admin/sistema') }}" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>
            @endif   
            <!--TRÁMITE DOCUMENTARIO-->
            @if(isset($modulos['TRAMITE']))
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-pink-lt avatar">
	                                <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 3v4a1 1 0 0 0 1 1h4" /><path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" /><path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <h4 class="mb-0">TRÁMITE DOCUMENTARIO</h4>
                                <div class="text-muted">
                                    Gestiona el envío y recepción de documentos en la organización.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('admin/tramite') }}" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>
            @endif  

            <!--ACCESO EXTERNO-->
            @if(isset($modulos['EXTERNO']))
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-pink-lt avatar">
	                                <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="12" x2="6" y2="12" /><line x1="12" y1="3" x2="12" y2="6" /><line x1="7.8" y1="7.8" x2="5.6" y2="5.6" /><line x1="16.2" y1="7.8" x2="18.4" y2="5.6" /><line x1="7.8" y1="16.2" x2="5.6" y2="18.4" /><path d="M12 12l9 3l-4 2l-2 4l-3 -9" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <h4 class="mb-0">VENTANILLA VIRTUAL</h4>
                                <div class="text-muted">
                                    Ingresa documentos a trámite, realiza seguimiento y valida integridad.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('admin/externo') }}" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>
            @endif  

            <!--CERTIFICADOS  DE PARTICIPACION-->
            @if(isset($modulos['CERTIFICADO']))
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-pink-lt avatar">
	                                <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="15" cy="15" r="3" /><path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73" /><line x1="6" y1="9" x2="18" y2="9" /><line x1="6" y1="12" x2="9" y2="12" /><line x1="6" y1="15" x2="8" y2="15" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <h4 class="mb-0">CERTIFICADOS</h4>
                                <div class="text-muted">
                                    Administración y publicación de certificados de participación.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('admin/certificado') }}" class="btn btn-success">Ingresar</a>
                    </div>
                </div>
            </div>
            @endif  
        </div>
    </div>
</div>
@endsection