var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_persona").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/personas",
            "type": "GET",
            "error": default_error_handler        
        },
        "columns": [    
            { "data": null, "orderable": false, "searchable": false,
                render: function ( data, type, full ) {              
                    if(full.tipo == 0)      //0:natural, 1:juridic  
                        return '<span class="badge bg-blue-lt">NATURAL</span>';
                    else
                        return '<span class="badge bg-purple-lt">JURÍDICA</span>';
                }        
            },        
            { "data": "nro_documento" , className: "w-1",
                render: function ( data, type, full ) {        
                    return '<small class="d-block text-muted lh-1">'+full.identidad_documento.abreviatura+'</small><div class="lh-1">'+data+'</div>';
                } 
            },
            { "data": "nombre",
                render: function ( data, type, full ) {                      
                    return full.nombre+' '+full.apaterno+' '+full.amaterno;
                }        
            },
            { "data": "apaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "amaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "correo" },
            { "data": "telefono" },
            { "data": null, "searchable": false, "orderable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {       
                    if((full.users_count+full.empleos_count+full.estudiantes_count) > 0)               
                        return '<a href="'+default_server+'/admin/sistema/persona/'+full.id+'/detalle" target="_blank" class="btn btn-ghost-primary">'+full.users_count+' / '+full.empleos_count+' / '+full.estudiantes_count+'</a>';
                    else
                        return '<a href="'+default_server+'/admin/sistema/persona/'+full.id+'/detalle" target="_blank" class="btn btn-ghost-secondary">'+full.users_count+' / '+full.empleos_count+' / '+full.estudiantes_count+'</a>';
                }        
            },                 
            { "data": "estado", "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                      
                    if(data == 1)
                        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>';
                    else
                        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="3" x2="21" y2="21" /><path d="M10.584 10.587a2 2 0 0 0 2.828 2.83" /><path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341" /></svg>';
                }        
            },  
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {   
                    var res = '<div class="btn-list flex-nowrap">'+                                
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
   
    window.Litepicker && (new Litepicker({
        format: "DD/MM/YYYY",
        element: document.getElementById('nacimiento'),
        buttonText: {
            previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>',
            nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>',
        },
    }));
  


    /*$("#dependencia_select").select2({        
        width: '100%',
        theme: 'bootstrap4'
    }).on('change', function() {
        tabla.ajax.reload();
    });

    $('#sede_id').on('change', function() {        
        var item = elementId($('#sede_id').val(), sedes);
        $('#dependencia_id').html('<option value="0">Seleccione...</option>');
        if(item != null) {
            for (let i = 0; i < item.dependencias.length; i++) {
                $('#dependencia_id').append('<option value="'+item.dependencias[i].id+'">'+item.dependencias[i].nombre+'</option>');
            }
        }  
        $("#dependencia_id").trigger('change');           
    });
    
    
    $("#dependencia_id").select2({
        dropdownParent: $('#modal_dependencia'),
        width: '100%',
        theme: 'bootstrap4'
    });*/
    

});


