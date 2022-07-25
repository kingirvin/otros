var destinos=[];

$( document ).ready(function() {

    $("#sede_id").on('change', function() {
        cargar_dependencias();
    });
    
    $("#dependencia_select").select2({
        dropdownParent: $('#destino_dependencia'),
        width: '100%',
        theme: 'bootstrap4'
    }).on('change', function() {
        cargar_personal();
    });
});

/**
 * SELECCIONAR DEPENDENCIA
 */
function nuevo_destino_dependencia() {    
    limpiar('#destino_dependencia');
    vaciar('#destino_dependencia');    
    $('#copia').prop('checked', false);    
    $("#sede_id").val($("#sede_id option:first").val());
    $("#sede_id").trigger('change');    
    $("#destino_dependencia").modal("show");
}

function cargar_dependencias() {
    $("#dependencia_loading").show();
    $("#btn_destino").prop('disabled', true);

    $.ajax({
        type: "GET",
        url: default_server+"/json/dependencias/"+$("#sede_id").val()+"/buscar",
        success: function(result){  
            var datos = result.data;
            $("#dependencia_select").html('<option value="0">Seleccione...</option>');
            for (let i = 0; i < datos.length; i++) {
                $("#dependencia_select").append('<option value="'+datos[i].id+'">'+datos[i].nombre+'</option>');
            }    
            $("#dependencia_select").trigger('change');       
        },
        error: function(error) {                
            alerta(response_helper(error), false);
        },
        complete: function() {                
            $("#dependencia_loading").hide();
            $("#btn_destino").prop('disabled', false);
        }
    });
}

function cargar_personal() {
    var dep_sel = $("#dependencia_select").val();
    if(dep_sel != 0){
        $("#dependencia_loading").show();
        $("#btn_destino").prop('disabled', true);

        $.ajax({
            type: "GET",
            url: default_server+"/json/empleados/"+$("#dependencia_select").val()+"/buscar",
            success: function(result){  
                var datos = result;
                $("#empleado_select").html('<option value="0" data-persona="0">Seleccione...</option>  ');
                for (let i = 0; i < datos.length; i++) {
                    $("#empleado_select").append('<option value="'+datos[i].id+'" data-persona="'+datos[i].persona_id+'">'+datos[i].persona.nombre+' '+datos[i].persona.apaterno+' '+datos[i].persona.amaterno+' | '+datos[i].cargo+'</option>');
                }                    
            },
            error: function(error) {                
                alerta(response_helper(error), false);
            },
            complete: function() {
                $("#dependencia_loading").hide();
                $("#btn_destino").prop('disabled', false);
            }
        });
    } else {
        $("#empleado_select").html('<option value="0" data-persona="0">Seleccione...</option>  ');
    }
}

function agregar_destino_interno() {
    if(validar("#interno"))
    {
        var dep = $("#dependencia_select").val();
        var emp = $("#empleado_select").val();

        if(!existe_destino(0, dep, emp, ""))
        {
            if(emp == 0){
                destinos.push({ 
                    tipo: 0,//0:interno
                    titulo: $("#dependencia_select option:selected").text(),
                    subtitulo: $("#sede_id option:selected").text(),
                    como_copia: ($("#copia").is(':checked') ? 1 : 0 ),
                    d_dependencia_id: $("#dependencia_select").val(),
                    d_empleado_id: 0,
                    d_persona_id: 0,
                    d_identidad_documento_id: 0,
                    d_nro_documento: "",
                    d_nombre: ""
                });
            } else {

                var pers = $("#empleado_select").find(':selected').data('persona');
                
                destinos.push({ 
                    tipo: 0,//0:interno
                    titulo: $("#empleado_select option:selected").text(),
                    subtitulo: $("#dependencia_select option:selected").text(),
                    como_copia: ($("#copia").is(':checked') ? 1 : 0 ),
                    d_dependencia_id: dep,
                    d_empleado_id: emp,
                    d_persona_id: pers,
                    d_identidad_documento_id: 0,
                    d_nro_documento: "",
                    d_nombre: ""
                });
            }            

            render_destinos();
            $("#destino_dependencia").modal("hide");
        }
        else
            alert("El destino ya ha sido seleccionado!");           
    }
}

