/*

--datos para firma

var datos_firma = {
    archivo_id: 0,
    num_pagina: 0,
    motivo: 'Soy el autor del documento',
    exacto: 1,
    pos_pagina: '0-0',
    apariencia: 0
};

--accion despues de firma ok
function accion_firma() {}

*/

//lanza el proceso de firma
function confirmar_firma() {
    if(datos_firma.archivo_id != 0) {
        if($('#firmar').length) {
            $("#firmar").modal("hide");
        }    
        initInvoker('W');
    }         
    else
        alerta("Seleccione un archivo", false);    
}

//obtiene los argumentos en base64 y lo guarda en el input #argumentos
function obtenerArgumentos() {    
    $.ajax({
        type: "GET",
        url: default_server+"/json/firma/argumentos",
        data: {
            archivo_id: datos_firma.archivo_id,
            num_pagina: datos_firma.num_pagina,
            motivo: datos_firma.motivo,
            exacto: datos_firma.exacto,
            apariencia: datos_firma.apariencia,
            pos_pagina: datos_firma.pos_pagina
         },
        success: function(result){  
            document.getElementById("argumentos").value = result;
			getArguments();
        },
        error: function(error) {                
            alerta(response_helper(error), false);                
        },
        complete: function() {              
        }
    });
}

window.addEventListener('getArguments', function (e) {								
    type = e.detail;
    obtenerArgumentos();
});

//inicia el envio de los argumentos
function getArguments(){	
    arg = document.getElementById("argumentos").value;				
    dispatchEventClient('sendArguments', arg);																
}

//si resulto correcto
window.addEventListener('invokerOk', function (e) { 
    type = e.detail;       
    alerta("Documento firmado correctamente.", true);
    //funcion por defecto
    accion_firma();
});

//si se cancelo la firma
window.addEventListener('invokerCancel', function (e) {    
    alerta("El proceso de firma digital fue cancelado.", false);
});
