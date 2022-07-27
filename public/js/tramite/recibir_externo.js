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
