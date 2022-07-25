$( document ).ready(function() {   

    $("#documento_tipo_id").select2({
        theme: 'bootstrap4',
        width: '100%',
    });

    $("#o_dependencia_id").on('change', function() {
        $("#cargando_pagina").show();
        window.location.href = default_server + "/admin/tramite/emision?origen="+$(this).val();
    });

    $("#o_empleado_id").select2({
        theme: 'bootstrap4',
        width: '100%',
    });

    
});

//-------------

function guardar_todo() {
    if(validar('#form-documento'))
    {       
        if(destinos.length > 0)
        {           
            if(remitente_en_destino()){
                alerta("El remitente no puede estar en el destino");
                return;
            }
            //validaciones adicionales si es pide         
            $("#cargando_pagina").show();

            $.ajax({
                type: "POST",
                url: default_server+"/json/tramites",
                data: {
                    procedimiento_id: $("#procedimiento_id").val(),
                    o_dependencia_id: $("#o_dependencia_id").val(),
                    o_empleado_id: $("#o_empleado_id").val(),
                    o_persona_id: $("#o_empleado_id").find(":selected").data("persona"),
                    documento_tipo_id: $("#documento_tipo_id").val(),
                    numero: $("#numero").val(),
                    remitente: $("#o_empleado_id option:selected").text(),
                    folios: $("#folios").val(),
                    asunto: $("#asunto").val(),
                    observaciones: $("#observaciones").val(),
                    archivo_id: (archivo_seleccion != null ? archivo_seleccion.id : 0),
                    destinos: destinos,
                    anexos: get_anexos()
                },
                success: function(result){  
                    alerta(result.message, true); 
                    window.location.href = default_server + "/admin/tramite/emision/emitidos";
                },
                error: function(error) {                
                    alerta(response_helper(error), false);
                    $("#cargando_pagina").hide();
                },
                complete: function() {
                    
                }
            });
        } 
        else
            alerta('Seleccione por lo menos un destino', false);
    }
}

function remitente_en_destino() {
    var res = false;
    var rem = $("#o_empleado_id").val();
    for (let i = 0; i < destinos.length; i++) {
        if(destinos[i].d_empleado_id == rem){
            res = true;
            break;
        }
    }
    return res;
}