var allowSubmit = false;

function capcha_filled () {
    allowSubmit = true;
}

function capcha_expired () {
    allowSubmit = false;
}

function cambio_persona(item) {
    if($(item).val() == 1){
        $("#es_juridica").removeClass("oculto");
    } else {
        $("#es_juridica").addClass("oculto");
    }
}

function registro(e) {  

    var persona = $("#persona").val();
    var correcto = true;

    if(persona == 1){
        if(!validar("#es_juridica")){
            correcto = false;
        }
    }

    if(!validar("#es_natural")){
        correcto = false;
    }

    if(!allowSubmit) {    
        alerta("INGRESE EL CAPTCHA!", false);
        correcto = false;
    }

    if(!correcto){
        e.preventDefault();
        return false;
    } else {
        $("#cargando_pagina").show();    
        return true; 
    }    
}