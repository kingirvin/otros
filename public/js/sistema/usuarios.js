var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_usuario").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/users",
            "type": "GET",
            "data": function ( d ) {
                
            },
            "error": default_error_handler        
        },
        "columns": [
            { "data": null, "orderable": false, "searchable": false,
                render: function ( data, type, full ) {  
                    if(full.tipo == 1)//1:interno, 0:externo            
                        return '<span class="badge bg-green-lt">INTERNO</span>';
                    else
                        return '<span class="badge bg-yellow-lt">EXTERNO</span>';
                }        
            },
            { "data": "nro_documento",
                render: function ( data, type, full ) {                      
                    return '<small class="d-block text-muted lh-1">'+full.identidad_documento.abreviatura+'</small><div class="lh-1">'+data+'</div>';
                }        
            },
            { "data": "nombre",
                render: function ( data, type, full ) {                      
                    return '<span>'+full.nombre+' '+full.apaterno+' '+full.amaterno+'</span>';
                }        
            },
            { "data": "apaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "amaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "rol.nombre" },
            { "data": "email" },                    
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
                                '<button class="btn btn-white btn-icon" onclick="cambiar_p('+full.id+');" title="CAMBIAR CONTRASEÑA">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>'+
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

    $("#persona_id").select2({
        dropdownParent: $('#editar'),
        width: '100%',
        minimumInputLength: 4,
        language: "es",
        theme: 'bootstrap4',
        ajax: {
            url: default_server+"/json/personas/buscar",
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
                        text: obj.nombre+' '+obj.apaterno+' '+obj.amaterno,
                        email: obj.correo
                    };
                  })
                };
            }
        }
    }).on('select2:selecting', function (e) {
        var data = e.params.args.data;
        $("#email").val(data.email);
    });
    
});

function nuevo() 
{
    editItem = 0;
    limpiar('#form_editar');
    vaciar('#form_editar');
    $("#persona_id").val(0);   
    $("#persona_id").trigger('change'); 
    $("#rol_id").val(0);    
    $("#editar").modal("show");
}

function modificar(iditem) 
{
    editItem = iditem;
    limpiar('#form_modificar');
    vaciar('#form_modificar');

    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {        
        $("#m_rol_id").val(objitem.rol_id);  
        $("#m_email").val(objitem.email);             
        $('#estado').prop('checked', (objitem.estado == 1));  
        $("#modificar").modal("show");
    }
    else
        alert("No se encontro el item");
}

function cambiar_p(iditem) 
{
    editItem = iditem;
    limpiar('#form_password');
    vaciar('#form_password');

    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {        
        $("#p_email").val(objitem.email);             
        $("#m_password").modal("show");
    }
    else
        alert("No se encontro el item");
}


function guardar_nuevo() 
{
    if(validar('#form_editar'))
    {
        $("#editar").modal("hide");       
        $.ajax({
            type: "POST",
            url: default_server+"/json/users",
            data: {
                persona_id: $("#persona_id").val(),
                rol_id: $("#rol_id").val(), 
                email: $("#email").val(), 
                password: $("#password").val(),
                password_confirmation: $("#password_confirmed").val()
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


function guardar_modificar() 
{
    if(validar('#form_modificar'))
    {
        $("#modificar").modal("hide");
       
        $.ajax({
            type: "PUT",
            url: default_server+"/json/users/"+editItem,
            data: {                   
                rol_id: $("#m_rol_id").val(),
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

function guardar_password() {
    if(validar('#form_password'))
    {
        $("#m_password").modal("hide");
       
        $.ajax({
            type: "PUT",
            url: default_server+'/json/users/'+editItem+'/password',
            data: {                   
                password: $("#pchange").val(),                  
                password_confirmation: $("#pchange_confirmed").val(),  
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
