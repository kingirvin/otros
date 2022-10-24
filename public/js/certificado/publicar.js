var carpeta_seleccion = null;
var carpetas = [];
var archivos = [];
var icono_texto = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 15h-6a1 1 0 0 1 -1 -1v-8a1 1 0 0 1 1 -1h6" /><rect x="13" y="4" width="8" height="16" rx="1" /><line x1="7" y1="19" x2="10" y2="19" /><line x1="17" y1="8" x2="17" y2="8.01" /><circle cx="17" cy="16" r="1" /><line x1="9" y1="15" x2="9" y2="19" /></svg> Mi repositorio';
var carpeta_accion = 0;//0:nuevo, n:editar
var archivo_accion = 0;//0:nuevo, n:editar

$( document ).ready(function() {

    $('#editar_carpeta').on('shown.bs.modal', function (event) {
        $("#nombre_carpeta").focus();
    });
    
    $("#repositorio_select").on('change', function() {
        ir(0);
    });
    
    //dropdown en table responsive
    $('.table-responsive').on('show.bs.dropdown', function () {
        if((carpetas.length + archivos.length) <= 2) {
            $('.table-responsive').css( "overflow-x", "inherit" );
        }
    });
   
    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "overflow-x", "auto" );
    });
  
    navegar();
});


/**
 * -- NAVEGACIÓN
 */
function ir(idsel = 0) {
    limpiar_filtro();
    carpeta_seleccion  = { id: idsel };        
    navegar();       
}

function navegar() {
    $("#cargando_pagina").show();    
    $.ajax({
        type: "GET",
        url: default_server+"/json/repositorios/archivos/todos",
        data: { 
            cert_repositorio_id: $("#repositorio_select").val(),
            cert_carpeta_id: (carpeta_seleccion != null ? carpeta_seleccion.id : 0),
            texto: $("#texto_select").val()
        },
        success: function(result){  
            carpetas = result.carpetas;
            archivos = result.archivos;
            carpeta_seleccion = result.seleccionado;
            render();
            $("#cargando_pagina").hide();    
        },
        error: function(error) {     
            alerta(response_helper(error), false);
            $("#cargando_pagina").hide();
        }
    });    
}

function limpiar_filtro() {
    $("#texto_select").val(""); 
}

function buscar() {    
    navegar();    
}

