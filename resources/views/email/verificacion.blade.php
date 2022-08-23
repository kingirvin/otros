@extends('layouts.email')

@section('contenido')
<div class="h5 fw-bolder mb-4">Hola {{ $user->nombre }} {{ $user->apaterno }}</div>
<div class="mb-2">Tu registro se ha realizado exitosamente, por favor verifica que tu correo es <b>{{$user->email}}</b> haciendo click en el siguiente enlace.</div>
<small class="text-muted d-block lh-sm mb-4">
    Si no enviaste la solicitud de verificación de correo, no es necesario que realices ninguna acción.
</small>
<div class="mb-5 text-center">
<a href="{{ url('verificar/'.$verficiacion->codigo) }}" class="btn btn-primary">Verificar correo</a>
</div>
<small class="text-muted d-block lh-sm">
    Si tienes problemas para verificar tu correo electrónico, contáctanos a <a href = "mailto: oti@unamad.edu.pe">oti@unamad.edu.pe</a>.
</small>
@endsection