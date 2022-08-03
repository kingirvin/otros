var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_por_recepcionar").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [[ 6, "desc" ]],
        "ajax": {
            "url":  default_server+"/json/movimientos/pendientes",
            "type": "GET",
            "data": function ( d ) {
                d.year = $("#year_select").val(); 
                d.dependencia_id = $("#dependencia_select").val();  
                d.persona_id = $("#persona_select").val();  
            },
            error: default_error_handler        
        },
        "columns": [
            { "data": "tramite.codigo", "orderable": false, className: "w-1",
                render: function ( data, type, full ) {
                    return get_codigo(full);
                }
            },
            { "data": "documento.numero", "orderable": false,
                render: function ( data, type, full ) {
                    return '<div title="'+data+'">'+full.documento.documento_tipo.abreviatura+' '+textoMax(data,25)+'</div><small title="'+full.documento.asunto+'" class="d-block text-muted text-truncate mt-n1 lh-1">'+textoMax(full.documento.asunto,25)+'</small>';
                }
            },
            { "data": "documento.asunto", "orderable": false, "searchable": true, "visible": false},
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
            { "data": "o_user.siglas", "orderable": false, "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                   
                    return '<span title="'+full.o_user.nombre+' '+full.o_user.apaterno+' '+full.o_user.amaterno+'" class="avatar">'+data+'</span>';;
                }        
            },            
            { "data": "o_fecha", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {
                    return '<div>'+dis_fecha(data)+'<small class="d-block lh-1">'+dis_solo_hora(data)+' h</small></div>';
                }
            },  
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {   
                        var res = 
                        '<button class="btn btn-primary" onclick="'+(full.o_tipo == 3 ? 'recepcionar_pide':'recepcionar')+'('+full.id+');">'+
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><polyline points="7 11 12 16 17 11" /><line x1="12" y1="4" x2="12" y2="16" /></svg> Recibir'+
                        '</button>';
                    return res;
                }
            }
        ],
        /*createdRow: function ( row, data ) {
            if(data.d_user_id != null){
                $(row).addClass('destino_user');
            }
        },*/
        "dom": default_datatable_dom,
        "language": default_datatable_language,
        "initComplete" : default_datatable_buttons,
        rowCallback: function(row, data, index) {
            if (data.o_dependencia_id == laDependencia) {
                $(row).addClass("bg-interno");
            }
        },
    }); 

    /*tabla.on( 'length', function ( e, settings, len ) {
        tabla.ajax.reload(); // user paging is not reset on reload
    });*/
    
    $('#year_select').on('change', function() {
        tabla.ajax.reload();
    });   

    $('#dependencia_select').on('change', function() {
        $("#cargando_pagina").show();
        window.location.href = default_server + "/admin/tramite/recepcion?destino="+$(this).val();
    });

    $('#persona_select').on('change', function() {
        tabla.ajax.reload();
    });

    
});

function get_codigo(movimiento) {
    var res = '<a href="'+default_server+'/admin/tramite/seguimiento/'+movimiento.tramite.id+'" target="_blank" class="d-block nowrap text-purple" title="CÓDIGO ÚNICO DE TRÁMITE">T-'+movimiento.tramite.codigo+'</a>';
    if(movimiento.copia == 1)
        res += '<span class="badge bg-indigo-lt">SOLO COPIA</span>';

    return res;
}

function get_origen(movimiento) {
    var res = '<div class="d-flex align-items-center">';

    if(movimiento.o_tipo == 1){//0:interno, 1:externo
        res += '<div class="pe-2"><span class="avatar bg-pink-lt" title="ORIGEN EXTERNO"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="3.6" y1="9" x2="20.4" y2="9" /><line x1="3.6" y1="15" x2="20.4" y2="15" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg></span></div>';
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

function recepcionar(iditem) {
    editItem = iditem;
    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);
    if(objitem != null)    
    {
        $("#r_tramite").val("T-"+objitem.tramite.codigo);
        $("#r_documento").val(objitem.documento.documento_tipo.abreviatura+' '+objitem.documento.numero);
        $("#r_remitente").val(objitem.documento.remitente);
        $("#r_folios").val(objitem.documento.folios);
        $("#r_asunto").val(objitem.documento.asunto);
        var motivo = '';//0:inicio, 1:derivacion (proveido), 2:referido
        if(objitem.tipo == 0){
            motivo = 'NUEVO TRÁMITE';
        } else if(objitem.tipo == 1) {
            motivo = 'DERIVADO [PROVEIDO] '+(objitem.accion ? ('- '+objitem.accion.nombre) : '');
        } else {
            motivo = 'DERIVADO [DOCUMENTO]';
        }

        $("#r_motivo").val(motivo); 
        $("#d_observacion").val("");
        $("#recibir").modal("show");
    }
    else
        alert("No se encontro el item");  
}

function guardar() {
    $("#recibir").modal("hide");        
    $.ajax({
        type: "PUT",
        url: default_server+"/json/movimientos/"+editItem+"/recibir",
        data: {                   
            d_observacion: $("#d_observacion").val()
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