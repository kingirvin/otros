@extends('layouts.admin')
@section('titulo', 'Firmar documento digital')

@section('css')
<link href="{{ asset('css/firmar_avanzado.css?v='.config('app.version')) }}" rel="stylesheet">
@endsection

@section('js')
<script>
    const factor_mult = 2.5;
    const elArchivo = {{$primero->id}};
    const firma_hojas = {!! $primero->informacion !!};  
    const firma_dimenciones = @json($firma_dimenciones);    
    const elZip = '{{$zip_nombre}}';

    var datos_firma = {
        archivo_id: 0,
        num_pagina: 0,
        motivo: 'Doy fé',
        exacto: 1,
        pos_pagina: '0-0',
        apariencia: 0
    };

</script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/clientclickonce.js"></script>
<script src="{{ asset('js/certificado/firmar.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/certificado') }}">Certificados</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/certificado/publicar') }}">Publicar</a></li>                    
                        <li class="breadcrumb-item active" aria-current="page">Firmar</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Firmar documentos digital
                </h2>
            </div>            
        </div>
    </div>
</div>
@php
    $informacion = json_decode($primero->informacion, true);   
@endphp
<div class="page-body">
    <div class="container-lg">
        <div class="row ">
            <div class="col-md-8">
                <div class="card overflow-auto mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                        &nbsp;{{$primero->nombre}}
                        </h3>
                    </div>
                    <div id="body_hoja" class="card-body p-0 overflow-auto text-center" style="background: #57565a;">
                        <div id="fx_contenedor">
                            <div id="fx_hoja">                                
                                <div id="fx_firma"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Firma</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <div class="form-label">Ubicación de la firma</div>
                            <div class="alert alert-info alert-important" role="alert">                                
                                <div>Se muestra la primera página del primer documento seleccionado, la firma se aplicará en la posición indicada en todos los documentos seleccionados.</div>
                            </div>
                        </div>                        
                        <div class="form-group mb-3">
                            <div class="form-label">Motivo de la firma</div>
                            <select id="motivo" class="form-select">
                                <option value="Soy el autor del documento">Soy el autor del documento</option>
                                <option value="En señal de conformidad">En señal de conformidad</option>
                                <option value="Doy V° B°">Doy V° B°</option>
                                <option value="Por encargo">Por encargo</option>
                                <option value="Doy fé" selected>Doy fé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-label">Apariencia de la firma</div>
                            <select id="apariencia" class="form-select">
                                <option value="0">Sello + Descripción Horizontal</option>
                                <option value="1">Sello + Descripción Vertical</option>
                                <option value="2">Solo sello</option>
                                <option value="3">Solo descripción</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <a href="{{ url('admin/certificado/publicar') }}" class="btn btn-link flex-fill">Cancelar</a>
                    <div style="width: 10px"></div>
                    <button id="enviar" class="btn btn-primary flex-fill" onclick="enviar();" disabled>
                        <!-- Download SVG icon from http://tabler-icons.io/i/writing-sign -->
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19c3.333 -2 5 -4 5 -6c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 2.877 2.5 4c1.5 2 2.5 2.5 3.5 1c.667 -1 1.167 -1.833 1.5 -2.5c1 2.333 2.333 3.5 4 3.5h2.5" /><path d="M20 17v-12c0 -1.121 -.879 -2 -2 -2s-2 .879 -2 2v12l2 2l2 -2z" /><path d="M16 7h4" /></svg>
                        Firmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="argumentos" value="" />
<div id="addComponent"></div>
@endsection