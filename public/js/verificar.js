
function enviar(e) {  
    if(!validar("#formulario")){
        e.preventDefault();
        return false;
    } else {
        $("#cargando_pagina").show();    
        return true; 
    }    
}