function nuevo_destino_externo() {    
    limpiar('#destino_externo');
    vaciar('#destino_externo');
    $("#d_identidad_documento_id").val(0);     
    $("#destino_externo").modal("show");
}

function agregar_destino_externo() {
    if(validar("#externo"))
    {     
        if(!existe_destino(1, 0, 0, $("#d_nombre").val()))
        {      
            destinos.push({
                tipo: 1,//1:externo
                titulo: $("#d_nombre").val(),
                subtitulo: $("#d_nro_documento").val(),
                como_copia: 0,
                d_dependencia_id: 0,
                d_empleado_id: 0,
                d_persona_id: 0,
                d_identidad_documento_id: $("#d_identidad_documento_id").val(),
                d_nro_documento: $("#d_nro_documento").val(),
                d_nombre: $("#d_nombre").val()
            }); 
            
            render_destinos();
            $("#destino_externo").modal("hide");
        }
        else
            alert("El destino ya ha sido seleccionado!"); 
    }
}

function existe_destino(tipo, d_dependencia_id, d_empleado_id, d_nombre) {
    var existe = false;
    if(tipo == 0){//interno
        for (let i = 0; i < destinos.length; i++) {
            //si es la misma dependencia y el mismo empleado
            if(destinos[i].tipo == 0 && destinos[i].d_dependencia_id == d_dependencia_id && destinos[i].d_empleado_id == d_empleado_id){
                existe = true;
                break;
            }            
        }
    }

    if(tipo == 1){//externo
        for (let i = 0; i < destinos.length; i++) {
            if(destinos[i].tipo == 1 && destinos[i].d_nombre == d_nombre){
                existe = true;
                break;
            }            
        }
    }

    return existe;
}

function render_destinos() {
    $("#destinos").html("");
    for (let i = 0; i < destinos.length; i++) {

        if(destinos[i].tipo == 0){
            if(destinos[i].d_empleado_id == 0)
                var destino_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><line x1="9" y1="8" x2="10" y2="8" /><line x1="9" y1="12" x2="10" y2="12" /><line x1="9" y1="16" x2="10" y2="16" /><line x1="14" y1="8" x2="15" y2="8" /><line x1="14" y1="12" x2="15" y2="12" /><line x1="14" y1="16" x2="15" y2="16" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>';
            else
                var destino_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>';
        }
        else {
            var destino_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 12h5a2 2 0 0 1 0 4h-15l-3 -6h3l2 2h3l-2 -7h3z" transform="rotate(-15 12 12) translate(0 -1)" /><line x1="3" y1="21" x2="21" y2="21" /></svg>';
        } 
        
        //0:dependencia, 1:estudia, 2:externo        
        $("#destinos").append(
            '<div class="list-group-item">'+
                '<div class="row align-items-center">'+
                    '<div class="col-auto">'+
                        '<div class="text-muted">'+
                            destino_icon+                        
                        '</div>'+
                    '</div>'+
                    '<div class="col text-truncate">'+
                        destinos[i].titulo+
                        '<div class="text-truncate lh-1">'+
                            (destinos[i].como_copia == 1 ? '<span class="badge bg-indigo-lt">SOLO COPIA</span>&nbsp;' : '')+
                            '<small class="text-muted align-middle">'+destinos[i].subtitulo+'</small>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-auto">'+
                        '<a href="javascript:void(0);" onclick="eliminar_destino('+i+');" class="list-group-item-actions text-danger title">'+
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                        '</a>'+
                    '</div>'+
                '</div>'+
            '</div>'
        );               
    }
}

function eliminar_destino(index) {
    if(confirm("Esta seguro que desea remover el destino?"))
    {
        destinos.splice(index, 1);
        render_destinos();
    }
}
