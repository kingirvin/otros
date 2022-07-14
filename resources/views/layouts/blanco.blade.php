<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    
    <title>{{ config('app.name', 'Titulo') }} - @yield('titulo')</title>

    <link href="{{ asset('lib/tabler/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler/css/tabler-vendors.min.css') }}" rel="stylesheet"/> 
    <link href="{{ asset('css/admin.css?v='.config('app.version')) }}" rel="stylesheet"/>    
    @yield('css') 
</head>
<body class="antialiased border-top-wide border-pink d-flex flex-column">
    <div class="page page-center" style="margin-top: -2px;">
        @yield('contenido')
    </div> 
    <div id="cargando_pagina" class="cargando">
        <div class="text-center pt-4">
          <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
        </div>
    </div>  
    <!-- Tabler Core --> 
    <script src="{{ asset('lib/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('lib/tabler/js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/utilitarios.js?v='.config('app.version')) }}"></script>
    @yield('js')   
</body>
</html>