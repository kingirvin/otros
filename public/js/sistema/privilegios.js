function guardar() {
    if(confirm("Esta seguro que desea guardar?")) {
        $("#cargando_pagina").show();   
        
        var submodulos = [];
        $("#lista_modulos .form-check-input").each(function() {
            if($(this).is(':checked')){
                submodulos.push({
                    id: $(this).attr('id')
                }); 
            }        
        });

        $.ajax({
            type: "POST",
            url: default_server+'/json/roles/'+elRol+'/privilegios',
            data: { submodulos: submodulos },
            success: function(result){  
                alerta(result.message, true); 
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