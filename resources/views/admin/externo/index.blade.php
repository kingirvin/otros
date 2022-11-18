@extends('layouts.admin')
@section('titulo', 'Ventanilla virtual')
@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center mw-100">
            <div class="col">
                <div>
                    <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ventanilla</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Ventanilla virtual
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">        
        <div class="row row-cards">
            <div class="col-md-6">                
                <p class="text-muted text-justify">Bienvenido al módulo de <b>Ventanilla Virtual</b>, en este módulo podras presentar tus documentos a trámite, el cual ser atendido por la Unidad de Trámite Documentario de la Universidad Nacional Amazónica de Madre de Dios.</p>
                <p class="text-muted text-justify">Desde este módulo solo podras hacer seguimiento a los documentos que presentaste de manera virtual, si presentaste un documento a trámite de <b>manera presencial</b> puedes consulta el estado de tu trámite a traves del siguiente <a href="{{ url('consultas/tramites') }}" target="_blank">ENLACE</a>, para lo cual necesitaras el <b>Codigo Único de Trámite</b> y la <b>fecha en que fue presentado</b> tu trámite.</p>
                <p class="text-muted text-justify">Si tu documento no esta siendo atendido en su debido plazo comunicate con la Unidad de Trámite Documentario al correo <a href="mailto:tramite-documentario@unamad.edu.pe">tramite-documentario@unamad.edu.pe</a></p>
                <p class="text-muted text-justify">Si presentas dificultades en el manejo de la Ventanilla Virtual o detectas algún error en la plataforma comunicate con la Oficina de Tecnologías de la Información al correo <a href="mailto:oti@unamad.edu.pe">oti@unamad.edu.pe</a>.</p>
            </div>                                    
        </div>
    </div>
</div>
@endsection