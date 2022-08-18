var allowSubmit = false;

function capcha_filled () {
    allowSubmit = true;
}

function capcha_expired () {
    allowSubmit = false;
}


function guardar_todo(form) {    
    if(!validar('#form_documento') | !validad_legalidad('#form_legalidad'))
        return false;
        
    if(!validar_archivo("#archivo_subir")){
        alerta('El documento principal supera el tamaño máximo', false);
        return false;
    }

    if(!validar_archivo("#archivo_anexos")){
        alerta('Alguno de los anexos supera el tamaño máximo', false);
        return false;
    }

    if(!allowSubmit) {    
        alerta("INGRESE EL CAPTCHA!", false);
        return false;
    }

    $("#cargando_pagina").show();
}


function validar_archivo(s_archivo) {
    var res = true;
    var t_files = $(s_archivo)[0].files;
    if(t_files.length > 0)
    {
        for (let i = 0; i < t_files.length; i++) {
            if(t_files[i].size > size_maximo)   
                res = false;              
        }
    }
    return res;
}

function validad_legalidad(ident_form) {
    var resultado = true;
    limpiar(ident_form);
    $(ident_form+" .form-group").each(function() {
        var grupo = this;       
        $(grupo).find('input[type=checkbox]').each(function () {  
            var checkbox = $(this);
            if(checkbox.length) {
                if(!checkbox.is(':checked')) {
                    resultado=false;
                    $(checkbox).addClass('is-invalid');
                    $(grupo).find('.invalid-feedback').remove();
                    $(grupo).append('<div class="invalid-feedback">Este campo es obligatorio</div>');                   
                }
            }
        }); 
    });

    return resultado;
}

function limpiar(ident_form) {
    $(ident_form+" .form-group").each(function() {
        var l_input = $(this).find('input[type=checkbox]');
        l_input.removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
    });
}