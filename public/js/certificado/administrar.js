var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_repositorios").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/repositorios",
            "type": "GET",
            "data": function ( d ) { },
            "error": default_error_handler        
        },
        "columns": [            
            { "data": "nombre",
                render: function ( data, type, full ) {                      
                    return '<div title="'+data+'">'+textoMax(data,30)+'</div>';
                }        
            },  
            { "data": "descripcion",
                render: function ( data, type, full ) {                      
                    return '<div title="'+data+'">'+textoMax(data,30)+'</div>';
                }        
            },  
            { "data": "archivos_count", "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {       
                    return data;
                }        
            },   
            { "data": "responsables_count", "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {       
                    return data;
                }        
            },  
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) { 
                        var res = '<div class="btn-list flex-nowrap">'+
                                    '<button class="btn btn-secondary btn-icon" onclick="responsables('+full.id+');" title="RESPONSABLES">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>'+
                                    '</button>'+
                                    '<button class="btn btn-white btn-icon" onclick="modificar('+full.id+');" title="MODIFICAR">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>'+
                                    '</button>'+
                                    '<button class="btn btn-danger btn-icon" onclick="eliminar('+full.id+');" title="ELIMINAR">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                    '</button>'+
                                '</div>';
                    return res;
                }
            }
        ],
        "dom": default_datatable_dom,
        "language": default_datatable_language,
        "initComplete" : default_datatable_buttons
    }); 
    
    $("#user_id").select2({
        dropdownParent: $('#responsable'),
        width: '100%',
        theme: 'bootstrap4',
        minimumInputLength: 4,
        language: "es",
        ajax: {
            url: default_server+"/json/users/buscar",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            data: function (term) {
                return term;
            },
            processResults: function (data) {
                return {
                  results: $.map(data, function(obj) {
                    return { 
                        id: obj.id, 
                        text: obj.nombre+' '+obj.apaterno+' '+obj.amaterno
                    };
                  })
                };
            }
        }
    });
});

function nuevo() 
{
    editItem = 0;
    $("#titulo_editar").html("Nuevo registro"); 
    limpiar('#form_editar');
    vaciar('#form_editar');    
    $("#editar").modal("show");
}

function modificar(iditem) 
{
    editItem = iditem;
    limpiar('#form_editar');
    vaciar('#form_editar');

    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {
        $("#nombre").val(objitem.nombre);
        $("#descripcion").val(safeText(objitem.descripcion));
        $("#titulo_editar").html("Modificar registro"); 
        $("#editar").modal("show");
    }
    else
        alert("No se encontro el item");          
}

function guardar() 
{
    if(validar('#form_editar'))
    {
        $("#editar").modal("hide");
        if(editItem == 0)//nuevo
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/repositorios",
                data: {
                    nombre: $("#nombre").val(),
                    descripcion: $("#descripcion").val()
                },
                success: function(result){  
                    alerta(result.message, true); 
                    tabla.ajax.reload();
                },
                error: function(error) {                
                    alerta(response_helper(error), false);      
                }
            });
        }
        else
        {
            $.ajax({
                type: "PUT",
                url: default_server+"/json/repositorios/"+editItem,
                data: {                   
                    nombre: $("#nombre").val(),
                    descripcion: $("#descripcion").val()
                },
                success: function(result){  
                    alerta(result.message, true); 
                    tabla.ajax.reload();
                },
                error: function(error) {                
                    alerta(response_helper(error), false);            
                }
            });
        }
    }    
}

function eliminar(iditem) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $.ajax({
            type: "DELETE",
            url: default_server+"/json/repositorios/"+iditem,        
            success: function(result){  
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);                       
            }
        });
    }
}

function responsables(iditem) {
    editItem = iditem;    
    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {
        $("#respositorio_users").val(objitem.nombre);     
        $("#user_id").val(0);   
        $("#user_id").trigger('change');
        limpiar("#form_responsable");
        $("#responsables_list").html("");
        $("#cargando_responsables").show();        
        $("#responsable").modal("show");

        $.ajax({
            type: "GET",
            url: default_server+"/json/repositorios/"+iditem+"/responsables",
            data: { },
            success: function(result){ 
                if(result.length > 0){
                    for (let i = 0; i < result.length; i++) {
                        $("#responsables_list").append(
                            '<div class="list-group-item bg-white">'+
                                '<div class="row align-items-center">'+
                                    '<div class="col text-truncate lh-1">'+
                                        result[i].user.nombre+' '+result[i].user.apaterno+' '+result[i].user.amaterno+
                                        '<small class="d-block text-muted text-truncate mt-n1">'+result[i].user.email+'</small>'+
                                    '</div>'+
                                    '<div class="col-auto">'+
                                        '<a href="javascript:void(0);" onclick="eliminar_responsable('+result[i].cert_repositorio_id+','+result[i].user_id+');" >'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                        '</a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    }                   
                } else {
                    $("#responsables_list").append('<div class="list-group-item bg-white">No se encontraron resultados</div>');
                }                   
            },
            error: function(error) {                
                alerta(response_helper(error), false);      
            },
            complete: function() {                
                $("#cargando_responsables").hide();   
            }
        });
    }
    else
        alert("No se encontro el item");
}

function guardar_asignar() {
    if(validar("#form_responsable")) {
        $('#responsable').modal("hide");        
        $.ajax({
            type: "POST",
            url: default_server+"/json/repositorios/responsables",
            data: {                    
                cert_repositorio_id: editItem,
                user_id: $("#user_id").val()
            },
            success: function(result){  
                alerta(result.message, true);   
                tabla.ajax.reload();
            },
            error: function(error) {                   
                alerta(response_helper(error), false);
            }
        });        
    }
}

function eliminar_responsable(repositorio_id, user_id) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $('#responsable').modal("hide");

        $.ajax({
            type: "DELETE",
            url: default_server+"/json/repositorios/responsables/eliminar",   
            data: {                   
                cert_repositorio_id: repositorio_id,
                user_id: user_id
            },     
            success: function(result){  
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);                       
            }
        });
    }
}