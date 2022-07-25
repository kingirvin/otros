$( document ).ready(function() {   
    $('.nav-pills a').on('show.bs.tab', function(e){
        var target = $(e.target).attr("href");
        if(target == "#tabs-referido"){
            $("#buscar_archivo").show();
            $("#anexos_card").show();
        }
        else {
            $("#buscar_archivo").hide();
            $("#anexos_card").hide();
        }
    });

    $("#documento_tipo_id").select2({
        theme: 'bootstrap4',
        width: '100%',
    });

    $("#o_empleado_id").select2({
        theme: 'bootstrap4',
        width: '100%',
    });
});


function guardar_todo() {
    var id = $('.tab-content .active').attr('id');   

    if(id == "tabs-proveido") {//es derivacion con proveido
        //validamos
        if(validar("#tabs-proveido")){
            if(!$("input:radio[name=accion_id]").is(":checked")){
                alerta("Seleccione alguna de las acciones");
                return;
            }

            if(destinos.length == 0){
                alerta('Seleccione por lo menos un destino', false);
                return;
            } 
        } else {
            return;
        }
        //llenamos datos
        var el_metodo = 0;

    } else {//es derivacion adjuntando un documento
        //validamos
        if(validar("#tabs-referido")){
            if(destinos.length == 0){
                alerta('Seleccione por lo menos un destino', false);
                return;
            }            
        } else {
            return;
        }
        //llenamos datos
        var el_metodo = 1;
    }

    $("#cargando_pagina").show();

    $.ajax({
        type: "POST",
        url: default_server+"/json/movimientos/derivar",
        data: {
            movimiento_id: elMovimiento,
            destinos: destinos,
            metodo: el_metodo,
            o_empleado_id: (el_metodo == 1 ? $("#o_empleado_id").val() : null),
            o_persona_id: (el_metodo == 1 ? $("#o_empleado_id").find(":selected").data("persona") : null),
            documento_tipo_id: (el_metodo == 1 ? $("#documento_tipo_id").val() : null),
            numero: (el_metodo == 1 ? $("#numero").val() : null),
            remitente: (el_metodo == 1 ? $("#o_empleado_id option:selected").text() : null),
            asunto: (el_metodo == 1 ? $("#asunto").val() : null),
            folios: (el_metodo == 1 ? $("#folios").val() : null),
            observaciones: (el_metodo == 1 ? $("#observaciones").val() : null),
            accion_id: (el_metodo == 0 ? $("input[name='accion_id']:checked").val() : null),
            accion_otros: (el_metodo == 0 ? $("#accion_otros").val() : null),
            archivo_id: (archivo_seleccion != null ? archivo_seleccion.id : 0),
            anexos: get_anexos()
        },
        success: function(result){  
            alerta(result.message, true); 
            window.location.href = default_server + "/admin/tramite/recibidos";
        },
        error: function(error) {                
            alerta(response_helper(error), false);
            $("#cargando_pagina").hide();
        },
        complete: function() {
            
        }
    });
}