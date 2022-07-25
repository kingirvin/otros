function cambio_persona(item) {
    if($(item).val() == 1){
        $("#es_juridica").removeClass("oculto");
    } else {
        $("#es_juridica").addClass("oculto");
    }
}
