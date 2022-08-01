var icono_texto = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg> INICIO';
var carpeta_seleccion = null;
var archivo_seleccion = null;
var carpetas = [];
var archivos = [];


$( document ).ready(function() {

    $("#ubicacion_select").on('change', function() {
        if($(this).val()=="d"){
            $("#origen_dependencia").show();
        } else {
            $("#origen_dependencia").hide();
        }
        ir(0);
    });

    $("#dependencia_archivo_select").on('change', function() {       
        ir(0);
    });

    $("#estado_select").on('change', function() {       
        navegar();
    });
    
});


/**
 * BUSCAR ARCHIVO
 */

 function buscar_archivo() {
    ir(0);
    $("#buscar_modal").modal("show");
}

function ir(idsel = 0) {
    limpiar_filtro();
    carpeta_seleccion  = { id: idsel };        
    navegar();       
}

function navegar() {
    $("#loading_buscar").show();    
    $.ajax({
        type: "GET",
        url: default_server+"/json/archivos/todos",
        data: { 
            ubicacion: $("#ubicacion_select").val(),
            dependencia_id: ($("#ubicacion_select").val() == "d" ? $("#dependencia_archivo_select").val() : "0"),
            carpeta_id: (carpeta_seleccion != null ? carpeta_seleccion.id : 0),
            firmado: $("#estado_select").val(),
            texto: $("#texto_select").val()
        },
        success: function(result){  
            carpetas = result.carpetas;
            archivos = result.archivos;
            carpeta_seleccion = result.seleccionado;
            render(); 
            $("#loading_buscar").hide();
        },
        error: function(error) {     
            alerta(response_helper(error), false);
            $("#loading_buscar").hide();
        }
    });    
}

function limpiar_filtro() {
    $("#estado_select").val(0);
    $("#texto_select").val(""); 
}

function filtrar_documento() {
    navegar();    
}

function render() {
    //Rederizamos la barra de navegacion 
    $("#carpetas_buscar").html("");
    if(carpeta_seleccion == null){
        $("#carpetas_buscar").append('<li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">'+icono_texto+'</a></li>');
    }
    else 
    {
        $("#carpetas_buscar").append('<li class="breadcrumb-item"><a href="javascript:void(0);" onclick="ir(0);">'+icono_texto+'</a></li>');
        var padres = carpeta_seleccion.ruta;
        if(padres != null){
            for (let i = 0; i < padres.length; i++) {
                $("#carpetas_buscar").append('<li class="breadcrumb-item"><a href="javascript:void(0);" onclick="ir('+padres[i].id+')">'+padres[i].nombre+'</a></li>');   
            }
        }
        $("#carpetas_buscar").append('<li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">'+carpeta_seleccion.nombre+'</a></li>');        
    }

    //Renderizamos la tabla
    $("#tabla_buscar").html('');    
    var contenido_tabla = '';

    //carpetas
    for (let i = 0; i < carpetas.length; i++) {
        contenido_tabla += 
        '<tr>'+
            '<td>'+
                '<div class="d-flex align-items-center">'+
                    '<span class="avatar bg-yellow-lt me-2">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>'+
                    '</span>'+
                    '<div class="flex-fill">'+
                        '<div class="font-weight-medium lh-1">'+
                            '<a href="javascript:void(0);" onclick="javascript:ir('+carpetas[i].id+')" title="'+carpetas[i].nombre+'">'+textoMax(carpetas[i].nombre,90)+'</a>'+
                        '<div class="text-muted">'+carpetas[i].archivos_count+' archivos &#183; '+carpetas[i].subcarpetas_count+' carpetas</div>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td class="text-muted w-1">'+dis_fecha(carpetas[i].created_at)+'<small class="d-block">'+dis_solo_hora(carpetas[i].created_at)+' h</small></td>'+
            '<td class="w-1"></td>'+
            '<td class="w-1">'+
                
            '</td>'+
        '</tr>';
    }

    //archivos
    for (let i = 0; i < archivos.length; i++) {
        contenido_tabla += 
        '<tr>'+
            '<td>'+
                '<div class="d-flex align-items-center">'+
                    get_icono_archivo(archivos[i])+
                    '<div class="flex-fill">'+
                    '<div class="font-weight-medium lh-1"><a href="'+url_time(default_server+(archivos[i].formato == 'pdf' ? '/admin/archivos/stream/':'/admin/archivos/download/')+archivos[i].codigo)+'" target="_blank">'+archivos[i].nombre+'</a></div>'+
                    '<div class="text-muted">'+archivos[i].formato.toUpperCase()+' &#183; '+archivos[i].format_size+get_compartidos(archivos[i])+get_historicos(archivos[i])+get_documentos(archivos[i])+'</div>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td class="text-muted w-1">'+dis_fecha(archivos[i].created_at)+'<small class="d-block">'+dis_solo_hora(archivos[i].created_at)+' h</small></td>'+
            '<td class="w-1">'+
                get_estado(archivos[i])+
            '</td>'+
            '<td class="text-end w-1">'+                
                '<button class="btn btn-icon btn-success" onclick="seleccionar_documento('+archivos[i].id+');">'+     
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>'+                          
                '</button>'+
            '</td>'+
        '</tr>';
    }

    if(contenido_tabla != '')
        $("#tabla_buscar").html(contenido_tabla);
    else
        $("#tabla_buscar").html('<tr><td colspan="4" class="text-muted">Esta carpeta esta vacia</td></tr>');
}

