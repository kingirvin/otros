
$( document ).ready(function() {
    evaluar_tiempos();
});

function evaluar_tiempos() {
    var ajaxTime= new Date().getTime();
    $("#cargando_evaluar").show();

    $.ajax({
        type: "GET",
        url: default_server+"/json/personas/probar",
        data: { },
        success: function(result){ 
            var ajax_time = (new Date().getTime() - ajaxTime) / 1000;//(endDate.getTime() - startDate.getTime()) / 1000;
            var php_time = result.time;            
            $("#ajax_time").html(parseFloat(ajax_time).toFixed(5)+"s");
            $("#php_time").html(parseFloat(php_time).toFixed(5)+"s");           
        },
        error: function(error) {                
            alerta(response_helper(error), false);      
        },
        complete: function() {                
            $("#cargando_evaluar").hide();   
        }
    });
}