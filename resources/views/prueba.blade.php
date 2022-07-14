@extends('layouts.blanco')

@section('titulo', 'Prueba')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="codigo col-md-6">
            <h1 style="font-weight: normal;">{{ "T-2022".$codigo }}</h1>
        </div>
    </div>
</div>
@endsection