function seleccionar_documento(ida) {
    var elemento = elementId(ida, archivos);
    if(elemento != null){
        if(elemento.formato == 'pdf'){

            $("#buscar_modal").modal("hide");
            archivo_seleccion = elemento;
            $("#archivo_seleccionado").html(
            '<div class="d-flex align-items-center">'+
                get_icono_archivo(archivo_seleccion)+
                '<div class="flex-fill">'+
                    '<div class="font-weight-medium lh-1">'+
                        '<a href="'+url_time(default_server+(archivo_seleccion.formato == 'pdf' ? '/admin/archivos/stream/':'/admin/archivos/download/')+archivo_seleccion.codigo)+'" target="_blank">'+archivo_seleccion.nombre+'</a>'+
                    '</div>'+
                    '<div class="d-flex mt-1">'+
                        get_estado(archivo_seleccion)+
                        '<div class="text-muted ms-2">'+archivo_seleccion.formato.toUpperCase()+' &#183; '+archivo_seleccion.format_size+'</div>'+
                    '</div>'+
    '                '+
                '</div>'+
                '<div class="px-2 flex-shrink-0">'+
                    '<a href="javascript:void(0);" onclick="eliminar_seleccion();" class="text-danger">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                    '</a>'+
                '</div>'+
            '</div>'
            );
        }
        else {
            alerta("Seleccione un archivo en formato PDF",false);
        }
    } else {
        alerta("No se encontro el archivo",false);
    }
}

function eliminar_seleccion() {
    if(confirm("Esta seguro que desea remover el archivo?"))
    {        
        archivo_seleccion = null;
        $("#archivo_seleccionado").html('');
    }
}

function get_icono_archivo(item) {
    if(item.formato == 'pdf')
        var icono_f = '<svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>';
    else
        var icono_f = '<svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>';

    var color_f = '';
    if(item.formato == 'pdf') {
        if(item.estado > 0) {
            color_f = 'bg-blue-lt';
        }
    }

    return '<span class="avatar '+color_f+' flex-shrink-0 me-2" title="POR: '+item.user.nombre+' '+item.user.apaterno+' '+item.user.amaterno+'">'+icono_f+'</span>';
}

function get_compartidos(item) {
    if(item.compartidos_count > 0)
    {
        return ' &#183; <span class="text-yellow" title="COMPARTIDO CON USUARIOS">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg> '+
            ceros(item.compartidos_count,2) +
            '</span>';
    }
    else
        return '';
}

function get_historicos(item) {
    if(item.historicos_count > 0)
    {
        return ' &#183; <span class="text-cyan" title="ARCHIVOS HISTORICOS">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 8 12 12 14 14" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg> '+
            ceros(item.historicos_count,2) +
            '</span>';
    }
    else
        return '';
}

function get_documentos(item) {
    if(item.documentos_count > 0)
    {
        return ' &#183; <span class="text-lime" title="DOCUMENTOS ASOCIADOS">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12h-1v5h1" /><path d="M14 12h1v5h-1" /><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg> '+
            ceros(item.documentos_count,2) +
            '</span>';
    }
    else
        return '';
}

function get_estado(item) {
    if(item.estado == 2)
        return '<span class="badge bg-blue-lt">FIRMADO</span>';
    else if(item.estado == 1)
        return '<span class="badge bg-purple-lt">PARA FIRMA</span>';
    else
        return '<span class="badge bg-yellow-lt">SIMPLE</span>';
}