/*

IGNACIONES
    Route::get('asignaciones/{id}', [App\Http\Controllers\Api\AsignacionController::class, 'listar']);
    Route::post('asignaciones',  [App\Http\Controllers\Api\AsignacionController::class, 'nuevo'])->middleware('submoduloapi:RECPDOC');
    Route::put('asignaciones/{id}', [App\Http\Controllers\Api\AsignacionController::class, 'modificar'])->middleware('submoduloapi:RECPDOC');
    Route::put('asignaciones/{id}/estado', [App\Http\Controllers\Api\AsignacionController::class, 'estado'])->middleware('submoduloapi:RECPDOC');  
    Route::delete('asignaciones/{id}', [App\H



*/

var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_asignaciones").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/asignaciones/"+elMovimiento,
            "type": "GET",
            "data": function ( d ) { },
            "error": default_error_handler        
        },
        "columns": [            
            { "data": "created_at", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {                      
                    return '<div>'+dis_fecha(data)+'<small class="d-block lh-1">'+dis_solo_hora(data)+' h</small></div>';
                }
            },
            { "data": "persona.nombre",
                render: function ( data, type, full ) {                      
                    return '<div>'+full.persona.nombre+' '+full.persona.apaterno+' '+full.persona.amaterno+'<small class="d-block lh-1 text-muted">'+full.empleado.cargo+'</small></div>';
                }        
            },
            { "data": "persona.apaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "persona.amaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "detalles", "orderable": false,
                render: function ( data, type, full ) {                      
                    return '<div>'+full.accion.nombre+'<small class="d-block lh-1 text-muted" title="'+data+'">'+textoMax(data,30)+'</small></div>';
                }        
            },
            { "data": "estado", "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                      
                    if(data == 0)
                        return '<span class="badge bg-secondary">PENDIENTE</span>';
                    else if(data == 1)
                        return '<span class="badge bg-blue">EN PROCESO</span>';
                    else
                        return '<span class="badge bg-green">FINALIZADO</span>';
                }        
            },  
            { "data": "user.siglas", "orderable": false, "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                   
                    return '<span title="'+full.user.nombre+' '+full.user.apaterno+' '+full.user.amaterno+'" class="avatar">'+data+'</span>';;
                }        
            },
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {   
                    var res = '<div class="btn-list flex-nowrap">'+
                                '<a href="'+default_server+'/admin/sistema/accesos/roles/'+full.id+'/privilegios" class="btn btn-info btn-icon" title="PRIVILEGIOS">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 11 12 14 20 6" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>'+
                                '</a>'+
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

    $("#empleado_id").select2({
        dropdownParent: $('#editar'),
        theme: 'bootstrap4',
        width: '100%',
    });

    $("#accion_id").select2({
        dropdownParent: $('#editar'),
        theme: 'bootstrap4',
        width: '100%',
    });
    
});

function nuevo() 
{
    editItem = 0;
    $("#titulo_editar").html("Nuevo registro"); 
    limpiar('#form_editar');
    //vaciar('#form_editar');
    $("#detalles").val("");
    $("#empleado_id").val(0);   
    $("#empleado_id").trigger('change'); 
    $("#accion_id").val(0);   
    $("#accion_id").trigger('change');
    $("#editar").modal("show");
}

function modificar(iditem) 
{
    editItem = iditem;
    limpiar('#form_editar');
    //vaciar('#form_editar');

    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {        
        $("#empleado_id").val(objitem.empleado_id);   
        $("#empleado_id").trigger('change');
        $("#accion_id").val(objitem.accion_id);
        $("#accion_id").trigger('change');
        $("#detalles").val(objitem.detalles);
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
                url: default_server+"/json/asignaciones",
                data: {
                    movimiento_id: elMovimiento,
                    empleado_id: $("#empleado_id").val(),
                    accion_id: $("#accion_id").val(),                    
                    detalles: $("#detalles").val()
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
                url: default_server+"/json/asignaciones/"+editItem,
                data: {                   
                    empleado_id: $("#empleado_id").val(),
                    accion_id: $("#accion_id").val(),                    
                    detalles: $("#detalles").val()
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
            url: default_server+"/json/asignaciones/"+iditem,        
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