var editItem = null;

$( document ).ready(function() {
    $("#dependencia_id").select2({   
        dropdownParent: $('#editar'),     
        width: '100%',
        theme: 'bootstrap4',
    }); 

    render();
});

function nuevo() {
    editItem = null;
    limpiar('#form_editar');
    vaciar('#form_editar');
    $("#dependencia_id").val(0);   
    $("#dependencia_id").trigger('change'); 
    $('#estado').prop('checked', true);
    $("#titulo_editar").html("Nuevo registro"); 
    $("#editar").modal("show");
}

function modificar(index) {
    editItem = index;
    limpiar('#form_editar');
    vaciar('#form_editar');
    $("#titulo_editar").html("Modificar registro"); 
    $("#dependencia_id").val(losPasos[editItem].dependencia_id);   
    $("#dependencia_id").trigger('change'); 
    $("#accion").val(losPasos[editItem].accion);  
    $("#descripcion").val(safeText(losPasos[editItem].descripcion));  
    $("#plazo_atencion").val(losPasos[editItem].plazo_atencion);
    $("#plazo_subsanacion").val(safeText(losPasos[editItem].plazo_subsanacion));
    $('#estado').prop('checked', (losPasos[editItem].estado == 1));
    $("#editar").modal("show");

}

function agregar() {
    if(validar("#form_editar")){
        var dependencia_data = $("#dependencia_id").select2('data');
        if(editItem == null){            
            losPasos.push({
                id: 0,
                procedimiento_id: elProcedimiento,
                dependencia_id: $("#dependencia_id").val(),
                dependencia: {
                    id: dependencia_data[0].id,
                    nombre: dependencia_data[0].text
                },
                accion: $("#accion").val(),
                descripcion: $("#descripcion").val(),
                plazo_atencion: $("#plazo_atencion").val(),
                plazo_subsanacion: $("#plazo_subsanacion").val(),
                estado: ($("#estado").is(':checked') ? 1 : 0 )
            });
        }
        else {
            losPasos[editItem].dependencia_id = $("#dependencia_id").val();
            losPasos[editItem].dependencia.id = dependencia_data[0].id;
            losPasos[editItem].dependencia.nombre = dependencia_data[0].text;
            losPasos[editItem].accion = $("#accion").val();
            losPasos[editItem].descripcion = $("#descripcion").val();
            losPasos[editItem].plazo_atencion = $("#plazo_atencion").val();
            losPasos[editItem].plazo_subsanacion = $("#plazo_subsanacion").val();
            losPasos[editItem].estado = ($("#estado").is(':checked') ? 1 : 0 );
        }

        render();
        $("#editar").modal("hide");
    }
    
}

function render() {
    $("#lista_pasos").html('');
    var plazos = 0;

    if(losPasos.length > 0){
        for (let i = 0; i < losPasos.length; i++) {
            $("#lista_pasos").append(
                '<div class="list-group-item">'+
                    '<div class="row align-items-center">'+    
                        '<div class="col-auto"><span class="badge badge-pill '+(losPasos[i].estado == 1 ? 'bg-blue':'')+'">'+(i + 1)+'</span></div> '+
                        '<div class="col-auto">'+                        
                            '<span class="avatar" title="PLAZO EN DIAS">'+losPasos[i].plazo_atencion+'</span>'+                       
                        '</div>'+
                        '<div class="col text-truncate">'+
                            '<span class="text-body d-block" title="'+losPasos[i].accion+'">'+textoMax(losPasos[i].accion,60)+'</span>'+
                            '<small class="d-block text-muted text-truncate mt-n1" title="'+losPasos[i].dependencia.nombre+'">'+textoMax(losPasos[i].dependencia.nombre,60)+'</small>'+
                        '</div>'+
                        '<div class="col-auto pe-3 border-end">'+
                            '<div class="d-flex flex-column" style="margin: -5px 0 -5px 0;">'+
                                '<a href="javascript:void(0);" onclick="subir('+i+');" class="d-block pb-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 15 12 9 18 15" /></svg></a>'+
                                '<a href="javascript:void(0);" onclick="bajar('+i+');" class="d-block pt-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 9 12 15 18 9" /></svg></a>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-auto ps-3">'+
                            '<a href="javascript:void(0);" onclick="modificar('+i+');" class="list-group-item-actions show">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-dark" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>'+
                            '</a>'+
                        '</div>'+
                        '<div class="col-auto">'+
                            '<a href="javascript:void(0);" onclick="eliminar('+i+');" class="list-group-item-actions show">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                            '</a>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );

            plazos += parseInt(losPasos[i].plazo_atencion);
        }
    }
    else {
        $("#lista_pasos").append('<div class="list-group-item bg-muted-lt">No se encontraron registros</div>');
    }

    $("#plazo_calculado").html(plazos);
}

function subir(index) {    
    if(index > 0){
        var dato = losPasos.splice(index, 1)[0];
        losPasos.splice(index - 1, 0, dato);
        render();
    }
}

function bajar(index) { 
     if(index < losPasos.length - 1){
        var dato = losPasos.splice(index, 1)[0];
        losPasos.splice(index + 1, 0, dato);
        render();
    }
}

function eliminar(index) {
    if(confirm("Esta seguro que desea eliminar?")) {
        losPasos.splice(index, 1);
        render();
    }    
}

function guardar() {
    if(confirm("Esta seguro que desea guardar?")) {
        $("#cargando_pagina").show(); 

        $.ajax({
            type: "POST",
            url: default_server+'/json/procedimientos/'+elProcedimiento+'/pasos',
            data: { pasos: losPasos },
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