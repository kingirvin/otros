var tabla;
var editItem = 0;


$( document ).ready(function() {

    tabla = $("#t_emitidos").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url":  default_server+"/json/documentos/emitidos",
            "type": "GET",
            "data": function ( d ) {
                d.year = $("#year_select").val(); 
                d.dependencia_id = $("#dependencia_select").val();  
                d.persona_id = $("#persona_select").val();
            },
            error: default_error_handler
        },
        "columns": [   
            { "data": "o_numero", className: "w-1",
                render: function ( data, type, full ) {                      
                    return '<div>'+ceros(data,5)+'</div>';
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
            { "data": "numero", "orderable": false,
                render: function ( data, type, full ) {                      
                    return '<div title="'+data+'">'+full.documento_tipo.abreviatura+' '+textoMax(data,30)+'</div><small title="'+full.asunto+'" class="d-block text-muted text-truncate mt-n1 lh-1">'+textoMax(full.asunto,30)+'</small>';
                }        
            },
            { "data": "asunto", "orderable": false, "searchable": true, "visible": false},
            { "data": "remitente"},
            { "data": "movimientos", "searchable": false, "orderable": false,
                render: function ( data, type, full ) {       
                    return get_destinos(data);
                }        
            },
            { "data": "user.siglas", "orderable": false, "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                   
                    return '<span title="'+full.user.nombre+' '+full.user.apaterno+' '+full.user.amaterno+'" class="avatar">'+data+'</span>';;
                }        
            }, 
            { "data": "created_at", "searchable": false, className: "w-1",
                render: function ( data, type, full ) { 
                    return '<div>'+dis_fecha(data)+'<small class="d-block lh-1">'+dis_solo_hora(data)+' h</small></div>';
                }        
            },  
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {
                    var res = 
                    '<div class="dropdown dropstart">'+
                        '<button class="btn align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">'+
                            'Editar'+
                        '</button>'+
                        '<div class="dropdown-menu">'+
                            '<a class="dropdown-item '+(full.user_id != elUser ? 'disabled' : '')+'" href="javascript:void(0);" onclick="modificar('+full.id+')">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>'+
                                'Modificar'+
                            '</a>'+
                            '<a class="dropdown-item '+(full.user_id != elUser ? 'disabled' : '')+'" href="javascript:void(0);" onclick="anular('+full.id+')" >'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                'Anular'+
                            '</a>'+
                        '</div>'+
                    '</div>';
                    return res;
                }
            }
        ],
        "dom": default_datatable_dom,
        "language": default_datatable_language,
        "initComplete" : default_datatable_buttons
    }); 
    
    $('#year_select').on('change', function() {
        tabla.ajax.reload();
    });   

    $('#dependencia_select').on('change', function() {
        $("#cargando_pagina").show();
        window.location.href = default_server + "/admin/tramite/emision/emitidos?origen="+$(this).val();
    });  

    $('#persona_select').on('change', function() {
        tabla.ajax.reload();
    });

    
});

function get_destinos(movimientos) {        
    var destinos = [];
    var destino_text = '';

    for (let i = 0; i < movimientos.length; i++) {
        if(movimientos[i].d_tipo == 0)//interno
        {
            destinos.push({
                d_text: textoMax(movimientos[i].d_dependencia.nombre, 30),
                d_completo: movimientos[i].d_dependencia.nombre,
                d_aux: (movimientos[i].d_persona != null ? movimientos[i].d_persona.nombre+' '+movimientos[i].d_persona.apaterno+' '+movimientos[i].d_persona.amaterno : '')
            });
        } 
        else 
        {
            destinos.push({
                d_text: safeText(movimientos[i].d_nro_documento)+' | '+textoMax(movimientos[i].d_nombre, 30),
                d_completo: safeText(movimientos[i].d_nro_documento)+' | '+movimientos[i].d_nombre,
                d_aux: ''
            });
        }
    }

    if(destinos.length == 0){
        return 'SIN DESTINOS';
    }
    else if(destinos.length == 1){
        return '<div class="lh-1" title="'+destinos[0].d_completo+'">'+destinos[0].d_text+(destinos[0].d_aux != '' ? '<small class="d-block text-muted">'+destinos[0].d_aux+'</small>' : '')+'</div>';
    }
    else {
        var destino_text = '';
        for (let i = 0; i < destinos.length; i++) {            
            destino_text += '- '+destinos[i].d_completo+'&#10;';
        }
        return '<div title="'+destino_text+'">MULTIPLES DESTINOS ('+destinos.length+')</div>'; 
    }
}


function modificar(iditem) {
    editItem = iditem;
    limpiar('#form_editar');
    vaciar('#form_editar');
   
    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {
        $("#documento_tipo_id").val(objitem.documento_tipo_id);
        $("#numero").val(objitem.numero);
        $("#remitente").val(objitem.remitente);
        $("#folios").val(objitem.folios);
        $("#asunto").val(objitem.asunto);
        $("#observaciones").val(safeText(objitem.observaciones));        
        $("#modificar").modal("show");
    }
    else
        alert("No se encontro el item"); 
}

function guardar_modificar() 
{
    if(validar('#form_editar'))
    {
        $("#modificar").modal("hide");        
        $.ajax({
            type: "PUT",
            url: default_server+"/json/documentos/"+editItem,
            data: {                
                documento_tipo_id: $("#documento_tipo_id").val(),                
                numero: $("#numero").val(),                
                //remitente: $("#remitente").val(),
                folios: $("#folios").val(),
                asunto: $("#asunto").val(),
                observaciones: $("#observaciones").val()
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

function anular(iditem) {
    if(confirm('Esta seguro que desea anular el envio?'))
    {
        $.ajax({
            type: "DELETE",
            url: default_server+"/json/documentos/"+iditem,        
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
