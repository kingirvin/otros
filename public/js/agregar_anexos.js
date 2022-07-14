var cargando_documento = false;//se esta en proceso de carga
var xhr_documento = null;//request
var anexos = [];

$(document).ready(function() {
    $("#nuevo_anexo").on('change', function () {        
        if(this.files.length > 0)
        {
            if(this.files[0].size < size_maximo)                   
                cargar_anexo(this.files[0]);               
            else
                alerta("El tamaño del archivo no debe ser mayor a "+format_getSize(size_maximo) ,false);
        }        
    });
});

function agregar_anexo() {    
    $("#nuevo_anexo").click();
}

function cargar_anexo(archivo_nuevo) {

    var fm_ahora = new Date();
        var fm_pre_codigo = ""+
        fm_ahora.getFullYear()+
        (fm_ahora.getMonth()+1)+
        fm_ahora.getDate()+
        fm_ahora.getHours()+
        fm_ahora.getMinutes()+
        fm_ahora.getSeconds()+
        fm_ahora.getMilliseconds();

    if(anexos.length == 0)//si no hay archivos
        $("#lista_anexos").html("");

    $("#lista_anexos").append(
        '<div id="fm_file_'+fm_pre_codigo+'" class="list-group-item">'+
            '<div class="row align-items-center">'+
                '<div class="col text-truncate">'+
                    archivo_nuevo.name+
                    '<div class="mt-n1">'+
                        '<small class="text-muted">'+format_getSize(archivo_nuevo.size,2)+'</small> | <small id="fm_status_'+fm_pre_codigo+'" class="text-muted">0% Cargando...</small>'+
                    '</div>'+
                '</div>'+
                '<div id="fm_cancelar_'+fm_pre_codigo+'" class="col-auto">'+
                    '<a href="javascript:void(0);" onclick="fm_cancelar('+fm_pre_codigo+');" class="list-group-item-actions" title="CANCELAR">'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>'+
                    '</a>'+
                '</div>'+
            '</div>'+
            '<div class="progress mt-2">'+
                '<div id="fm_progress_'+fm_pre_codigo+'" class="progress-bar bg-green" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">'+
                    '<span class="visually-hidden">0% Complete</span>'+
                '</div>'+
            '</div>'+
        '</div>'
    );

    //formulario
    var formData = new FormData();
    formData.append('motivo', 0);        
    formData.append('nombre', archivo_nuevo.name);   
    formData.append('carpeta_id', 0); 
    formData.append('dependencia_id', 0);
    formData.append("archivo_subir", archivo_nuevo);
    
    //xml request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", default_server+'/json/archivos');
    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

    //en progreso
    xhr.upload.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
            var percentComplete = Math.round(evt.loaded / evt.total * 100);   
            $("#fm_progress_"+fm_pre_codigo).css('width', percentComplete+'%').attr('aria-valuenow', percentComplete); 
            $("#fm_status_"+fm_pre_codigo).html(percentComplete+'% Cargando...');           
        }
    }, false);

    //cancelado
    xhr.upload.addEventListener("abort", function(evt){        
        $("#fm_progress_"+fm_pre_codigo).closest(".progress").addClass("d-none");
        $("#fm_cancelar_"+fm_pre_codigo).html(
        '<a href="javascript:void(0);" onclick="fm_remover('+fm_pre_codigo+');" class="list-group-item-actions" title="REMOVER">'+
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'+
        '</a>');
        $("#fm_status_"+fm_pre_codigo).html('Cancelado');
    }, false);

    //finalizado
    xhr.onreadystatechange = function (evt) {
        if (xhr.readyState == 4) {
            $("#fm_progress_"+fm_pre_codigo).closest(".progress").addClass("d-none");

            if(xhr.status == 200)//correcto
            {
                var respuesta = JSON.parse(xhr.response);
                var item_index = fm_index(fm_pre_codigo);
                anexos[item_index].id = respuesta.archivo.id;                                
                $("#fm_cancelar_"+fm_pre_codigo).html(
                '<a href="javascript:void(0);" onclick="fm_eliminar('+respuesta.archivo.id+','+fm_pre_codigo+');" class="list-group-item-actions" title="ELIMINAR">'+
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'+
                '</a>');
                $("#fm_status_"+fm_pre_codigo).html('Cargado');                                     
            }  
            else if(xhr.status == 0) {}// abort     
            else//error
            {     
                $("#fm_cancelar_"+fm_pre_codigo).html(
                '<a href="javascript:void(0);" onclick="fm_remover('+fm_pre_codigo+');" class="list-group-item-actions" title="REMOVER">'+
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'+
                '</a>');
                $("#fm_status_"+fm_pre_codigo).html('Error');

                if(xhr.status==413)
                    alerta('Error: El Archivo a excedido el tamaÃ±o mÃ¡ximo!',false);
                else
                    alerta('Error: '+response_helper(xhr.response),false); 
            }
        }
    };              
    
    xhr.send(formData); 

    anexos.push({
        codigo: fm_pre_codigo,
        id: 0,
        nombre: archivo_nuevo.name,
        formato: '',
        ruta: '',
        size: archivo_nuevo.size,
        xhr: xhr
    });

}

function fm_cancelar(codigo) 
{
    for (var i = 0; i < anexos.length; i++) {
        if(anexos[i].codigo==codigo)
        {
            anexos[i].xhr.abort();
            break;
        }
    }    
}

function fm_index(codigo) {
    var res = null;
    for (let i = 0; i < anexos.length; i++) {
        if(anexos[i].codigo==codigo)
        {
            res = i;
            break;
        }        
    }
    return res;
}

function fm_remover(codigo) 
{
    var index_temp = fm_index(codigo);
    $("#fm_file_"+codigo).remove();
    anexos.splice(index_temp, 1);

    if(anexos.length == 0)
        $("#lista_anexos").html('<div class="list-group-item"><div class="text-muted">Agregar archivos anexos al documento</div></div>');
}

function fm_eliminar(ida, codigo) 
{
    if(confirm("Esta seguro que desea eliminar el archivo?"))
    {
        $(".wrapper").scrollTop();
        $("#cargando_pagina").show();
        $.ajax({
            type: "DELETE",
            url: default_server+"/json/archivos/"+ida,
            data: { },
            success: function(result){  
                alerta(result.message, true); 
                var index_temp = fm_index(codigo);
                $("#fm_file_"+codigo).remove();
                anexos.splice(index_temp, 1);

                if(anexos.length == 0)
                    $("#lista_anexos").html('<div class="list-group-item"><div class="text-muted">Agregar archivos anexos al documento</div></div>');
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

function fm_vaciar_anexos() {
    anexos = [];
    $("#lista_anexos").html('<div class="list-group-item"><div class="text-muted">Agregar archivos anexos al documento</div></div>');
}

function get_anexos() {
    var temp = [];
    for (let i = 0; i < anexos.length; i++) {
        temp.push({
            id: anexos[i].id,        
            nombre: anexos[i].nombre
        });        
    }
    return temp;
}


