@extends('layouts.admin')
@section('titulo', 'Ingresar documento')
@section('js')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="{{ asset('js/externo/ingresar.js?v='.config('app.version')) }}" type="text/javascript"></script>
<script>
    @if(!session('correcto') && !session('error') && !$errors->any())
        $(document).ready(function() {
            $("#informacion").modal("show");
        });
    @endif
</script>
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
                        <li class="breadcrumb-item"><a href="{{ url('admin/externo') }}">Ventanilla</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ingresar</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Ingresa tu documento a trámite
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <form action="{{ url('admin/externo/tramite') }}" method="POST" enctype="multipart/form-data" onsubmit="return guardar_todo(this);"> 
        @csrf
        <div class="container-xl">
            <div class="row">
                <div class="col-12">                    
                    @if(session('correcto'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        Tu trámite se ha registrado exitosamente, dirígete a la <a href="{{url('admin/externo')}}" class="alert-link">Ventanilla virtual</a> para realizar el seguimiento.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">                                      
                        {{ session('error') }}               
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                    @endif   

                    @if($errors->any())
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <ul class="m-0 ps-0" style="list-style: none;">
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row row-cards">  
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Datos del documento</h3>
                        </div>
                        <div class="card-body">
                            <div id="form_documento">
                                <div class="form-group form-required mb-3">
                                    <div class="form-label">Archivo digital</div>
                                    <input id="archivo_subir" name="archivo_subir" type="file" class="form-control" accept=".pdf,.PDF">
                                    <small class="form-hint">Seleccione el documento principal del trámite, debe estar en formato PDF.</small>
                                </div>                        
                                <div class="row">
                                    <div class="col-md-6 form-required form-group mb-3">
                                        <label class="form-label">Tipo de documento</label>
                                        <select id="documento_tipo_id" name="documento_tipo_id" class="form-select validar_select">
                                            <option value="0">Seleccione...</option>   
                                            @foreach ($documento_tipos as $documento_tipo)
                                            <option value="{{ $documento_tipo->id }}">{{ $documento_tipo->nombre }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                    <div class="col-md-6 form-required form-group mb-3">
                                        <label class="form-label">N° de documento</label>
                                        <input id="numero" name="numero" type="text" class="form-control mayuscula validar_maximo:190" placeholder="ejem. 001-2021-UNAMAD-R" maxlength="190">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-required form-group mb-3">
                                        <label class="form-label">Remitente</label>
                                        <input id="remitente" name="remitente" type="text" class="form-control mayuscula validar_maximo:190" placeholder="" maxlength="190">
                                    </div>
                                    <div class="col-md-4 form-required form-group mb-3">
                                        <label class="form-label">Folios</label>
                                        <input id="folios" name="folios" type="text" class="form-control validar_entero" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group form-required mb-3">
                                    <label class="form-label">Asunto</label>
                                    <textarea id="asunto" name="asunto" class="form-control mayuscula" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-group form-required">
                                <label class="form-label">Validación</label>
                                <div class="g-recaptcha" data-callback="capcha_filled"
                                        data-expired-callback="capcha_expired" data-sitekey="{{ config('app.recaptcha_public') }}"></div>   
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">Anexos</h4>                        
                        </div>                   
                        <div class="card-body">
                            <div class="">
                                <input id="archivo_anexos" name="archivo_anexos[]" type="file" class="form-control" multiple="multiple">
                                <small class="form-hint">Puede seleccionar multiples archivos que no excedan el tamaño máximo.</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>                        
                                <input id="anexos_url" name="anexos_url" type="text" class="form-control" placeholder="Enlace para descarga de anexos">
                                <small class="form-hint">Puede agregar un enlace de descarga de WeTransfer, Google Drive, One Drive u otros.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">Datos de trámite</h4>                        
                        </div>                   
                        <div class="card-body">
                            <div class="form-group mb-3">                               
                                <label class="form-label">Procedimiento administrativo</label>
                                <select id="procedimiento_id" name="procedimiento_id" class="form-select">
                                    <option value="0">Seleccione...</option>
                                    @foreach ($procedimientos as $procedimiento)
                                    <option value="{{ $procedimiento->id }}">{{ $procedimiento->titulo }}</option>
                                    @endforeach
                                </select>                               
                            </div>
                            <div class="form-group mb-3">                               
                                <label class="form-label">Destinatario</label>
                                <input type="text" class="form-control" value="{{ ($mesa_partes != null ? $mesa_partes->nombre.' - '.$mesa_partes->sede->nombre : 'NO DEFINIDO' ) }}" maxlength="190" readonly>                          
                            </div>
                            <div id="form_legalidad">
                                <div class="form-group form-required">
                                    <div class="form-label">Legalidad</div>
                                    <ul class="list-group bg-light">
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <span><a href="{{ url('info/declaracion') }}" target="_blank">Declaro</a> que los datos consignados responden a la verdad.</span>
                                            <span>
                                                <label class="form-check form-check-single form-switch ps-3">
                                                    <input class="form-check-input" type="checkbox">
                                                </label>
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <span>Acepto los <a href="{{ url('info/terminos') }}" target="_blank">términos y condiciones</a> del servicio.</span>
                                            <span>
                                                <label class="form-check form-check-single form-switch ps-3">
                                                    <input class="form-check-input" type="checkbox">
                                                </label>
                                            </span>
                                        </li>
                                    </ul>                                                                                             
                                </div> 
                            </div>
                        </div>   
                    </div>                
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ url('admin/externo') }}" class="btn btn-link link-secondary w-100">
                                Cancelar
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><circle cx="12" cy="14" r="2" /><polyline points="14 4 14 8 8 8 8 4" /></svg>
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>                                       
            </div>
        </div>
    </form>  
</div>
@endsection


@section('modal')
<!-- MODAL INFORMACION -->
<div id="informacion" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo-editar">Consideraciones a tener en cuenta</h5>            
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <ul class="m-0" style="font-size: 15px;">
                    <li>El <b>tamaño máximo</b> por archivo es de 15 MB.</li>
                    <li>Si el tamaño de los <b>archivos anexos</b> excede el tamaño máximo, utilice algun servicio de almacenamiento en la nube y agregue el enlace para descarga de los anexos.</li>
                    <li>Los campos con el signo [<span class="text-danger">*</span>] son de carácter <b>obligatorio</b>.</li>
                    <li>Solo estan habilitados los <b>procedimientos administrativos</b> que permiten el envío de documentos de manera no presencial.</li>
                </ul>           
            </div>
            <div class="modal-footer">
                <button class="btn" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection