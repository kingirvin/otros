@extends('layouts.blanco')

@section('titulo', 'Declaración jurada')

@section('contenido')
<div class="container-narrow py-4">
    <div class="text-center mb-4">        
        <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">        
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h3 class="card-title text-center">Declaración jurada</h3>
            <div class="markdown">
                <p class="text-justify"><b>DECLARO BAJO JURAMENTO</b>, que los datos consignados en la presente solicitud responden a la verdad. En caso de resultar falsa la información que proporciono, me sujeto a los alcances de lo establecido en el artículo 411 del Código Penal concordante con el artículo 34.3 del Texto Único Ordenado de la Ley N° 27444, Ley del Procedimiento Administrativo General.</p>
                <h3>Aclaración sobre falsedad de la información declarada</h3>
                <p class="text-justify"><b>TUO de la Ley N° 27444 (numeral 34.3 del artículo 34)</b> “En caso de comprobar fraude o falsedad en la declaración, información o en la documentación presentada por el administrado, la entidad considerará no satisfecha la exigencia respectiva para todos sus efectos, procediendo a declarar la nulidad del acto administrativo sustentado en dicha declaración, información o documento; e imponer a quien haya empleado esa declaración, información o documento una multa en favor de la entidad de entre cinco (5) y diez (10) Unidades Impositivas Tributarias vigentes a la fecha de pago; y, además, si la conducta se adecua a los supuestos previstos en el Título XIX Delitos contra la Fe Pública del Código Penal, ésta deberá ser comunicada al Ministerio Público para que interponga la acción penal correspondiente.”</p>
            </div>
        </div>
    </div>
</div>
@endsection
