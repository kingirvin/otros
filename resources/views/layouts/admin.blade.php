<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Titulo') }} - @yield('titulo')</title>
    <link href="{{ asset('lib/tabler/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-vendors.min.css') }}" rel="stylesheet"/>    
    <link href="{{ asset('css/admin.css?v='.config('app.version')) }}" rel="stylesheet"/>    
    @yield('css')
</head>
<body>
    @php 
      $user = Auth::user();
      $rol = $user->rol;
      $modulos =  request('modulos', array());//Obtenemos los modulos de los middleware modulo y submodulo
    @endphp
    <div class="wrapper">
        <!--HEADER SUPERIOR-->
        <header class="navbar navbar-expand-md navbar-dark bg-pink d-print-none">
          <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".menu-navegacion">
              <span class="navbar-toggler-icon"></span>
            </button>
            <!--LOGO-->
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">     
              <a href="{{ url('admin') }}">          
                <img src="{{ asset('img/logo_horizontal_alt.png') }}" height="36" alt="Tabler" class="navbar-brand-image">   
              </a>              
            </h1>
            <!--MENU DERECHA-->
            <div class="navbar-nav flex-row order-md-last"> 
              <!--
              <div class="nav-item dropdown d-none d-md-flex me-3">
                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
                  <span class="badge bg-red"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-card">
                  <div class="card">
                    <div class="card-body">
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad amet consectetur exercitationem fugiat in ipsa ipsum, natus odio quidem quod repudiandae sapiente. Amet debitis et magni maxime necessitatibus ullam.
                    </div>
                  </div>
                </div>
              </div>
              -->
              <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                  <span class="avatar avatar-sm">{{ $user->siglas }}</span>
                  <div class="d-none d-xl-block ps-2">
                    <div>{{ $user->nombre.' '.$user->apaterno }}</div>
                    <div class="mt-1 small text-muted">{{ $rol ? $rol->nombre : 'PÚBLICO' }}</div>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <a href="{{ url('admin/perfil') }}" class="dropdown-item">Perfil</a>              
                  <div class="dropdown-divider"></div>
                  <a href="{{ url('logout') }}" class="dropdown-item"                          
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" >
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 6a7.75 7.75 0 1 0 10 0" /><line x1="12" y1="4" x2="12" y2="12" /></svg>
                    Salir
                  </a>
                  <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </div>
              </div>
            </div>
            <!--MODULO ACTIVO-->
            @if(!empty($modulos))
            <div class="collapse navbar-collapse menu-navegacion">
              <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                  <!-- Administración de sistemas -->
                  @if(request()->is('admin/sistema*'))
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('admin/sistema') }}">
                      <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><circle cx="12" cy="12" r="3" /></svg>
                      </span>
                      <span class="nav-link-title">
                        Administración de Sistema
                      </span>
                    </a>
                  </li> 
                  @endif

                  <!-- Trámite documentario -->
                  @if(request()->is('admin/tramite*'))
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('admin/tramite') }}">
                      <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 3v4a1 1 0 0 0 1 1h4" /><path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" /><path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>
                      </span>
                      <span class="nav-link-title">
                        Trámite documentario
                      </span>
                    </a>
                  </li> 
                  @endif

                  <!-- ... -->

                </ul>
              </div>
            </div>
            @endif
          </div>
        </header>
        <!--HEADER SECUNDARIO-->
        @if(!empty($modulos))
        <div class="navbar-expand-md">
          <div class="collapse navbar-collapse menu-navegacion" id="navbar-menu">
            <div class="navbar navbar-light">
              <div class="container-xl">
                <!-- SUBMODULOS -->
                <ul class="navbar-nav"> 
                  <!-- Administración de sistemas -->
                  @if(request()->is('admin/sistema*') && array_key_exists('SISTEMA', $modulos))
                    
                    @if(in_array('ACCESOS', $modulos['SISTEMA']))
                    <li class="nav-item dropdown {{ (request()->is('admin/sistema/accesos*')) ? 'active' : '' }}">
                      <a class="nav-link dropdown-toggle" href="#sistema" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-5a4 4 0 0 1 8 0" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Accesos
                        </span>
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item {{ (request()->is('admin/sistema/accesos/roles*')) ? 'active' : '' }}" href="{{ url('admin/sistema/accesos/roles') }}">
                          Roles y privilegios
                        </a>                        
                        <a class="dropdown-item {{ (request()->is('admin/sistema/accesos/usuarios')) ? 'active' : '' }}" href="{{ url('admin/sistema/accesos/usuarios') }}">
                          Usuarios de sistema
                        </a>                        
                      </div>
                    </li>
                    @endif

                    @if(in_array('MANTENIMIENTO', $modulos['SISTEMA']))
                    <li class="nav-item dropdown {{ (request()->is('admin/sistema/mantenimiento*')) ? 'active' : '' }}">
                      <a class="nav-link dropdown-toggle" href="#sistema" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Mantenimiento
                        </span>
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item {{ (request()->is('admin/sistema/mantenimiento/sedes')) ? 'active' : '' }}" href="{{ url('admin/sistema/mantenimiento/sedes') }}">
                          Sedes
                        </a> 
                        <a class="dropdown-item {{ (request()->is('admin/sistema/mantenimiento/dependencias')) ? 'active' : '' }}" href="{{ url('admin/sistema/mantenimiento/dependencias') }}">
                          Dependencias
                        </a>                 
                      </div>
                    </li>
                    @endif

                    @if(in_array('PERSONA', $modulos['SISTEMA']))
                    <li class="nav-item dropdown {{ (request()->is('admin/sistema/persona*')) ? 'active' : '' }}">
                      <a class="nav-link dropdown-toggle" href="#sistema" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Personas
                        </span>
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item {{ (request()->is('admin/sistema/persona/datos')) ? 'active' : '' }}" href="{{ url('admin/sistema/persona/datos') }}">
                          Datos personales
                        </a> 
                        <a class="dropdown-item {{ (request()->is('admin/sistema/persona/empleados')) ? 'active' : '' }}" href="{{ url('admin/sistema/persona/empleados') }}">
                          Empleados
                        </a>          
                      </div>
                    </li>
                    @endif

                    @if(in_array('DOCADM', $modulos['SISTEMA']))
                    <li class="nav-item dropdown {{ (request()->is('admin/sistema/documental*')) ? 'active' : '' }}">
                      <a class="nav-link dropdown-toggle" href="#sistema" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 3v4a1 1 0 0 0 1 1h4" /><path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" /><path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Gestión documental
                        </span>
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item {{ (request()->is('admin/sistema/documental/tipos')) ? 'active' : '' }}" href="{{ url('admin/sistema/documental/tipos') }}">
                          Documentos de gestión
                        </a> 
                        <a class="dropdown-item {{ (request()->is('admin/sistema/documental/procedimientos*')) ? 'active' : '' }}" href="{{ url('admin/sistema/documental/procedimientos') }}">
                          Procedimientos administrativos
                        </a>          
                      </div>
                    </li>
                    @endif

                  @endif  

                  <!-- Gestión de documentos -->
                  @if(request()->is('admin/tramite*') && array_key_exists('TRAMITE', $modulos))

                    @if(in_array('RECPDOC', $modulos['TRAMITE']))
                    <li class="nav-item {{ (request()->is('admin/tramite/recepcion')) ? 'active' : '' }}">
                      <a class="nav-link nav-link-custom" href="{{ url('admin/tramite/recepcion') }}">
                        <button class="btn btn-outline-success w-100">                         
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><polyline points="7 11 12 16 17 11" /><line x1="12" y1="4" x2="12" y2="16" /></svg>
                          Recibir documento                          
                        </button>
                      </a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/tramite/recepcion/*')) ? 'active' : '' }}">
                      <a class="nav-link" href="{{ url('admin/tramite/recepcion/recibidos') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><path d="M4 13h3l3 3h4l3 -3h3" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Documentos recibidos
                        </span>
                      </a>
                    </li>
                    @endif                 

                    @if(in_array('ENVDOC', $modulos['TRAMITE']))
                    <li class="nav-item dropdown {{ (request()->is('admin/tramite/emision*')) ? 'active' : '' }}">
                      <a class="nav-link dropdown-toggle" href="#sistema" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="10" y1="14" x2="21" y2="3" /><path d="M21 3l-6.5 18a0.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a0.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Emisión de documentos
                        </span>
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-success {{ (request()->is('admin/tramite/emision')) ? 'active' : '' }}" href="{{ url('admin/tramite/emision') }}">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><line x1="9" y1="14" x2="15" y2="14" /></svg>
                          Nuevo trámite
                        </a>
                        <a class="dropdown-item {{ (request()->is('admin/tramite/emision/emitidos')) ? 'active' : '' }}" href="{{ url('admin/tramite/emision/emitidos') }}">
                          Documentos emitidos
                        </a>                                      
                      </div>
                    </li>
                    @endif   

                    @if(in_array('ARCHIVOS', $modulos['TRAMITE']))
                    <li class="nav-item {{ (request()->is('admin/tramite/archivos*')) ? 'active' : '' }}">
                      <a class="nav-link" href="{{ url('admin/tramite/archivos') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" /><circle cx="16.5" cy="17.5" r="2.5" /><line x1="18.5" y1="19.5" x2="21" y2="22" /></svg>
                        </span>
                        <span class="nav-link-title">
                          Archivos digitales
                        </span>
                      </a>
                    </li>
                    @endif

                    <!-- ... -->

                  @endif 
                  <!-- ... -->

                </ul>                
              </div>
            </div>
          </div>
        </div>
        @endif
        <!--CONTENIDO-->
        <div class="page-wrapper position-relative">
            @yield('contenido')
            <!--FOOTER-->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                  <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-lg-auto ms-lg-auto">
                      <ul class="list-inline list-inline-dots mb-0">                        
                        <li class="list-inline-item">
                          <a href="{{ url('info/versiones') }}" class="link-secondary" rel="noopener" target="_blank">
                            Versión {{ config('app.version') }}
                          </a>
                        </li>
                        <li class="list-inline-item">
                          <a href="https://www.linkedin.com/in/jos%C3%A9-cortijo-bellido-49a513b5" target="_blank" class="link-secondary" rel="noopener">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="7 8 3 12 7 16" /><polyline points="17 8 21 12 17 16" /><line x1="14" y1="4" x2="10" y2="20" /></svg>
                            Desarrollo
                          </a>
                        </li>
                      </ul>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                      <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                          UNAMAD &copy; 2022
                        </li>
                        <li class="list-inline-item">                          
                          Oficina de Tecnológias de la Información
                        </li>                        
                      </ul>
                    </div>
                  </div>
                </div>
            </footer>

            <div id="cargando_pagina" class="cargando">
              <div class="text-center pt-4">
                <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
              </div>
            </div>
        </div>
    </div>    

    <div id="mensaje_container"></div>
    <!-- MODAL -->
    @yield('modal')
    <!-- Tabler Core -->
    <script src="{{ asset('lib/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('lib/tabler/js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/utilitarios.js?v='.config('app.version')) }}"></script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });       
    </script>
    @yield('js')  
</body>
</html>