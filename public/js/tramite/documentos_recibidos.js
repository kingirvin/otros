var tabla;
var editItem = 0;
$( document ).ready(function() {

    tabla = $("#t_recepcionados").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url":  default_server+"/json/movimientos/recibidos",
            "type": "GET",
            "data": function ( d ) {
                d.year = $("#year_select").val(); 
                d.dependencia_id = $("#dependencia_select").val(); 
                d.persona_id = $("#persona_select").val();  
                d.estado = $("#estado_select").val();                           
            },
            error: default_error_handler
        },
        "columns": [    
            { "data": "d_numero", className: "w-1 text-center",
                render: function ( data, type, full ) {                      
                    var res = '<div>'+ceros(data,5)+'</div>';
                    if(full.copia == 1)
                        res += '<span class="badge bg-indigo-lt">SOLO COPIA</span>';
                    return res;
                }        
            }, 
            { "data": "tramite.codigo", "orderable": false, className: "w-1",
                render: function ( data, type, full ) {                      
                    var res = '<a href="'+default_server+'/admin/tramite/seguimiento/'+full.tramite.id+'" target="_blank" title="CÓDIGO ÚNICO DE TRÁMITE" class="d-block nowrap lh-1 text-purple">T-'+data+'</a>';
                    if(full.tramite.estado == 2)//2:observado                     
                        res += '<div class="text-center lh-1"><span class="badge bg-red">OBSERVADO</span></div>';
                    
                    return res;
                }        
            },
            { "data": "documento.numero", "orderable": false,
                render: function ( data, type, full ) {                      
                    return '<div title="'+full.documento.documento_tipo.abreviatura+' '+data+'">'+full.documento.documento_tipo.abreviatura+' '+textoMax(data, 25)+'</div><small title="'+full.documento.asunto+'" class="d-block text-muted text-truncate mt-n1 lh-1">'+textoMax(full.documento.asunto,25)+'</small>';
                }        
            },
            { "data": "documento.asunto", "orderable": false, "searchable": true, "visible": false },
            { "data": "o_descripcion", "orderable": false,
                render: function ( data, type, full ) {       
                    return get_origen(full);
                }
            },
            { "data": null, "orderable": false, "searchable": false,
                render: function ( data, type, full ) {       
                    return get_motivo(full);
                }
            },
            { "data": "d_user.siglas", "orderable": false, "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                   
                    return '<span title="'+full.d_user.nombre+' '+full.d_user.apaterno+' '+full.d_user.amaterno+'" class="avatar">'+data+'</span>';
                }        
            },          
            { "data": "d_fecha", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {                      
                    return '<div>'+dis_fecha(data)+'<small class="d-block lh-1">'+dis_solo_hora(data)+' h</small></div>';
                }        
            }, 
            { "data": null, "orderable": false, "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {   
                    return get_estado(full);               
                }
            },
            {
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                render: function ( data, type, full ) {   
                    var res = 
                    '<div class="btn-list flex-nowrap">'+
                        /*'<a href="'+default_server+'/admin/tramite/recepcion/asignaciones/'+full.id+'" title="ASIGNACIONES">'+
                            '<span class="avatar bg-info-lt">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>'+
                                (full.asignaciones > 0 ? '<span class="badge bg-blue">'+full.asignaciones+'</span>' : '')+                    
                            '</span>'+
                        '</a>'+*/
                        '<div class="dropdown dropstart">'+
                            '<button class="btn align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">'+
                                'Editar'+
                            '</button>'+
                            '<div class="dropdown-menu">'+
                                get_acciones(full)+
                            '</div>'+
                        '</div>'+
                    '</div>';
                    return res;
                }
            }
        ],
        "dom": default_datatable_dom,
        "language": default_datatable_language,
        "initComplete" : default_datatable_buttons,
        rowCallback: function(row, data, index) {
            if (data.o_dependencia_id == laDependencia) {
                $(row).addClass("bg-interno");
            }
        },
    })    
    
    $('#year_select').on('change', function() {
        tabla.ajax.reload();
    });

    $('#dependencia_select').on('change', function() {
        $("#cargando_pagina").show();
        window.location.href = default_server + "/admin/tramite/recibidos?destino="+$(this).val();
    });

    $('#persona_select').on('change', function() {
        tabla.ajax.reload();
    });

    $('#estado_select').on('change', function() {
        tabla.ajax.reload();
    });   

    //laDependencia
    
});

