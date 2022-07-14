
$( document ).ready(function() {

    if (document.getElementById('check_Terrilyn')) {
        window.Litepicker && (new Litepicker({
            format: "DD/MM/YYYY",
            element: document.getElementById('m_nacimiento'),
            lang: "es-ES",
            //parentEl: document.getElementById('editar'),
            buttonText: {
                previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>',
                nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>',
            },
        }));
    }
    
});

function modificar_password() 
{
    limpiar('#renovar');
    $("#p_password_old").val("");
    $("#p_password").val("");
    $("#p_password_confirmation").val("");
    $("#renovar").modal("show");      
}


function guardar_password() {
    if(validar('#form_renovar'))
    {
        $("#renovar").modal("hide");  
        $("#cargando_pagina").show();

        $.ajax({
            type: "POST",
            url: default_server+"/json/users/password/renovar",
            data: {      
                password_old: $("#p_password_old").val(),
                password: $("#p_password").val(),
                password_confirmation: $("#p_password_confirmation").val() 
            },
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


function modificar() 
{
    limpiar('#form_modificar');
    vaciar('#form_modificar');

    if(elUser != null)
    {        
        $("#m_tipo_documento").val(elUser.tipo_documento);         
        $("#m_nro_documento").val(elUser.nro_documento);     
        $("#m_nombre").val(elUser.nombre);         
        $("#m_nacimiento").val(dis_fecha(elUser.nacimiento));     
        $("#m_apaterno").val(elUser.apaterno); 
        $("#m_amaterno").val(elUser.amaterno);
        $("#m_email").val(elUser.email);     
        $("#m_telefono").val(elUser.telefono);   
        $("#m_direccion").val(elUser.direccion);        
        $('#estado').prop('checked', (elUser.estado == 1));
        $("#modificar").modal("show");
    }
    else
        alert("No se encontro el item");
}

function guardar_modificar() 
{
    if(validar('#form_modificar'))
    {
        $("#modificar").modal("hide");
        $("#cargando_pagina").show();
       
        $.ajax({
            type: "PUT",
            url: default_server+"/json/users/datos/actualizar",
            data: {                   
                tipo_documento: $("#m_tipo_documento").val(), 
                nro_documento: $("#m_nro_documento").val(),                  
                nombre: $("#m_nombre").val(), 
                nacimiento: db_fecha($("#m_nacimiento").val()), 
                apaterno: $("#m_apaterno").val(), 
                amaterno: $("#m_amaterno").val(), 
                telefono: $("#m_telefono").val(), 
                direccion: $("#m_direccion").val()
            },
            success: function(result){  
                alerta(result.message, true); 
                location.reload();
            },
            error: function(error) {                
                alerta(response_helper(error), false);    
                $("#cargando_pagina").hide();        
            }
        });
    
    }    
}