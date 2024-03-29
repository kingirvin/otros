@extends('layouts.blanco')

@section('titulo', 'Ayuda para el acceso')

@section('contenido')
<div class="container-narrow py-4">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo_horizontal.png') }}" height="60" alt="">        
    </div>
    <div class="card card-md">
        <div class="card-body">
        <h3 class="card-title text-center">Ayuda para el acceso</h3>
        <div class="markdown">
            <p class="text-justify">Si presentas dificultades para acceder a tu cuenta de usuario, identifica las posibles causas entre las siguientes opciones.</p>            
            <h3>1. Aún no posees una cuenta de usuario</h3>            
            <p class="text-justify">Si aún no posees una cuenta de usuario deberás solicitar el registro o registrarte en la plataforma dependiendo del tipo de usuario al que pertenezcas.</p>
            <ul>
                <li>
                    <p class="text-justify"><strong>Personal administrativo.</strong> Comunícate con la <span class="text-pink">Oficina de Tecnologías de la Información</span> al correo <a href="mailto: oti@unamad.edu.pe">oti@unamad.edu.pe</a> o apersónate personalmente a su oficina y solicita el registro de tu cuenta de usuario.</p>
                </li>
                <li>
                    <p class="text-justify"><strong>Estudiante universitario.</strong> Comunícate con la <span class="text-pink">Oficina de Tecnologías de la Información</span> al correo <a href="mailto: oti@unamad.edu.pe">oti@unamad.edu.pe</a> o apersónate personalmente a su oficina y solicita el registro de tu cuenta de usuario.</p>
                </li>
                <li>
                    <p class="text-justify mb-1"><strong>Persona externa.</strong> Ingresa en la página de <a href="{{ url('register') }}">Registro</a>, completa el formulario con tus datos y sigue el procedimiento para el registro de tu cuenta de usuario.</p>
                    <p class="text-justify">Si te registraste pero no te llego el correo de verificación es posible que se encuentre en la carpeta de <b>correo no deseado</b> o <b>spam</b>, si no se encuentra intenta <a href="{{ url('verificar') }}">Reenviar</a> el correo e verificación.</p>
                </li>
            </ul>
            <h3>2. Olvido de contraseña</h3>            
            <p class="text-justify">Si has olvidado la contraseña de tu cuenta de usuario puedes utilizar los siguientes métodos para recuperarlo.</p>
            <ul>
                <li>
                    <p class="text-justify"><strong>Correo de restablecimiento.</strong> Ingresa en la página de <a href="{{ url('restablecer') }}">Restablecer contraseña</a>, donde deberás ingresar el correo electrónico asociado a tu cuenta de usuario y se te enviará un correo con un enlace para restablecer tu contraseña.</p>
                </li>
                <li>
                    <p class="text-justify"><strong>Soporte técnico.</strong> Comunícate con la <span class="text-pink">Oficina de Tecnologías de la Información</span> al correo <a href="mailto: oti@unamad.edu.pe">oti@unamad.edu.pe</a> o apersónate personalmente a su oficina y solicita el apoyo técnico para restablecimiento de tu contraseña (solo personal administrativo y estudiante universitario).</p>
                </li>
            </ul>
            <h3>3. Cuenta de usuario deshabilitada</h3>            
            <p class="text-justify">Si eres personal administrativo o estudiante universitario que se encuentre actualmente en funciones en la Universidad Nacional Amazónica de Madre de Dios, comunícate con la <span class="text-pink">Oficina de Tecnologías de la Información</span> al correo <a href = "mailto: oti@unamad.edu.pe">oti@unamad.edu.pe</a> o apersónate personalmente a su oficina y solicita la reactivación de tu cuenta de usuario.</p>
        </div>
        </div>
    </div>
</div>
@endsection
