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
    <link rel="manifest" href="/manifest.json">
    <link href="{{ asset('lib/tabler/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-vendors.min.css') }}" rel="stylesheet"/>    
    <link href="{{ asset('css/admin.css?v='.config('app.version')) }}" rel="stylesheet"/>
    
</head>
<body class="h-100" style="overflow: auto;">
   <div class="h-100 d-flex">        
        <div class="flex-grow-1 h-100 d-flex flex-column justify-content-center">
            
            <div class="px-5">
                <div class="mb-3">
                    <img src="{{ asset('img/logo_horizontal.png') }}" alt="" height="60px">
                </div>
                <h1>{{ config('app.name', 'Titulo') }}</h1>
                <p class="text-justify">
                    La Universidad Nacional Amazónica de Madre de Dios en el marco del proceso de Transformación Digital en las Entidades de la Administración Pública impulsado por la Presidencia del Consejo de Ministros (PCM) a través de la Secretaría de Gobierno y Transformación Digital (SEGDI), implementa el Sistema de Gestión Documental acorde a los estándares y buenas prácticas establecidos en Modelo de Gestión Documental (MGD) aprobado mediante Resolución de Secretaría de Gobierno Digital N° 001-2017-PCM/SEGDI.
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
                    <a href="{{ url('register') }}" class="btn btn-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>
                        Registrarse
                    </a>                                
                    @endif
                </div>       
            </div>

        </div>
        <div class="w-40 flex-shrink-0 h-100 d-none d-md-block" style="background-color: #303030; border-left: 3px solid #ff4187;">
           <div class="d-table h-100 w-100 p-4 text-center" style="background: url({{ asset('img/background.jpg') }}) no-repeat center center fixed; background-size: cover;">
                <div class="d-table-cell" style="vertical-align: middle">
                    <h2 class="text-white mb-4">VENTANILLA VIRTUAL</h2>
                    <img src="{{ asset('img/bienvenido2.png') }}" style="width: 60%">
                    <div class="mb-4"></div>
                    <p class="" style="color: rgb(255 255 255 / 75%);">Registrate para acceder a las siguientes opciones:</p>
                    <div class="d-inline-block text-start" style="color: rgb(255 255 255 / 75%);">                        
                        <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                            Presenta documentos para trámite
                        </div>
                        <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="15" cy="15" r="4" /><path d="M18.5 18.5l2.5 2.5" /><path d="M4 6h16" /><path d="M4 12h4" /><path d="M4 18h4" /></svg>
                            Consulta el estado de tu trámite
                        </div>
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="6" height="6" rx="1" /><line x1="7" y1="17" x2="7" y2="17.01" /><rect x="14" y="4" width="6" height="6" rx="1" /><line x1="7" y1="7" x2="7" y2="7.01" /><rect x="4" y="14" width="6" height="6" rx="1" /><line x1="17" y1="7" x2="17" y2="7.01" /><line x1="14" y1="14" x2="17" y2="14" /><line x1="20" y1="14" x2="20" y2="14.01" /><line x1="14" y1="14" x2="14" y2="17" /><line x1="14" y1="20" x2="17" y2="20" /><line x1="17" y1="17" x2="20" y2="17" /><line x1="20" y1="17" x2="20" y2="20" /></svg>
                            Valida la firma digital de un documento
                        </div>
                    </div>
                </div>
                
            </div>            
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