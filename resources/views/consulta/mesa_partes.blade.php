@extends('layouts.blanco')

@section('titulo', 'UNAMAD - Mesa de Partes Virtual')

@section('contenido')
<div class="container py-4">
    <div class="row">
        <div class="col-12 text-center mb-3">        
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">
            </a>           
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1>Mesa de Partes Virtual</h1>
        </div>
    </div>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-4 px-3">
            <p class="text-justify">
                La Universidad Nacional Amazónica de Madre de Dios en el marco del proceso de Transformación Digital en las Entidades de la Administración Pública, pone a disposicion de sus estudiantes y público en general una Mesa de Partes Virtual para el trámite de sus documentos de manera digital.
            </p>
            <p class="text-justify">
                La Mesa de Partes Virtual se encuentra habilitado las 24 horas del día, pero el <b>horario de atención de los documentos es de LUN-VIE 09:00am a 03:00pm</b>, los documentos presentados después de dicho horario serán recepcionados con fecha del día hábil siguiente.
            </p>
            <p class="text-justify">
               Si presentaste un documento a trámite de <b>manera presencial</b> puedes consultar el estado de tu trámite a traves del siguiente <a href="{{ url('consultas/tramites') }}" target="_blank">ENLACE</a>, para lo cual necesitaras el <b>Codigo Único de Trámite</b> y la <b>fecha en que fue presentado</b> tu trámite.
            </p>
        </div>
        <div class="col-md-4 p-3">
            <div class="mb-3">
                <a href="{{ url('register') }}" class="card card-link card-link-pop">
                    <div class="card-status-start bg-pink"></div>
                    <div class="card-body">                        
                        <div class="row align-items-center">
                            <div class="col-auto">
                              <span class="avatar bg-primary-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 11h6m-3 -3v6"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">     
                                <h3 class="card-title mb-0">REGISTRARSE</h3>                           
                                <div class="text-muted">
                                    Si eres usuario nuevo
                                </div>
                            </div>
                        </div>                        
                    </div>
                </a>
            </div>
            <div class="mb-4">
                <a href="{{ url('login') }}" class="card card-link card-link-pop">
                    <div class="card-status-start bg-pink"></div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-success-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 11l2 2l4 -4"></path>
                                     </svg>
                                </span>
                            </div>
                            <div class="col">     
                                <h3 class="card-title mb-0">INGRESAR</h3>                           
                                <div class="text-muted">
                                    Si ya posees una cuenta de usuario
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="text-center">
                <a href="{{ asset('pdf/manual_mesa_de_partes.pdf') }}" class="d-inline-block" download>
                    <div class="d-flex">
                        <div class="pe-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                <line x1="9" y1="9" x2="10" y2="9"></line>
                                <line x1="9" y1="13" x2="15" y2="13"></line>
                                <line x1="9" y1="17" x2="15" y2="17"></line>
                            </svg>
                        </div>
                        <div>
                            Descargar Manual de Usuario
                        </div>
                    </div>
                </a>
            </div>
        </div>
   </div>
</div>
@endsection