function get_origen(movimiento) {
    var res = '<div class="d-flex align-items-center">';

    if(movimiento.o_tipo == 1){//0:interno, 1:externo
        res += '<div class="pe-2"><span class="avatar bg-yellow-lt" title="ORIGEN EXTERNO"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="3.6" y1="9" x2="20.4" y2="9" /><line x1="3.6" y1="15" x2="20.4" y2="15" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg></span></div>';
        res += '<div><div>EXTERNO</div><small class="d-block text-muted text-truncate mt-n1 lh-1" title="'+movimiento.o_descripcion+'">'+textoMax(movimiento.o_descripcion,25)+'</small></div>';
    } else {
        res += '<div><div title="'+movimiento.o_dependencia.nombre+'">'+textoMax(movimiento.o_dependencia.nombre, 25)+'</div><small class="d-block text-muted text-truncate mt-n1 lh-1">'+movimiento.o_dependencia.sede.nombre+'</small></div>';
    }    
    
    res += '</div>';
    return res;
}

function get_motivo(movimiento) {
    //destinatario
    var res = '';
    if(movimiento.d_persona != null)      
        res += '<div class="lh-1" title="'+movimiento.d_persona.nombre+' '+movimiento.d_persona.apaterno+' '+movimiento.d_persona.amaterno+'">'+textoMax((movimiento.d_persona.nombre+' '+movimiento.d_persona.apaterno+' '+movimiento.d_persona.amaterno),25)+'</div>';
    else
        res += '<div class="lh-1">SOLO DEPENDENCIA</div>';
    //motivo
    if(movimiento.tipo == 0){
        res += '<small class="d-block text-muted">NUEVO TRÁMITE</small>';
    } else if(movimiento.tipo == 1) {
        var temp = 'DERIVADO [PROVEIDO]';
        if(movimiento.accion != null) {
            temp +=  ' &#183 '+movimiento.accion.nombre;
        }
        res += '<small class="d-block text-muted" title="'+temp+'">'+textoMax(temp,30)+'</small>';
        
    } else {
        res += '<small class="d-block text-muted">DERIVADO [DOCUMENTO]</small>';
    }

    return res;
}

function get_estado(movimiento) {
    //0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado      
    if(movimiento.estado == 2){
        return '<span class="badge bg-yellow-lt">PENDIENTE</span>'; 
    } else if(movimiento.estado == 3){
        return '<span class="badge bg-blue-lt">DERIVADO</span>'; 
    } else if(movimiento.estado == 4){
        return '<span class="badge bg-green-lt">ATENDIDO</span>'; 
    } else if(movimiento.estado == 5){
        return '<span class="badge bg-red-lt">OBSERVADO</span>';
    } else {
        return '<span class="badge">UNKNOWN</span>';
    }
}

function get_acciones(movimiento) {
    //0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado      
    var res = '';
    //DERIVAR
    if(movimiento.estado == 2)//recepcionado
    {
        res += 
        '<a class="dropdown-item text-blue" href="'+default_server+'/admin/tramite/recibidos/derivar/'+movimiento.id+'">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>'+
            'Derivar'+
        '</a>'+
        '<a class="dropdown-item" href="javascript:void(0);" onclick="atender('+movimiento.id+')">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 12l2 2l4 -4" /></svg>'+
            'Marcar atendido'+
        '</a>'+
        '<a class="dropdown-item" href="javascript:void(0);" onclick="observar('+movimiento.id+')">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>'+
            'Observar'+
        '</a>'+
        '<div class="dropdown-divider"></div>'+
        '<a class="dropdown-item" href="javascript:void(0);" onclick="anular('+movimiento.id+')">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 19h-11l-4 -4a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9 9" /><line x1="18" y1="12.3" x2="11.7" y2="6" /></svg>'+
            'Anular recepción'+
        '</a>';
    } 
    else if(movimiento.estado == 3)//3:derivado/referido
    {
        res += 
        '<a class="dropdown-item text-blue" href="'+default_server+'/admin/tramite/recibidos/derivar/'+movimiento.id+'">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>'+
            'Derivar'+
        '</a>'+
        '<a class="dropdown-item" href="'+default_server+'/admin/tramite/recibidos/derivaciones/'+movimiento.id+'">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-4a3 3 0 0 1 3 -3h5" /><path d="M9 17l3 -3l-3 -3" /><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 11v-6a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-9.5" /></svg>'+
            'Derivaciones'+
        '</a>';
    }
    else if(movimiento.estado == 4) //4:atendido
    {
        res += 
        '<a class="dropdown-item" href="javascript:void(0);" onclick="anular_atender('+movimiento.id+')">'+    
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /></svg>'+
            'Desmarcar atendido'+
        '</a>';
    }
    else if(movimiento.estado == 5)//5:observado   
    {
        res += 
        '<a class="dropdown-item" href="javascript:void(0);" onclick="observaciones('+movimiento.id+')">'+    
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /><line x1="12" y1="9" x2="12" y2="12" /><line x1="12" y1="15" x2="12.01" y2="15" /></svg>'+
            'Observaciones'+
        '</a>';
    }
    else 
    {
        res += 
        '<span class="dropdown-header">SIN OPCIONES</span>';
    }    
    
    return res;
}

