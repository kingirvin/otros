$( document ).ready(function() {   

    $("#documento_tipo_id").select2({
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
            //validaciones adicionales si es pide         
            $("#cargando_pagina").show();

            $.ajax({
                type: "POST",
                url: default_server+"/json/tramites",
                data: {
                    procedimiento_id: $("#procedimiento_id").val(),
                    o_dependencia_id: $("#o_dependencia_id").val(),
                    documento_tipo_id: $("#documento_tipo_id").val(),
                    numero: $("#numero").val(),
                    remitente: $("#remitente").val(),
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