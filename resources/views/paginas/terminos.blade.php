@extends('layouts.blanco')

@section('titulo', 'Téminos y condiciones')

@section('contenido')
<div class="container-narrow py-4">
    <div class="text-center mb-4">        
        <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">        
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h3 class="card-title text-center">Términos y condiciones</h3>
            <div class="markdown">
                <ul>
                    <li>
                        <p class="text-justify">Los/las administrados/as verifican que los documentos e información presentada se encuentren de acuerdo a los requisitos señalados en el procedimiento administrativo, servicios o solicitudes; así como, estén conforme a lo indicado en el artículo 124 del TUO de la Ley N° 27444, y en el marco de las disposiciones internas vigentes. Asimismo, se encuentren debidamente registrados en la Plataforma Virtual de Atención a la Ciudadanía, procediendo luego a enviar el formulario correspondiente.</p>
                    </li>
                    <li>
                        <p class="text-justify">Para el ingreso de documentos a través de la Plataforma Virtual de Atención a la Ciudadanía previamente el/la administrado/a debe digitalizar, de manera completa y legible, la documentación que desee ingresar por la Plataforma Virtual de Atención a la Ciudadanía, incluyendo aquellos que sean requisitos indispensables para su presentación (peso estandarizado por cada documento como máximo de 15 MB.).</p>
                    </li>
                    <li>
                        <p class="text-justify">La Plataforma Virtual de Atención a la Ciudadanía del Ministerio de Cultura, está habilitada las veinticuatro (24) horas del día, durante los siete (7) días de la semana. Sin embargo, la recepción de los documentos presentados entre las 00:00 horas y las 23:59 horas de un día hábil, se consideran presentados el mismo día hábil. Los documentos presentados los sábados, domingos y feriados o cualquier otro día inhábil, se consideran presentados al primer día hábil siguiente.</p>
                    </li>
                    <li>
                        <p class="text-justify">En cumplimiento de lo dispuesto en la Ley N° 29733, Ley de Protección de Datos Personales, desde el momento de su ingreso y/o utilización de la Plataforma Virtual de Atención a la Ciudadanía, los/las administrados/as dan expresamente su consentimiento para el tratamiento de los datos personales que por ellos sean facilitados o que se faciliten a través de la Plataforma Virtual de Atención a la Ciudadanía.</p>
                    </li>
                </ul>                
            </div>
        </div>
    </div>
</div>
@endsection