function atender(iditem) {
    editItem = iditem;
    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);
    if(objitem != null)    
    {
        $("#tramite_fin").val("T-"+objitem.tramite.codigo);
        $("#documento_fin").val(objitem.documento.documento_tipo.abreviatura+' '+objitem.documento.numero);
        $("#asunto_fin").val(objitem.documento.asunto);
        $("#observacion_fin").val("");        
        $("#atender").modal("show");
    }
    else
        alert("No se encontro el item"); 
}

function guardar_atender() {
    $("#atender").modal("hide");        
    $.ajax({
        type: "PUT",
        url: default_server+"/json/movimientos/"+editItem+"/atender",
        data: {                   
            f_observacion: $("#observacion_fin").val()
        },
        success: function(result){  
            alerta(result.message, true); 
            tabla.ajax.reload();
        },
        error: function(error) {                
            alerta(response_helper(error), false);     
        }
    });
}

function anular_atender(iditem) {
    if(confirm("Esta seguro que desea anular el estado atendido?"))
    {
        $.ajax({
            type: "PUT",
            url: default_server+"/json/movimientos/"+iditem+"/atencion/anular",            
            success: function(result){  
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);     
            }
        });
    }  
}

function anular(iditem) {
    if(confirm('Esta seguro que desea anular recepción?'))
    {        
        $.ajax({
            type: "DELETE",
            url: default_server+"/json/movimientos/"+iditem+"/recibido/anular",        
            success: function(result){  
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);     
            }
        });
    }
}

function observar(iditem) {
    editItem = iditem;
    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);
    if(objitem != null)    
    {
        $("#tramite_obs").val("T-"+objitem.tramite.codigo);
        $("#documento_obs").val(objitem.documento.documento_tipo.abreviatura+' '+objitem.documento.numero);
        $("#asunto_obs").val(objitem.documento.asunto);
        $("#detalle_obs").val("");
        limpiar('#observar');        
        $("#observar").modal("show");
    }
    else
        alert("No se encontro el item");  
}

function guardar_observacion() {
    if(validar('#observar'))
    {
        $("#observar").modal("hide");        
        $.ajax({
            type: "POST",
            url: default_server+"/json/movimientos/"+editItem+"/observar",
            data: {                   
                detalle: $("#detalle_obs").val()
            },
            success: function(result){  
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);     
            }
        });
    }
}

function observaciones(iditem) {
    editItem = iditem;
    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);
    if(objitem != null)    
    {
        $("#tramite_obnes").val("T-"+objitem.tramite.codigo);
        $("#documento_obnes").val(objitem.documento.documento_tipo.abreviatura+' '+objitem.documento.numero);
        $("#asunto_obnes").val(objitem.documento.asunto);
        $("#observaciones").modal("show");
        $("#cargando_observaciones").show();

        $.ajax({
            type: "GET",
            url: default_server+"/json/movimientos/"+iditem+"/observaciones",
            data: { },
            success: function(result){  
                var obnes = result.data;
                $("#lista_obnes").html('');
                for (let i = 0; i < obnes.length; i++) {
                    $("#lista_obnes").append(
                    '<div class="list-group-item">'+
                        '<div class="row align-items-center">'+
                            '<div class="col">'+
                                '<div class="text-body d-block">'+obnes[i].detalle+'</div>'+
                            '</div>'+
                            '<div class="col-auto">'+
                                '<a href="javascript:void(0);" onclick="eliminar_observacion('+obnes[i].id+');" class="list-group-item-actions show text-danger" title="ELIMINAR">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                '</a>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                    ); 
                }                
                $("#cargando_observaciones").hide();   
            },
            error: function(error) {                
                alerta(response_helper(error), false);
                $("#cargando_observaciones").hide();
            }
        });
    }
    else
        alert("No se encontro el item");  
}

function eliminar_observacion(idobs) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $("#observaciones").modal("hide");
        $.ajax({
            type: "DELETE",
            url: default_server+"/json/movimientos/observacion/"+idobs+"/anular",        
            success: function(result){  
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);     
            }
        });
    }
}


