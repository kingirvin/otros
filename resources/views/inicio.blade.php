<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Titulo') }}</title>
    <link href="{{ asset('lib/tabler/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-vendors.min.css') }}" rel="stylesheet"/>    
    <link href="{{ asset('css/admin.css?v='.config('app.version')) }}" rel="stylesheet"/>
</head>
<body class="h-100">
    <div class="h-100 d-flex flex-column">
        <div class="flex-fill">
            <div class="h-100 d-flex flex-column justify-content-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 order-md-1 order-2 py-3">
                            <div class="mb-3">
                                <img src="{{ asset('img/logo_horizontal.png') }}" alt="" height="60px">
                            </div>
                            <h1>{{ config('app.name', 'Titulo') }}</h1>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                            </p>

                            <div class="btn-list">
                                @auth  
                                <a href="{{ url('admin') }}" class="btn btn-pink">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M20 12h-13l3 -3m0 6l-3 -3" /></svg>
                                    Ingresar
                                </a>
                                @else
                                <a href="{{ url('login') }}" class="btn btn-pink">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M20 12h-13l3 -3m0 6l-3 -3" /></svg>
                                    Ingresar
                                </a>
                                <a href="{{ url('register') }}" class="btn btn-white disabled ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>
                                    Registrarse
                                </a>                                
                                @endif
                            </div>                          
                        </div>
                        <div class="col-md-6 order-md-2 order-1 text-center text-md-end py-3">
                            <img src="{{ asset('img/bienvenido.png') }}" alt="" style="width: 65%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-3 px-3 pb-2">
            <!--<div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div>
                        <ul class="list-inline">
                            <li class="list-inline-item  mb-2">
                                <a href="#">                                    
	                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="15" cy="15" r="4" /><path d="M18.5 18.5l2.5 2.5" /><path d="M4 6h16" /><path d="M4 12h4" /><path d="M4 18h4" /></svg>
                                    Consultar trámite
                                </a>
                            </li>
                            <li class="list-inline-item">
                            </li>
                            <li class="list-inline-item  mb-2">
                                <a href="#">                                    
	                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="6" height="6" rx="1" /><line x1="7" y1="17" x2="7" y2="17.01" /><rect x="14" y="4" width="6" height="6" rx="1" /><line x1="7" y1="7" x2="7" y2="7.01" /><rect x="4" y="14" width="6" height="6" rx="1" /><line x1="17" y1="7" x2="17" y2="7.01" /><line x1="14" y1="14" x2="17" y2="14" /><line x1="20" y1="14" x2="20" y2="14.01" /><line x1="14" y1="14" x2="14" y2="17" /><line x1="14" y1="20" x2="17" y2="20" /><line x1="17" y1="17" x2="20" y2="17" /><line x1="20" y1="17" x2="20" y2="20" /></svg>
                                    Validar firma digital
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                    <div>
                        <ul class="list-inline">
                            <li class="list-inline-item mb-2"><a href="#">But they're displayed inline.</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        -->

           <!-- <div>
                <div class="dropdown">
                    <a class="btn btn-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    ¿Necesitas ayuda?
                    </a>                                  
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="{{ url('restablecer') }}">Restablecer contraseña</a></li>
                        <li><a class="dropdown-item" href="{{ url('verificar') }}">Reenviar correo de verificación</a></li>
                        <li><a class="dropdown-item" href="#">Contactar con soporte técnico</a></li>
                    </ul>
                </div>
            </div>-->
        </div>
    </div>

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
</body>
</html>