function nuevo() 
{
    editItem = 0;
    $("#titulo_editar").html("Nuevo registro"); 
    limpiar('#form_editar');
    vaciar('#form_editar');
    $('#estado').prop('checked', true);
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
        $("#identidad_documento_id").val(objitem.identidad_documento_id);
        $("#nro_documento").val(objitem.nro_documento);
        $("#nombre").val(objitem.nombre);
        $("#apaterno").val(objitem.apaterno);
        $("#amaterno").val(objitem.amaterno);
        $("#correo").val(safeText(objitem.correo));
        $("#telefono").val(safeText(objitem.telefono));
        $("#direccion").val(safeText(objitem.direccion));
        $("#nacimiento").val(dis_fecha(objitem.nacimiento));      
        $('#estado').prop('checked', (objitem.estado == 1));
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
                url: default_server+"/json/personas",
                data: {
                    identidad_documento_id: $("#identidad_documento_id").val(),
                    nro_documento: $("#nro_documento").val(),
                    nombre: $("#nombre").val(),
                    apaterno: $("#apaterno").val(),
                    amaterno: $("#amaterno").val(),     
                    correo: $("#correo").val(),
                    telefono: $("#telefono").val(),
                    direccion: $("#direccion").val(),       
                    nacimiento: db_fecha($("#nacimiento").val()),             
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
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
                url: default_server+"/json/personas/"+editItem,
                data: {                   
                    identidad_documento_id: $("#identidad_documento_id").val(),
                    nro_documento: $("#nro_documento").val(),
                    nombre: $("#nombre").val(),
                    apaterno: $("#apaterno").val(),
                    amaterno: $("#amaterno").val(),     
                    correo: $("#correo").val(),
                    telefono: $("#telefono").val(),
                    direccion: $("#direccion").val(),       
                    nacimiento: db_fecha($("#nacimiento").val()),             
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
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
            url: default_server+"/json/personas/"+iditem,        
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


function asignar(idItem) {

    editItem = idItem;
    limpiar('#form_dependencia');
    vaciar('#form_dependencia');

    var data = tabla.rows().data();
    var objitem = elementId(idItem, data);

    if(objitem != null)
    {        
        $("#personal").val(objitem.nombre+' '+objitem.apaterno+' '+objitem.amaterno);
        $("#sede_id").val(0);
        $('#dependencia_id').html('<option value="0">Seleccione...</option>');
        $("#dependencia_id").trigger('change');     
        $('#revocar_anterior').prop('checked', true);
        $("#modal_dependencia").modal("show");
    }
    else
        alert("No se encontro el item");      
}


function guardar_dependemncia() 
{
    if(validar('#form_dependencia'))
    {      
        $("#modal_dependencia").modal("hide");
       
        $.ajax({
            type: "POST",
            url: default_server+"/json/personal/asignar/dependencia",
            data: {                   
                personal_id: editItem,
                dependencia_id: $("#dependencia_id").val(),     
                cargo: $("#cargo").val(),
                revocar: ($("#revocar_anterior").is(':checked') ? 1 : 0 )
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


function revocar(idItem) { 
    var data = tabla.rows().data();
    var objitem = elementId(idItem, data);

    if(objitem != null)
    {
        $("#personal_revocar").val(objitem.nombre+' '+objitem.apaterno+' '+objitem.amaterno);
        $("#lista_asignaciones").html('');
        $("#revocar_loading").show();

        $.ajax({
            type: "POST",
            url: default_server+"/json/personal/asignaciones/"+idItem,
            success: function(result){  
                var datos = result.data;                
                if(datos.length > 0)
                {
                    for (let i = 0; i < datos.length; i++) {
                        $("#lista_asignaciones").append(
                        '<div class="list-group-item">'+
                            '<div class="row align-items-center">'+
                                '<div class="col text-truncate" title="'+datos[i].dependencia.nombre+'">'+
                                    '<small class="d-block text-muted text-truncate mt-n1">'+datos[i].cargo+'</small>'+
                                    datos[i].dependencia.sede.abreviatura+' | '+datos[i].dependencia.nombre+                                    
                                '</div>'+
                                '<div class="col-auto">'+
                                    '<a href="javscript:void(0);" onclick="eliminar_asignacion('+datos[i].id+')" class="list-group-item-actions" title="ELIMINAR">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                    '</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>');
                    }  
                }
                else
                {
                    $("#lista_asignaciones").html(
                    '<div class="list-group-item">'+
                        '<div class="row align-items-center">'+
                            '<div class="col text-muted">'+
                                'SIN REGISTROS'+                                
                            '</div>'+                            
                        '</div>'+
                    '</div>');
                }
     
            },
            error: function(error) {                
                alerta(response_helper(error), false);
            },
            complete: function() {                
                $("#revocar_loading").hide();                
            }
        });      

        $("#modal_revocar").modal("show");
    }
    else
        alert("No se encontro el item");     
}


function eliminar_asignacion(iditem) {
    if(confirm('Esta seguro que desea finalizar?'))
    {
        $("#modal_revocar").modal("hide");

        $.ajax({
            type: "POST",
            url: default_server+"/json/personal/asignacion/eliminar/"+iditem,        
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
