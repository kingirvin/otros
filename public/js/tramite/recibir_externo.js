$( document ).ready(function() {   

    $("#documento_tipo_id").select2({
        theme: 'bootstrap4',
        width: '100%',
    });

    $('#d_dependencia_id').on('change', function() {
        $("#cargando_pagina").show();
        window.location.href = default_server + "/admin/tramite/recepcion/externo?destino="+$(this).val();
    });

});

function cambio_persona(item) {
    if($(item).val() == 1){
        $("#es_juridica").removeClass("oculto");
    } else {
        $("#es_juridica").addClass("oculto");
    }
}

function guardar_todo() {
    var persona = $("#persona").val();
    var correcto = true;

    if(persona == 1){
        if(!validar("#es_juridica")){
            correcto = false;
            console.log("-es_juridica");
        }
    }

    if(!validar("#es_natural")){
        correcto = false;
        console.log("-es_natural");
    }

    if(!validar("#form_documento")){
        correcto = false;
        console.log("-form_documento");
    }

    if(!validar("#form_destino_interno")){
        correcto = false;
        console.log("-form_destino_interno");
    }

    console.log("correcto :"+correcto);

    if(correcto){
        $("#cargando_pagina").show();

        $.ajax({
            type: "POST",
            url: default_server+"/json/tramites/externo",
            data: {
                archivo_id: (archivo_seleccion != null ? archivo_seleccion.id : 0),   
                documento_tipo_id: $("#documento_tipo_id").val(),
                numero: $("#numero").val(),
                remitente: $("#remitente").val(),
                folios: $("#folios").val(),
                asunto: $("#asunto").val(),
                observaciones: $("#observaciones").val(),
                anexos: get_anexos(),
                tipo_persona: $("#persona").val(),
                ruc: $("#ruc").val(),
                razon_social: $("#razon_social").val(),
                identidad_documento_id: $("#identidad_documento_id").val(),
                nro_documento: $("#nro_documento").val(),
                nombre: $("#nombre").val(),
                apaterno: $("#apaterno").val(),
                amaterno: $("#amaterno").val(),
                email: $("#email").val(),
                telefono: $("#telefono").val(),
                direccion: $("#direccion").val(),
                procedimiento_id: $("#procedimiento_id").val(),
                d_dependencia_id: $("#d_dependencia_id").val(),                
                d_empleado_id: $("#d_empleado_id").val(),
                d_persona_id: $("#d_empleado_id").find(":selected").data("persona"),
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
    
}
