$( document ).ready(function() {

    $("#presentar_id").select2({        
        width: '100%',
        theme: 'bootstrap4',
    });

    $("#atender_id").select2({        
        width: '100%',
        theme: 'bootstrap4',
    });    
});

function guardar() {
    if(confirm("Esta seguro que desea guardar?")){
        if(validar("#form_procedimiento")) {
            $("#cargando_pagina").show();
            $.ajax({
                type: (elProcedimiento != 0 ? "PUT" : "POST"),
                url: default_server+(elProcedimiento != 0 ? '/json/procedimientos/'+elProcedimiento : '/json/procedimientos'),
                data: {
                    tipo: $("#tipo").val(),
                    codigo: $("#codigo").val(),
                    titulo: $("#titulo").val(),
                    descripcion: $("#descripcion").val(),
                    requisitos: $("#requisitos").val(),
                    normatividad: $("#normatividad").val(),
                    presentar_id: $("#presentar_id").val(),
                    presentar_modalidad: ($("#presentar_modalidad").is(':checked') ? 1 : 0 ),
                    pago_monto: $("#pago_monto").val(),
                    pago_entidad: $("#pago_entidad").val(),
                    pago_codigo: $("#pago_codigo").val(),
                    plazo: $("#plazo").val(),
                    calificacion: $("#calificacion").val(),
                    atender_id: safeSelect($("#atender_id").val()),
                    atender_modalidad: ($("#atender_modalidad").is(':checked') ? 1 : 0 ),            
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){  
                    alerta(result.message, true); 
                    location.href = default_server+'/admin/sistema/documental/procedimientos';
                },
                error: function(error) {                
                    alerta(response_helper(error), false);        
                },
                complete: function() {                
                    $("#cargando_pagina").hide();   
                }
            });
        }  
    }  
}