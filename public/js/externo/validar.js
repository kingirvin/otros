var allowSubmit = false;

function capcha_filled () {
    allowSubmit = true;
}

function capcha_expired () {
    allowSubmit = false;
}

function guardar_todo(form) {
    
    if(!validar('#form_principal'))
        return false;

    if(!allowSubmit) {    
        alerta("INGRESE EL CAPTCHA!", false);
        return false;
    }
    
    $("#cargando_pagina").show();    
}