function render() {
    //Rederizamos la barra de navegacion 
    $("#navegacion").html("");
    if(carpeta_seleccion == null){
        $("#navegacion").append('<li class="breadcrumb-item active" aria-current="page"><a href="#">'+icono_texto+'</a></li>');
    }
    else 
    {
        $("#navegacion").append('<li class="breadcrumb-item"><a href="javascript:void(0);" onclick="ir(0);">'+icono_texto+'</a></li>');
        var padres = carpeta_seleccion.ruta;
        if(padres != null){
            for (let i = 0; i < padres.length; i++) {
                $("#navegacion").append('<li class="breadcrumb-item"><a href="javascript:void(0);" onclick="ir('+padres[i].id+')">'+padres[i].nombre+'</a></li>');   
            }
        }
        $("#navegacion").append('<li class="breadcrumb-item active" aria-current="page"><a href="#">'+carpeta_seleccion.nombre+'</a></li>');        
    }

    //Renderizamos la tabla
    $("#tabla_file").html('');    
    var contenido_tabla = '';

    //carpetas
    for (let i = 0; i < carpetas.length; i++) {
        contenido_tabla += 
        '<tr>'+
            '<td>'+
            '</td>'+
            '<td>'+
                '<div class="d-flex align-items-center">'+
                    '<span class="avatar bg-yellow-lt me-2" title="POR: '+carpetas[i].user.nombre+' '+carpetas[i].user.apaterno+' '+carpetas[i].user.amaterno+'">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>'+
                    '</span>'+
                    '<div class="flex-fill">'+
                        '<div class="font-weight-medium lh-1">'+
                            '<a href="javascript:void(0);" onclick="javascript:ir('+carpetas[i].id+')" title="'+carpetas[i].nombre+'">'+textoMax(carpetas[i].nombre,90)+'</a>'+
                        '<div class="text-muted">'+carpetas[i].archivos_count+' archivos &#183; '+carpetas[i].subcarpetas_count+' carpetas</div>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td></td>'+
            '<td class="text-muted w-1">'+dis_fecha(carpetas[i].created_at)+'<small class="d-block">'+dis_solo_hora(carpetas[i].created_at)+' h</small></td>'+
            '<td class="w-1"></td>'+
            '<td class="text-end w-1">'+
                '<div class="btn-list flex-nowrap">'+
                    '<span class="dropdown dropstart">'+
                        '<button class="btn btn-white btn-icon align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">'+                               
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 -.25rem 0 -.25rem"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="6" x2="20" y2="6" /><line x1="4" y1="12" x2="20" y2="12" /><line x1="4" y1="18" x2="20" y2="18" /></svg>'+
                        '</button>'+
                        '<div class="dropdown-menu">'+
                            '<a class="dropdown-item" href="javascript:void(0);" onclick="modificar_carpeta('+carpetas[i].id+')">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>'+
                                'Modificar'+
                            '</a>'+
                            '<a class="dropdown-item" href="javascript:void(0);" onclick="mover_carpeta('+carpetas[i].id+')">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 3l-4 4l4 4" /><path d="M3 7h11a3 3 0 0 1 3 3v11" /><path d="M13 17l4 4l4 -4" /></svg>'+
                                'Mover'+
                            '</a>'+                                   
                            '<a class="dropdown-item" href="javascript:void(0);" onclick="eliminar_carpeta('+carpetas[i].id+')">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                'Eliminar'+
                            '</a>'+
                        '</div>'+
                    '</span>'+
                '</div>'+
            '</td>'+
        '</tr>';
    }

    //archivos
    for (let i = 0; i < archivos.length; i++) {
        contenido_tabla += 
        '<tr>'+
            '<td>'+
                '<input class="form-check-input archivo_select my-0 ms-2 me-1" type="checkbox" data-id="'+archivos[i].id+'">'+
            '</td>'+
            '<td>'+
                '<div class="d-flex align-items-center">'+
                    get_icono_archivo(archivos[i])+
                    '<div class="flex-fill">'+
                    '<div class="font-weight-medium lh-1"><a href="'+url_time(default_server+'/admin/certificado/archivos/stream/'+archivos[i].codigo)+'" target="_blank">'+archivos[i].nombre+'</a></div>'+
                    '<div class="text-muted">'+(archivos[i].codigo != null ? archivos[i].codigo : 'UNDEFINED')+' &#183; '+archivos[i].formato.toUpperCase()+' &#183; '+archivos[i].format_size+'</div>'+
                    '</div>'+
                '</div>'+
            '</td>'+
            '<td title="'+safeText(archivos[i].descripcion)+'">'+textoMax(archivos[i].descripcion,25)+'</td>'+
            '<td class="text-muted w-1">'+dis_fecha(archivos[i].created_at)+'<small class="d-block">'+dis_solo_hora(archivos[i].created_at)+' h</small></td>'+
            '<td class="w-1">'+
                get_estado(archivos[i])+
            '</td>'+
            '<td class="text-end w-1">'+
                '<div class="btn-list flex-nowrap">'+
                    '<span class="dropdown dropstart">'+
                        '<button class="btn btn-white btn-icon align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">'+                               
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 -.25rem 0 -.25rem"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="6" x2="20" y2="6" /><line x1="4" y1="12" x2="20" y2="12" /><line x1="4" y1="18" x2="20" y2="18" /></svg>'+
                        '</button>'+
                        '<div class="dropdown-menu">'+
                            get_menu(archivos[i])+
                        '</div>'+
                    '</span>'+
                '</div>'+
            '</td>'+
        '</tr>';
    }

    if(contenido_tabla != '')
        $("#tabla_file").html(contenido_tabla);
    else
        $("#tabla_file").html('<tr><td colspan="4" class="text-muted">Esta carpeta esta vacia</td></tr>');
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

    return '<span class="avatar '+color_f+' me-2" title="POR: '+item.user.nombre+' '+item.user.apaterno+' '+item.user.amaterno+'">'+icono_f+'</span>';
}

