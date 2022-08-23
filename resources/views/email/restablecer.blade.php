@extends('layouts.email')

@section('contenido')
<div class="h5 fw-bolder mb-4">Hola {{ $user->nombre }} {{ $user->apaterno }}</div>
<div class="mb-2">Hemos recibido una solicitud para <b>restablecer</b> la contraseña de tu cuenta de usuario, has click en el siguiente enlace para iniciar el restablecimiento.</div>
<div class="mb-4"> <b>Este enlace solo es válido por las siguientes 24 horas</b>.</div>
<div class="mb-4 text-center">
<a href="{{ url('restablecer/'.$restablecimiento->codigo) }}" class="btn btn-primary">Restablecer contraseña</a>
</div>
<small class="text-muted d-block lh-sm mb-1">
    Si no enviaste la solicitud de restablecimiento de contraseña, no es necesario que realices ninguna acción.
</small>
<small class="text-muted d-block lh-sm">
    Si tienes problemas para restablecer tu contraseña, contáctanos a <a href = "mailto: oti@unamad.edu.pe">oti@unamad.edu.pe</a>.
</small>
@endsection