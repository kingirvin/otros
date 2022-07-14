var fx_contenedor = document.getElementById("fx_contenedor");
var fx_hoja = document.getElementById("fx_hoja");
var fx_firma = document.getElementById("fx_firma");
var moviendo = false;

$( document ).ready(function() {

    set_pagina(0);
    set_apariencia(0);

    fx_hoja.addEventListener('mousemove', event => {        
        if(moviendo){ mover_a(event); }
    });

    fx_firma.addEventListener("mousedown", event => {
        moviendo = true;
        $( "#fx_firma" ).addClass("moviendo");
    });

    document.addEventListener("mouseup", event => {
        moviendo = false;
    });

    $("#num_pagina").on('change', function() {
        set_pagina(this.value);
    });
   
    $("#apariencia").on('change', function() {
        set_apariencia(this.value);
    });

    $("#enviar").prop('disabled', false);

});

function mover_a(event) {
    const relative_pos = get_relative(event);
    var new_left = relative_pos.x - (fx_firma.offsetWidth / 2);
    var new_top = relative_pos.y - (fx_firma.offsetHeight / 2);

    if(new_left < 0 ){
        new_left = 0;
    } else if (new_left > fx_hoja.offsetWidth - fx_firma.offsetWidth){
        new_left = fx_hoja.offsetWidth - fx_firma.offsetWidth;
    }

    if(new_top < 0 ){
        new_top = 0;
    } else if(new_top > fx_hoja.offsetHeight - fx_firma.offsetHeight){
        new_top = fx_hoja.offsetHeight - fx_firma.offsetHeight;
    }

    fx_firma.style.left = new_left + 'px';
    fx_firma.style.top = new_top + 'px';
}

function get_relative(event) {
    const pos = event.currentTarget.getBoundingClientRect();
    return {
        x: event.clientX - pos.left,
        y: event.clientY - pos.top
    };
}

function set_apariencia(index) {
    $("#fx_firma").width(firma_dimenciones[index].width * factor_mult);
    $("#fx_firma").height(firma_dimenciones[index].height  * factor_mult);
}

function set_pagina(index) {   
    $("#fx_hoja").width(firma_hojas[index].width * factor_mult);
    $("#fx_hoja").height(firma_hojas[index].height  * factor_mult);
    $("#fx_hoja").css('background-image', "url("+firma_hojas[index].miniatura+")");    
}

function enviar() {
    if(confirm("Esta seguro que desea firmar en esta posición?")){
        //obtenemos la posicion de la firma 
        var position = $("#fx_firma").position();

        datos_firma = {
            archivo_id: elArchivo,
            num_pagina: $("#num_pagina").val(),
            motivo: $("#motivo").val(),
            exacto: 1,//posicion exacta
            pos_pagina: (position.top / factor_mult)+'-'+(position.left / factor_mult),
            apariencia: $("#apariencia").val()
        };

        confirmar_firma();
    }
}

function accion_firma() {
    location.href = default_server+'/admin/tramite/archivos';
}
