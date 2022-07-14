var destinos=[];

$( document ).ready(function() {

    $("#sede_id").on('change', function() {
        cargar_dependencias();
    });
    
    $("#dependencia_select").select2({
        dropdownParent: $('#destino_dependencia'),
        width: '100%',
        theme: 'bootstrap4'
    });
});

/**
 * SELECCIONAR DEPENDENCIA
 */
function nuevo_destino_dependencia() {    
    limpiar('#destino_dependencia');
    vaciar('#destino_dependencia');    
    $('#copia').prop('checked', false);    
    $("#sede_id").val($("#sede_id option:first").val());   
    $("#sede_id").trigger('change');    
    $("#destino_dependencia").modal("show");
}

function cargar_dependencias() {
    $("#dependencia_loading").show();
    $("#btn_destino").prop('disabled', true);

    $.ajax({
        type: "GET",
        url: default_server+"/json/dependencias/"+$("#sede_id").val()+"/buscar",
        success: function(result){  
            var datos = result.data;
            $("#dependencia_select").html('<option value="0">Seleccione...</option>');
            for (let i = 0; i < datos.length; i++) {
                $("#dependencia_select").append('<option value="'+datos[i].id+'" '+(datos[i].id==$("#o_dependencia_id").val() ? 'disabled':'')+'>'+datos[i].nombre+'</option>');
            }    
            $("#dependencia_select").trigger('change');       
        },
        error: function(error) {                
            alerta(response_helper(error), false);
        },
        complete: function() {                
            $("#dependencia_loading").hide();
            $("#btn_destino").prop('disabled', false);
        }
    });
}

function agregar_destino_dependencia() {
    if(validar("#interno"))
    {
        var res = elementId($("#dependencia_select").val(), destinos);
        if(res == null)
        {
            destinos.push({ 
                tipo: 0,//0:interno (dependencia)
                id: $("#dependencia_select").val(),
                ruc: "",
                entidad:  "",
                dependencia_nombre: $("#dependencia_select option:selected").text(),
                sede_id: $("#sede_id").val(),
                sede_nombre: $("#sede_id option:selected").text(),
                como_copia: ($("#copia").is(':checked') ? 1 : 0 ),
                dirigido_otro: 0,
                d_identidad_tipo_id: 0,
                d_nro_documento: "",
                d_nombre: "",
                d_cargo: "",
                cuo_referencia: ""
            });

            render_destinos();
            $("#destino_dependencia").modal("hide");
        }
        else
            alert("La dependencia ya ha sido seleccionada!");           
    }
}

function nuevo_destino_externo() {    
    limpiar('#destino_externo');
    vaciar('#destino_externo');
    $("#d_identidad_documento_id").val(0);     
    $("#destino_externo").modal("show");
}

function agregar_destino_externo() {
    if(validar("#externo"))
    {           
        destinos.push({
            tipo: 1,//1:externo
            id: 0,
            ruc: "",
            entidad:  "",
            dependencia_nombre: "",
            sede_id: 0,
            sede_nombre: "",
            como_copia: 0,
            dirigido_otro: 1,
            d_identidad_documento_id: $("#d_identidad_documento_id").val(),
            d_nro_documento: $("#d_nro_documento").val(),
            d_nombre: $("#d_nombre").val(),
            d_cargo: "",
            cuo_referencia: ""
        }); 
        
        render_destinos();
        $("#destino_externo").modal("hide");
    }
}

function render_destinos() {
    $("#destinos").html("");
    for (let i = 0; i < destinos.length; i++) {
        //0:dependencia, 1:estudia, 2:externo
        if(destinos[i].tipo == 0)
        {
            $("#destinos").append(
                '<div class="list-group-item">'+
                    '<div class="row align-items-center">'+
                        '<div class="col text-truncate">'+
                            destinos[i].dependencia_nombre+
                            '<div class="text-truncate lh-1">'+
                                (destinos[i].como_copia == 1 ? '<span class="badge bg-indigo-lt">SOLO COPIA</span>&nbsp;' : '')+
                                '<small class="text-muted align-middle">'+destinos[i].sede_nombre+'</small>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-auto">'+
                            '<a href="javascript:void(0);" onclick="eliminar_destino('+i+');" class="list-group-item-actions text-danger title">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                            '</a>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            ); 
        }
        else 
        {
            $("#destinos").append(
                '<div class="list-group-item">'+
                    '<div class="row align-items-center">'+
                        '<div class="col text-truncate">'+
                            destinos[i].d_nombre+
                            '<div class="text-truncate lh-1">'+
                                '<small class="text-muted">'+destinos[i].d_nro_documento+'</small>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-auto">'+
                            '<a href="javascript:void(0);" onclick="eliminar_destino('+i+');" class="list-group-item-actions text-danger ">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                            '</a>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            ); 
        }         
    }
}

function eliminar_destino(index) {
    if(confirm("Esta seguro que desea remover el destino?"))
    {
        destinos.splice(index, 1);
        render_destinos();
    }
}
