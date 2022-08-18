<style>  
    @page  
    {
        size: portrait;
        margin: 10mm 5mm 10mm 5mm; 
    }

    body
    {
        font-family: Arial, Helvetica, sans-serif; 
    }

    .pagina
    {
        padding: .5cm 1cm;
    }

    .tabla
    {
        display: table;
        width: 100%;        
    }

    .celda
    {
        display: table-cell;
        vertical-align: middle;        
    }

    .w-1
    {
        width: 1px;
    }

    .w-350
    {
        width: 350px;
    }

    .titulo
    {
        font-size: 20px;        
        font-weight: bolder;
        margin: 0;
        line-height: 1;
    }

    .subtitulo
    {
        font-size: 16px;        
        font-weight: bold;
        line-height: 1;
        margin: 0;
    }

    .normal
    {
        font-size: 12px;        
        font-weight: normal;
    }

    .muted
    {
        font-size: 12px;        
        font-weight: normal;
        color: #9f9f9f;
    }

    .t-center
    {
        text-align: center;
    }

    .t-right
    {
        text-align: right;
    } 

    .t-left
    {
        text-align: left;
    }

    .mb-5
    {
        margin-bottom: 5px;
    }

    .mb-10
    {
        margin-bottom: 10px;
    }

    .mb-15
    {
        margin-bottom: 15px;
    }

    .pr-5
    {
        padding-right: 5px;
    }

    table
    {        
        border-collapse: collapse;
        border-spacing: 0; 
        font-size: 12px;
        width: 100%;
    }

    td, th {
        border:1px solid black;
        padding: 5px;
    }

    th {
        background: #e2e2e2;
    }  
   
    .t-w
    {
        white-space: nowrap;
    }

    .final
    {
        display: block;
        border-bottom: 1px dashed black;
    }
    
    @media print {
        table {
            border-collapse: collapse;
            border-spacing: 0; 
           /* border-collapse: unset;*/
        }
    }

</style>
<div class="pagina">
    <div class="tabla mb-10">
        <div class="celda w-1 pr-5">
            <img src="/img/escudo_unamad.png" alt="" width="70">
        </div>
        <div class="celda">
            <div class="subtitulo t-left">UNIVERSIDAD NACIONAL DE MADRE DE DIOS</div>
            <div class="normal t-left">JR. CUSCO N° 350 - PUERTO MALDONADO</div>
            <div class="normal t-left mb-0">“AÑO DEL BICENTENARIO DEL PERÚ: 200 AÑOS DE INDEPENDENCIA”</div>                    
        </div>        
    </div>
    <div class="tabla mb-15">
        <div class="celda">
            <div class="titulo t-center">@yield('titulo')</div>
        </div>
    </div>
    <div>
        @yield('contenido') 

         
        <div class="muted mb-5 t-right">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y ') }}</div>
        <div class="final"></div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function() { window.print(); }
    window.onafterprint = function(){ window.close(); }
</script>