function get_estado(item) {
    if(item.publico == 1) {
        return '<span class="badge bg-purple-lt">PUBLICADO</span>';
    } else {
        if(item.estado == 2){
            return '<span class="badge bg-blue-lt">FIRMADO</span>';       
        } else if (item.estado == 1) {
            return '<span class="badge bg-yellow-lt">PENDIENTE</span>';
        } else {
            return '<span class="badge">ERROR</span>';
        }
    }
}//0:inicial 1:incrustado 2:firmado

function get_menu(item) {
    var res = 
        '<a class="dropdown-item" href="javascript:void(0);" onclick="mover_archivo('+item.id+')">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 3l-4 4l4 4" /><path d="M3 7h11a3 3 0 0 1 3 3v11" /><path d="M13 17l4 4l4 -4" /></svg>'+
            'Mover'+
        '</a>'+
        '<a class="dropdown-item" href="javascript:void(0);" onclick="eliminar_archivo('+item.id+')">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
            'Eliminar'+
        '</a>'; 

    return res;
}


/**
 * -- CARPETAS
 */
function nueva_carpeta() {
    
    if($("#dependencia_select").val() == 0){
        alerta("El usuario no tiene asignado un repositorio");
        return;
    }

    carpeta_accion = 0;
    limpiar('#form_carpeta');
    vaciar('#form_carpeta');
    $("#titulo_editar_carpeta").html("Nueva carpeta");         
    $('#editar_carpeta').modal("show");           
   
}

function modificar_carpeta(idc) {
    carpeta_accion = idc;
    var elemento = elementId(idc, carpetas);
    if(elemento != null)
    {
        limpiar('#form_carpeta');
        $("#nombre_carpeta").val(elemento.nombre);
        $("#titulo_editar_carpeta").html("Modificar carpeta"); 
        $('#editar_carpeta').modal("show");
    }
    else
        alert("No se pudo encontrar el elemento");    
}

function guardar_carpeta() {
    if(validar('#form_carpeta'))
    {
        $("#editar_carpeta").modal("hide");
        $("#cargando_pagina").show();

        if(carpeta_accion == 0)//nuevo
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/repositorios/carpetas",
                data: {
                    cert_repositorio_id: $("#repositorio_select").val(),
                    cert_carpeta_id: (carpeta_seleccion != null ? carpeta_seleccion.id : 0),
                    nombre: $("#nombre_carpeta").val()
                },
                success: function(result){  
                    alerta(result.message, true);   
                    navegar(); 
                },
                error: function(error) {      
                    $("#cargando_pagina").hide();              
                    alerta(response_helper(error), false);
                }
            });  
        }
        else
        {
           $.ajax({
                type: "PUT",
                url: default_server+"/json/repositorios/carpetas/"+carpeta_accion,
                data: {                    
                    nombre: $("#nombre_carpeta").val()
                },
                success: function(result){  
                    alerta(result.message, true);   
                    navegar(); 
                },
                error: function(error) {      
                    $("#cargando_pagina").hide();              
                    alerta(response_helper(error), false);
                }
            });       
        }       
    }
}

function eliminar_carpeta(idc) {
    if(confirm("Esta seguro que desea eliminar?"))
    {
        $("#cargando_pagina").show();

        $.ajax({
            type: "DELETE",
            url: default_server+"/json/repositorios/carpetas/"+idc,
            data: { },
            success: function(result){  
                alerta(result.message, true);   
                navegar(); 
            },
            error: function(error) {      
                $("#cargando_pagina").hide();              
                alerta(response_helper(error), false);
            }
        }); 
    }
}

/**
 * -- NAVEGACION DE CARPETAS
 */

var carpeta_seleccion_mover = null;
var carpetas_mover = [];
var tipo_mover = 0;//0:carpeta, 1:archivo

function mover_carpeta(idc) {
    carpeta_accion = idc;//carpeta a mover   
    tipo_mover = 0;//carpeta
    ir_mover();
    $('#mover_carpeta').modal("show");
}
 
function ir_mover(idsel = 0) {
    carpeta_seleccion_mover  = { id: idsel };  
    navegar_mover();
}

