@extends('layouts.print')
@section('titulo', 'HOJA DE TRÁMITE DOCUMENTARIO')
@section('contenido')
<table class="mb-15">
    <tbody>
        <tr>
            <th class="t-left w-1 t-w">CÓDIGO ÚNICO DE TRÁMITE</th>
            <td class="t-left">T-{{ $tramite->codigo }}</td>
            <th class="t-left w-1 t-w">FECHA DE REGISTRO</th>
            <td class="t-left">{{ $tramite->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th class="t-left w-1 t-w" >PROCEDIMIENTO</th>
            <td class="t-left" colspan="3">{{ ($tramite->procedimiento_id != null ? $tramite->procedimiento->nombre : 'NO REGISTRA') }}</td>
        </tr>        
        <tr>
            <td class="t-left" colspan="4">{{ $tramite->observaciones }}</td>
        </tr>
    </tbody>
</table>
<table  class="mb-15">
    <thead>
        <tr>
            <th colspan="4">DOCUMENTO INICIAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="t-left w-1 t-w" >NÚMERO DE DOCUMENTO</th>
            <td class="t-left">{{ $documento->documento_tipo->nombre}} {{ $documento->numero }}</td>
            <th class="t-left w-1 t-w">FOLIOS</th>
            <td class="t-left">{{ $documento->folios }}</td>
        </tr>
        <tr>
            <th class="t-left w-1 t-w" >REMITENTE</th>
            <td class="t-left" colspan="3">{{ $documento->remitente }}</td>
        </tr>
        <tr>
            <th class="t-left w-1 t-w" >ASUNTO</th>
            <td class="t-left" colspan="3">{{ $documento->asunto }} </td>
        </tr>
    </tbody>
</table>


<table  class="mb-15">
    <tbody>
        <tr>
            <th class="t-left w-1 t-w" >ORIGEN</th>
            @if($tramite->o_dependencia != null)
            <td class="t-left">{{ $tramite->o_dependencia->nombre }}</td>
            <td class="t-left">{{ $tramite->o_dependencia->sede->nombre }}</td>
            @else
            <td class="t-left">EXTERNO</td>
            <td class="t-left">
                {{ ($tramite->ruc ? $tramite->ruc.' | '.$tramite->razon_social.' | ' : '') }} {{ $tramite->o_nro_documento }} | {{ $tramite->o_nombre }} {{ $tramite->o_apaterno }} {{ $tramite->o_amaterno }}
            </td>
            @endif
        </tr>
        <tr>
            <th class="" colspan="3">DESTINOS</th>
        </tr>
        @foreach($movimientos as $movimiento)
        <tr>
            <td class="t-left w-1 t-w">{{ $loop->iteration }}</td>
            @if($movimiento->d_dependencia_id != null)
            <td class="t-left">{{ $movimiento->d_dependencia->nombre }}</td>
            <td class="t-left">{{ $movimiento->d_dependencia->sede->nombre }}</td>
            @else
            <td class="t-left">EXTERNO</td>
            <td class="t-left">{{ $movimiento->d_nro_documento }} | {{ $movimiento->d_nombre }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
@endsection