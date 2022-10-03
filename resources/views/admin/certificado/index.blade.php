@extends('layouts.admin')
@section('titulo', 'Certificados de participación')
@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center mw-100">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Certificados</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Certificados de participación
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
                        <h3 class="card-title">Herramientas para firma digital</h3>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item">
                            <div class="row align-items-center">                          
                                <div class="col-auto">
                                    <span class="avatar bg-red-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                                    </span>                                    
                                </div>
                                <div class="col text-truncate">
                                    <a href="https://drive.google.com/file/d/1U_Jxiu_f4btvgkosoQenlJCPwqb7e5f6/view?usp=sharing" class="text-body d-block" target="_blank">Java 8 JRE 32 bits</a>                                
                                </div>
                            </div>                            
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">                          
                                <div class="col-auto">
                                    <span class="avatar bg-green-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                                    </span>	
                                </div>
                                <div class="col text-truncate">
                                    <a href="https://dsp.reniec.gob.pe/refirma_suite/pcx/web/main.jsf" class="text-body d-block" target="_blank">Software ReFirma PCX</a>                                
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">                          
                                <div class="col-auto">
                                    <span class="avatar bg-yellow-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                                    </span>	
                                </div>
                                <div class="col text-truncate">
                                    <a href="https://drive.google.com/file/d/19WNtuSkA-iSvxDy2Xa_83QyqPPWnICbS/view?usp=sharing" class="text-body d-block" target="_blank">Middleware Token Bit4id</a>                                
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">                          
                                <div class="col-auto">
                                    <span class="avatar bg-blue-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                                    </span>	
                                </div>
                                <div class="col text-truncate">
                                    <a href="https://serviciosportal.reniec.gob.pe/static/portal/RENIEC-DNIe.exe" class="text-body d-block" target="_blank">Driver DNIe</a>                                
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">                          
                                <div class="col-auto">
                                    <span class="avatar bg-azure-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                                    </span>	
                                </div>
                                <div class="col text-truncate">
                                    <a href="https://chrome.google.com/webstore/detail/meta4-clickonce-launcher/jkncabbipkgbconhaajbapbhokpbgkdc?hl=es" class="text-body d-block" target="_blank">Meta4 ClickOnce Launcher</a>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Repositorios asignados</h3>
                    </div>
                    @if(count($repositorios) > 0)
                    <div class="list-group list-group-flush list-group-hoverable">
                    @foreach ($repositorios as $asignado)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">                                
                                <span class="avatar bg-blue-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><ellipse cx="12" cy="6" rx="8" ry="3"></ellipse><path d="M4 6v6a8 3 0 0 0 16 0v-6" /><path d="M4 12v6a8 3 0 0 0 16 0v-6" /></svg>
                                </span>                                
                            </div>
                            <div class="col text-truncate">
                                <div class="text-body">{{$asignado->repositorio->nombre}}</div>
                                <small class="d-block text-muted text-truncate mt-n1">{{$asignado->repositorio->descripcion}}</small>
                            </div>                           
                        </div>
                    </div>
                    @endforeach
                    </div>
                    @else
                    <div class="card-body">
                        <div class="alert alert-important" role="alert">
                            No tienes asignado un repositorio, ponte en contacto con el administrador del sistema.
                        </div>
                    </div>
                    @endif 
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Material de ayuda</h3>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable">                               
                                <!--<div class="list-group-item">
                                    <div class="row align-items-center">                          
                                        <div class="col-auto">
                                            <span class="avatar bg-teal-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><line x1="3" y1="6" x2="3" y2="19" /><line x1="12" y1="6" x2="12" y2="19" /><line x1="21" y1="6" x2="21" y2="19" /></svg>
                                            </span>                                    
                                        </div>
                                        <div class="col text-truncate">
                                            <a href="{{ asset('pdf/manual_usuario.pdf') }}" class="text-body d-block" target="_blank">Manual de usuario</a>                                
                                        </div>
                                    </div>                            
                                </div>-->
                                <div class="list-group-item">
                                    <div class="row align-items-center">                          
                                        <div class="col-auto">
                                            <span class="avatar bg-red-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="4" /><path d="M10 9l5 3l-5 3z" /></svg>
                                            </span>                                    
                                        </div>
                                        <div class="col text-truncate">
                                            <a href="https://youtu.be/mWt1JLvI8Q8" class="text-body d-block" target="_blank">Video tutorial</a>                                
                                        </div>
                                    </div>                            
                                </div>                                                              
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>                                   
        </div>
    </div>
</div>
@endsection