function navegar_mover() {    
    $("#cargando_mover").show();
    $("#boton_guardar_mover").prop('disabled', true);

    $.ajax({
        type: "GET",
        url: default_server+"/json/repositorios/carpetas",
        data: {
            cert_repositorio_id: $("#repositorio_select").val(),
            cert_carpeta_id: (carpeta_seleccion_mover != null ? carpeta_seleccion_mover.id : 0),
        },         
        success: function(result){  
            carpeta_seleccion_mover = result.seleccionado;
            carpetas_mover = result.carpetas;  
            render_mover();
            $("#cargando_mover").hide();
            $("#boton_guardar_mover").prop('disabled', false);
        },
        error: function(error) {     
            alerta(response_helper(error), false);
            $("#cargando_mover").hide();
            $("#boton_guardar_mover").prop('disabled', false);
        }
    });    
}

function render_mover() {
    $("#lista_carpetas").html('');    
    var contenido_lista = '';
    //principal
    if(carpeta_seleccion_mover != null) {//carpeta seleccionada
        contenido_lista += 
        '<div class="list-group-item py-2" style="color: #626976; background: rgb(242, 243, 244);">'+
            '<div class="row align-items-center">'+
                '<div class="col-auto">'+
                    '<a href="javascript:void(0);" onclick="ir_mover('+(carpeta_seleccion_mover.carpeta_id ? carpeta_seleccion_mover.carpeta_id : 0)+')"; class="btn btn-white btn-icon">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="11" y2="18" /><line x1="5" y1="12" x2="11" y2="6" /></svg>'+
                    '</a>'+
                '</div>'+
                '<div class="col"> '+
                    '<div class="h3 mb-0">'+carpeta_seleccion_mover.nombre+'</div>'+
                '</div>'+
            '</div>'+
        '</div>';
    }
    else {//carpeta principal / raiz
        contenido_lista += 
        '<div class="list-group-item py-2" style="color: #626976; background: rgb(242, 243, 244);">'+
            '<div class="row align-items-center">'+                
                '<div class="col"> '+
                    '<div class="h3 mb-0">'+icono_texto+'</div>'+
                '</div>'+
            '</div>'+
        '</div>';
    }
    //carpetas
    if(carpetas_mover.length > 0){//tiene elementos
        for (let i = 0; i < carpetas_mover.length; i++) {
            contenido_lista +=
            '<a  href="javascript:void(0);" onclick="ir_mover('+carpetas_mover[i].id+')"; class="list-group-item list-group-item-action">'+
                '<div class="row align-items-center">'+
                    '<div class="col-auto">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>'+
                    '</div>'+
                    '<div class="col text-truncate">'+
                        carpetas_mover[i].nombre+
                    '</div>'+
                    '<div class="col-auto">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>'+
                    '</div>'+
                '</div>'+
            '</a>';
        }
    }
    else {//esta vacio
        contenido_lista += '<div class="list-group-item"><span class="text-muted">Esta carpeta esta vacia</span></div>'
    }

    $("#lista_carpetas").html(contenido_lista);
}

function guardar_mover() {
    if(confirm("Esta seguro que desea mover a esta ubicación?"))
    {
        $("#mover_carpeta").modal("hide");
        $("#cargando_pagina").show();

        if(tipo_mover == 0)//carpeta
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/repositorios/carpetas/mover",
                data: { 
                    cert_carpeta_id: carpeta_accion,
                    destino_id: (carpeta_seleccion_mover != null ? carpeta_seleccion_mover.id : 0)
                },
                success: function(result){  
                    alerta(result.message, true);   
                    navegar();
                },
                error: function(error) {      
                    $("#cargando_pagina").hide();              
                    alerta(response_helper(error), false);
                }
            }); 
        }
        else
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/repositorios/archivos/mover",
                data: {                    
                    cert_archivo_id: archivo_accion,
                    destino_id: (carpeta_seleccion_mover != null ? carpeta_seleccion_mover.id : 0)
                },
                success: function(result){  
                    alerta(result.message, true);   
                    navegar(); 
                },
                error: function(error) {      
                    $("#cargando_pagina").hide();              
                    alerta(response_helper(error), false);
                }
            });            
        }
    }    
}

/**
 * ARCHIVOS
 */
