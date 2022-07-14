
function anular_movimiento(idmov) {
    if(confirm("Esta seguro que desea eliminar?"))
    {    
        $("#cargando_pagina").show();
        $.ajax({
            type: "DELETE",
            url: default_server+"/json/movimientos/"+idmov+"/derivacion/anular/",
            data: {},
            success: function(result){  
                alerta(result.message, true); 
                window.location.reload();
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