function nuevo_archivo() {
    
    if($("#repositorio_select").val() == 0){
        alerta("El usuario no tiene asignado un repositorio");
        return;
    }
    
    archivo_accion = 0;
    limpiar('#cargar_contenido');
    $("#para_firma").prop('checked', true);
    $("#input_subir").val(null);//eliminamos la selecciona anterior
    $("#descripcion").val("");       
    $('#cargar_archivo').modal("show");           
    
}

function cargar_archivo() {
    if(validar('#cargar_contenido'))
    {     
        var res = true;
        var t_files = $("#input_subir")[0].files;        
        for (let i = 0; i < t_files.length; i++) {
            if(t_files[i].size > size_maximo)   
                res = false;              
        }

        if(res == false)  {
            alerta("El tamaño de alguno de los archivos seleccionados es mayor a "+format_getSize(size_maximo) ,false);
            return;
        }

        $("#cargar_archivo").modal("hide");
        $("#cargando_pagina").show();

        var formData = new FormData();
        formData.append('cert_repositorio_id', $("#repositorio_select").val());
        formData.append('ubicacion', $("input[name=ubicacion]:checked").val()); 
        formData.append('cert_carpeta_id', (carpeta_seleccion != null ? carpeta_seleccion.id : 0));    
        formData.append('descripcion', $("#descripcion").val()); 
        for (let i = 0; i < t_files.length; i++) {
            formData.append("archivos[]", t_files[i]);            
        }

        $.ajax({
            type: "POST",
            url: default_server+"/json/repositorios/archivos",
            processData: false,
            contentType: false,
            data: formData,
            success: function(result){  
                alerta(result.message, true);   
                navegar(); 
            },
            error: function(error) {      
                $("#cargando_pagina").hide();  
                var result = response_helper(error);            
                alerta(response_helper(result), false);
                if(result.indexOf('compression technique which is not supported') >= 0)
                    navegar(); 
            }
        });
    }
}

function mover_archivo(ida) {
    archivo_accion = ida;//archivo a mover   
    tipo_mover = 1;//archivo
    ir_mover();
    $('#mover_carpeta').modal("show");
}

function eliminar_archivo(ida) {
    if(confirm("Esta seguro que desea eliminar?"))
    {
        $("#cargando_pagina").show();

        $.ajax({
            type: "DELETE",
            url: default_server+"/json/repositorios/archivos/"+ida,
            data: { },
            success: function(result){  
                alerta(result.message, true);   
                navegar(); 
            },
            error: function(error) {      
                $("#cargando_pagina").hide();              
                alerta(response_helper(error), false);
            }
        });  
        
    }
}

//-----------------------------------------
function obtener_seleccionados() {
    var result = [];
    $("input.archivo_select").each(function() {
        if($(this).is(":checked"))            
            result.push({ id: $(this).data("id") });        
    });
    return result;    
}

/**
 * FIRMA
 */

function firmar_seleccionado() {
    var selecionados = obtener_seleccionados();
    if(selecionados.length > 0){
        var params = '';
        for (let i = 0; i < selecionados.length; i++) {            
            params += (params==''?'':'&')+'arch[]='+selecionados[i].id;            
        }
        location.href = default_server+'/admin/certificado/firma?'+params;    
    } else {
        alerta("Seleccione por lo menos un archivo",false);
    }
}


function publicar_seleccionado() {
    var selecionados = obtener_seleccionados();
    if(selecionados.length > 0){
        if(confirm("Esta seguro que desea publicar los archivos seleccionados?\nUna ves publicados no podrá modificarlos")){
            var select = [];
            for (let i = 0; i < selecionados.length; i++) {
                select.push(selecionados[i].id);           
            }

            $("#cargando_pagina").show();

            $.ajax({
                type: "POST",
                url: default_server+"/json/repositorios/archivos/publicar",
                data: { lista_archivos: select },
                success: function(result){  
                    alerta(result.message, true);   
                    navegar(); 
                },
                error: function(error) {      
                    $("#cargando_pagina").hide();              
                    alerta(response_helper(error), false);
                }
            });  
        }
    } else {
        alerta("Seleccione por lo menos un archivo",false);
    }
}
