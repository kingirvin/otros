<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//PAGINA INICIAL
Route::get('/', function () {
    return view('inicio');
});

//Auth::routes();

//ACCESO GENERAL
Route::get('login', [App\Http\Controllers\UserController::class, 'ingreso'])->name('login');
Route::post('login', [App\Http\Controllers\UserController::class, 'login'])->middleware('throttle:limite');
Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
Route::get('register', [App\Http\Controllers\UserController::class, 'registro'])->name('register');
Route::post('register', [App\Http\Controllers\UserController::class, 'registro_post']);
Route::get('verificar/{codigo}', [App\Http\Controllers\UserController::class, 'verificar']);
Route::get('verificar', [App\Http\Controllers\UserController::class, 'reenviar_verificacion']);
Route::post('verificar', [App\Http\Controllers\UserController::class, 'reenviar_verificacion_post']);
Route::get('restablecer', [App\Http\Controllers\UserController::class, 'restablecer']);
Route::post('restablecer', [App\Http\Controllers\UserController::class, 'restablecer_post']);
Route::get('restablecer/{codigo}', [App\Http\Controllers\UserController::class, 'confirmar']);
Route::post('confirmar', [App\Http\Controllers\UserController::class, 'confirmar_post']);

Route::get('/mensaje', function () {
    return view('paginas.mensaje');
});

Route::get('/status', function () {
    return  view('paginas.status');
});

Route::get('/validar', function () { return redirect('consultas/firmas'); });
Route::get('/constancias', function () { return redirect('consultas/constancias'); });
Route::get('/mesa-de-partes', [App\Http\Controllers\ConsultaController::class, 'mesa_partes']);

Route::prefix('consultas')->group(function () {
    Route::get('/', [App\Http\Controllers\ConsultaController::class, 'index']);
    Route::get('firmas', [App\Http\Controllers\ConsultaController::class, 'firmas']);
    Route::post('firmas/validar', [App\Http\Controllers\ConsultaController::class, 'firmas_post']);  
    Route::get('constancias', [App\Http\Controllers\ConsultaController::class, 'constancias']);
    Route::post('constancias/validar', [App\Http\Controllers\ConsultaController::class, 'constancias_post']);
    Route::get('tramites', [App\Http\Controllers\ConsultaController::class, 'tramites']);
    Route::post('tramites/seguimiento', [App\Http\Controllers\ConsultaController::class, 'seguimiento']);
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index']);
    Route::get('perfil', [App\Http\Controllers\AdminController::class, 'perfil']);

    //ADMINISTRACIÓN DE SISTEMA
    Route::get('sistema', [App\Http\Controllers\SistemaController::class, 'index'])->middleware('modulo:SISTEMA');
    Route::get('sistema/accesos/roles', [App\Http\Controllers\SistemaController::class, 'roles'])->middleware('submodulo:ACCESOS');
    Route::get('sistema/accesos/roles/{id}/privilegios', [App\Http\Controllers\SistemaController::class, 'privilegios'])->middleware('submodulo:ACCESOS');
    Route::get('sistema/accesos/usuarios', [App\Http\Controllers\SistemaController::class, 'usuarios'])->middleware('submodulo:ACCESOS');
    Route::get('sistema/mantenimiento/sedes', [App\Http\Controllers\SistemaController::class, 'sedes'])->middleware('submodulo:MANTENIMIENTO');
    Route::get('sistema/mantenimiento/dependencias', [App\Http\Controllers\SistemaController::class, 'dependencias'])->middleware('submodulo:MANTENIMIENTO');
    Route::get('sistema/persona/datos', [App\Http\Controllers\SistemaController::class, 'personas'])->middleware('submodulo:PERSONA');
    Route::get('sistema/persona/empleados', [App\Http\Controllers\SistemaController::class, 'empleados'])->middleware('submodulo:PERSONA');
    Route::get('sistema/persona/{id}/detalle', [App\Http\Controllers\SistemaController::class, 'persona_detalle'])->middleware('submodulo:PERSONA');
    Route::get('sistema/documental/tipos', [App\Http\Controllers\SistemaController::class, 'documento_tipos'])->middleware('submodulo:DOCADM');
    Route::get('sistema/documental/procedimientos', [App\Http\Controllers\SistemaController::class, 'procedimientos'])->middleware('submodulo:DOCADM');
    Route::get('sistema/documental/procedimientos/nuevo', [App\Http\Controllers\SistemaController::class, 'procedimiento_nuevo'])->middleware('submodulo:DOCADM');
    Route::get('sistema/documental/procedimientos/{id}/modificar', [App\Http\Controllers\SistemaController::class, 'procedimiento_modificar'])->middleware('submodulo:DOCADM');
    Route::get('sistema/documental/procedimientos/{id}/pasos', [App\Http\Controllers\SistemaController::class, 'procedimiento_pasos'])->middleware('submodulo:DOCADM');

    //GESTIÓN DOCUMENTAL
    Route::get('tramite', [App\Http\Controllers\TramiteController::class, 'index'])->middleware('modulo:TRAMITE');    
    Route::get('tramite/archivos', [App\Http\Controllers\TramiteController::class, 'archivos'])->middleware('submodulo:ARCHIVOS');
    Route::get('tramite/archivos/{id}/firma', [App\Http\Controllers\TramiteController::class, 'firma'])->middleware('submodulo:ARCHIVOS');
    Route::get('tramite/emision', [App\Http\Controllers\TramiteController::class, 'nuevo'])->middleware('submodulo:ENVREC');
    Route::get('tramite/emision/emitidos', [App\Http\Controllers\TramiteController::class, 'emitidos'])->middleware('submodulo:ENVREC');
    Route::get('tramite/recepcion', [App\Http\Controllers\TramiteController::class, 'recibir'])->middleware('submodulo:ENVREC');
    Route::get('tramite/recepcion/externo', [App\Http\Controllers\TramiteController::class, 'externo'])->middleware('submodulo:GESTDOC');
    Route::get('tramite/recibidos', [App\Http\Controllers\TramiteController::class, 'recibidos'])->middleware('submodulo:ENVREC');    
    Route::get('tramite/recibidos/derivar/{id}', [App\Http\Controllers\TramiteController::class, 'derivar'])->middleware('submodulo:ENVREC');
    Route::get('tramite/recibidos/derivaciones/{id}', [App\Http\Controllers\TramiteController::class, 'derivaciones'])->middleware('submodulo:ENVREC');
    Route::get('tramite/recibidos/asignaciones/{id}', [App\Http\Controllers\TramiteController::class, 'asignaciones'])->middleware('submodulo:ENVREC');
    Route::get('tramite/seguimiento/{id}', [App\Http\Controllers\TramiteController::class, 'seguimiento']);
    Route::get('tramite/hoja/{id}', [App\Http\Controllers\TramiteController::class, 'hoja']);
    Route::get('tramite/documento/{id}', [App\Http\Controllers\TramiteController::class, 'documento']);  

    //VENTANILLA VIRTUAL
    Route::get('externo', [App\Http\Controllers\ExternoController::class, 'index'])->middleware('modulo:EXTERNO'); 
    Route::get('externo/tramite', [App\Http\Controllers\ExternoController::class, 'ingresar'])->middleware('submodulo:MESADEPARTES');
    Route::post('externo/tramite', [App\Http\Controllers\ExternoController::class, 'ingresar_post'])->middleware('submodulo:MESADEPARTES');
    Route::get('externo/tramite/seguimiento/{codigo}', [App\Http\Controllers\ExternoController::class, 'seguimiento_tramite'])->middleware('submodulo:MESADEPARTES');
    Route::get('externo/consulta', [App\Http\Controllers\ExternoController::class, 'consulta'])->middleware('submodulo:MESADEPARTES');
       
    //CERTIFICADOS
    Route::get('certificado', [App\Http\Controllers\CertificadoController::class, 'index'])->middleware('modulo:CERTIFICADO');
    Route::get('certificado/administrar', [App\Http\Controllers\CertificadoController::class, 'administrar'])->middleware('submodulo:CERTADMIN');
    Route::get('certificado/publicar', [App\Http\Controllers\CertificadoController::class, 'publicar'])->middleware('submodulo:CERTPUBLISH');
    Route::get('certificado/archivos/stream/{codigo}', [App\Http\Controllers\CertificadoController::class, 'vista_previa'])->middleware('submodulo:CERTPUBLISH');
    Route::get('certificado/firma', [App\Http\Controllers\CertificadoController::class, 'firma'])->middleware('submodulo:CERTPUBLISH');

    


    //DE USO GENERAL
    Route::get('archivos/stream/{codigo}', [App\Http\Controllers\TramiteController::class, 'vista_previa']);
    Route::get('archivos/download/{id}', [App\Http\Controllers\TramiteController::class, 'descargar']);
    


});

Route::prefix('info')->group(function () {
    Route::get('/acceso', function () { return view('paginas.acceso'); });
    Route::get('/terminos', function () { return view('paginas.terminos'); });
    Route::get('/declaracion', function () { return view('paginas.declaracion'); });
    Route::get('/versiones', function () { return view('paginas.version'); });
});


Route::get('/prueba', function () {
    return view('email.verificacion');
});


Route::prefix('programador')->group(function () {
    Route::get('codigo/{id}', [App\Http\Controllers\ProgramadorController::class, 'codigo']);
    Route::get('prueba', [App\Http\Controllers\ProgramadorController::class, 'prueba']);
   

});




Route::prefix('json')->group(function () {
    
    //ROLES Y PRIVILEGIOS
    Route::get('roles', [App\Http\Controllers\Api\RolController::class, 'listar']);
    Route::post('roles',  [App\Http\Controllers\Api\RolController::class, 'nuevo'])->middleware('submoduloapi:ACCESOS');
    Route::put('roles/{id}', [App\Http\Controllers\Api\RolController::class, 'modificar'])->middleware('submoduloapi:ACCESOS'); 
    Route::delete('roles/{id}', [App\Http\Controllers\Api\RolController::class, 'eliminar'])->middleware('submoduloapi:ACCESOS'); 
    Route::post('roles/{id}/privilegios', [App\Http\Controllers\Api\RolController::class, 'privilegios'])->middleware('submoduloapi:ACCESOS');
    
    //USER
    Route::get('users', [App\Http\Controllers\Api\UsuarioController::class, 'listar'])->middleware('submoduloapi:ACCESOS');   
    Route::get('users/buscar', [App\Http\Controllers\Api\UsuarioController::class, 'buscar']); 
    Route::post('users',  [App\Http\Controllers\Api\UsuarioController::class, 'nuevo'])->middleware('submoduloapi:ACCESOS');
    Route::put('users/{id}', [App\Http\Controllers\Api\UsuarioController::class, 'modificar'])->middleware('submoduloapi:ACCESOS'); 
    Route::put('users/{id}/password',  [App\Http\Controllers\Api\UsuarioController::class, 'cambiar_password'])->middleware('submoduloapi:ACCESOS');
    Route::post('users/password/renovar',  [App\Http\Controllers\Api\UsuarioController::class, 'renovar_password']);
    Route::put('users/datos/actualizar',  [App\Http\Controllers\Api\UsuarioController::class, 'actualizar']);

    //SEDES
    Route::get('sedes', [App\Http\Controllers\Api\SedeController::class, 'listar']);
    Route::post('sedes',  [App\Http\Controllers\Api\SedeController::class, 'nuevo'])->middleware('submoduloapi:MANTENIMIENTO');
    Route::put('sedes/{id}', [App\Http\Controllers\Api\SedeController::class, 'modificar'])->middleware('submoduloapi:MANTENIMIENTO'); 
    Route::delete('sedes/{id}', [App\Http\Controllers\Api\SedeController::class, 'eliminar'])->middleware('submoduloapi:MANTENIMIENTO');

    //DEPENDENCIAS
    Route::get('dependencias', [App\Http\Controllers\Api\DependenciaController::class, 'listar']);
    Route::get('dependencias/{id}/buscar', [App\Http\Controllers\Api\DependenciaController::class, 'buscar']);
    Route::post('dependencias',  [App\Http\Controllers\Api\DependenciaController::class, 'nuevo'])->middleware('submoduloapi:MANTENIMIENTO');
    Route::put('dependencias/{id}', [App\Http\Controllers\Api\DependenciaController::class, 'modificar'])->middleware('submoduloapi:MANTENIMIENTO'); 
    Route::delete('dependencias/{id}', [App\Http\Controllers\Api\DependenciaController::class, 'eliminar'])->middleware('submoduloapi:MANTENIMIENTO');
    
    //PERSONA
    Route::get('personas', [App\Http\Controllers\Api\PersonaController::class, 'listar']);
    Route::get('personas/buscar', [App\Http\Controllers\Api\PersonaController::class, 'buscar']);
    Route::get('personas/probar', [App\Http\Controllers\Api\PersonaController::class, 'probar']);
    Route::post('personas',  [App\Http\Controllers\Api\PersonaController::class, 'nuevo'])->middleware('submoduloapi:PERSONA');
    Route::put('personas/{id}', [App\Http\Controllers\Api\PersonaController::class, 'modificar'])->middleware('submoduloapi:PERSONA'); 
    Route::delete('personas/{id}', [App\Http\Controllers\Api\PersonaController::class, 'eliminar'])->middleware('submoduloapi:PERSONA');
    
    //EMPLEADOS
    Route::get('empleados', [App\Http\Controllers\Api\EmpleadoController::class, 'listar']);
    Route::get('empleados/{id}/buscar', [App\Http\Controllers\Api\EmpleadoController::class, 'buscar']);
    Route::post('empleados',  [App\Http\Controllers\Api\EmpleadoController::class, 'nuevo'])->middleware('submoduloapi:PERSONA');
    Route::put('empleados/{id}',  [App\Http\Controllers\Api\EmpleadoController::class, 'finalizar'])->middleware('submoduloapi:PERSONA');

    //DOCUMENTOS DE GESTION
    Route::get('tipodocumentos', [App\Http\Controllers\Api\DocumentoTipoController::class, 'listar']);
    Route::post('tipodocumentos',  [App\Http\Controllers\Api\DocumentoTipoController::class, 'nuevo'])->middleware('submoduloapi:DOCADM');
    Route::put('tipodocumentos/{id}', [App\Http\Controllers\Api\DocumentoTipoController::class, 'modificar'])->middleware('submoduloapi:DOCADM'); 
    Route::delete('tipodocumentos/{id}', [App\Http\Controllers\Api\DocumentoTipoController::class, 'eliminar'])->middleware('submoduloapi:DOCADM');
    
    //PROCEDIMIENTOS ADMINISTRATIVOS
    Route::get('procedimientos', [App\Http\Controllers\Api\ProcedimientoController::class, 'listar']);
    Route::post('procedimientos',  [App\Http\Controllers\Api\ProcedimientoController::class, 'nuevo'])->middleware('submoduloapi:DOCADM');
    Route::put('procedimientos/{id}', [App\Http\Controllers\Api\ProcedimientoController::class, 'modificar'])->middleware('submoduloapi:DOCADM'); 
    Route::delete('procedimientos/{id}', [App\Http\Controllers\Api\ProcedimientoController::class, 'eliminar'])->middleware('submoduloapi:DOCADM');    
    Route::post('procedimientos/{id}/pasos', [App\Http\Controllers\Api\ProcedimientoController::class, 'pasos'])->middleware('submoduloapi:DOCADM');
    

    //ARCHIVOS
    Route::get('archivos/todos', [App\Http\Controllers\Api\ArchivoController::class, 'listar_todo']);
    Route::post('archivos',  [App\Http\Controllers\Api\ArchivoController::class, 'nuevo']);
    Route::post('archivos/mover',  [App\Http\Controllers\Api\ArchivoController::class, 'mover'])->middleware('submoduloapi:ARCHIVOS');
    Route::delete('archivos/{id}',  [App\Http\Controllers\Api\ArchivoController::class, 'eliminar'])->middleware('submoduloapi:ARCHIVOS');
 
    //COMPARTIDOS
    Route::get('compartidos/{id}', [App\Http\Controllers\Api\CompartidoController::class, 'listar'])->middleware('submoduloapi:ARCHIVOS');
    Route::post('compartidos',  [App\Http\Controllers\Api\CompartidoController::class, 'nuevo'])->middleware('submoduloapi:ARCHIVOS');   
    Route::delete('compartidos', [App\Http\Controllers\Api\CompartidoController::class, 'eliminar'])->middleware('submoduloapi:ARCHIVOS');

    //VERSIONES
    Route::get('versiones/{id}', [App\Http\Controllers\Api\VersionController::class, 'listar'])->middleware('submoduloapi:ARCHIVOS');
    Route::post('versiones/restaurar', [App\Http\Controllers\Api\VersionController::class, 'restaurar'])->middleware('submoduloapi:ARCHIVOS');

    //CARPETAS
    Route::get('carpetas', [App\Http\Controllers\Api\CarpetaController::class, 'listar']);
    Route::post('carpetas',  [App\Http\Controllers\Api\CarpetaController::class, 'nuevo'])->middleware('submoduloapi:ARCHIVOS');
    Route::post('carpetas/mover',  [App\Http\Controllers\Api\CarpetaController::class, 'mover'])->middleware('submoduloapi:ARCHIVOS');
    Route::put('carpetas/{id}', [App\Http\Controllers\Api\CarpetaController::class, 'modificar'])->middleware('submoduloapi:ARCHIVOS'); 
    Route::delete('carpetas/{id}', [App\Http\Controllers\Api\CarpetaController::class, 'eliminar'])->middleware('submoduloapi:ARCHIVOS');

    //FIRMA
    Route::get('firma/argumentos', [App\Http\Controllers\Api\FirmaController::class, 'obtener_argumentos']);
    Route::get('firma/{id}/descargar', [App\Http\Controllers\Api\FirmaController::class, 'descargar_firma']);
    Route::post('firma/{id}/cargar',  [App\Http\Controllers\Api\FirmaController::class, 'cargar_firmado']);

    //TRAMITE
    Route::post('tramites', [App\Http\Controllers\Api\TramiteController::class, 'nuevo'])->middleware('submoduloapi:ENVREC');  
    Route::get('documentos/emitidos', [App\Http\Controllers\Api\TramiteController::class, 'emitidos'])->middleware('submoduloapi:ENVREC');    
    Route::put('documentos/{id}', [App\Http\Controllers\Api\TramiteController::class, 'modificar_documento'])->middleware('submoduloapi:ENVREC'); 
    Route::delete('documentos/{id}', [App\Http\Controllers\Api\TramiteController::class, 'anular_emision'])->middleware('submoduloapi:ENVREC');

    Route::post('tramites/externo', [App\Http\Controllers\Api\TramiteController::class, 'recepcionar_externo'])->middleware('submoduloapi:GESTDOC');  

    Route::get('movimientos/pendientes', [App\Http\Controllers\Api\TramiteController::class, 'por_recepcionar'])->middleware('submoduloapi:ENVREC');
    Route::put('movimientos/{id}/recibir', [App\Http\Controllers\Api\TramiteController::class, 'recepcionar'])->middleware('submoduloapi:ENVREC'); 
    Route::get('movimientos/recibidos', [App\Http\Controllers\Api\TramiteController::class, 'recepcionados'])->middleware('submoduloapi:ENVREC');
    Route::post('movimientos/derivar', [App\Http\Controllers\Api\TramiteController::class, 'derivar'])->middleware('submoduloapi:ENVREC'); 
    Route::delete('movimientos/{id}/derivacion/anular', [App\Http\Controllers\Api\TramiteController::class, 'anular_derivacion'])->middleware('submoduloapi:ENVREC');  
    Route::put('movimientos/{id}/atender', [App\Http\Controllers\Api\TramiteController::class, 'atender'])->middleware('submoduloapi:ENVREC');     
    Route::put('movimientos/{id}/atencion/anular', [App\Http\Controllers\Api\TramiteController::class, 'anular_atendido'])->middleware('submoduloapi:ENVREC');
    Route::delete('movimientos/{id}/recibido/anular', [App\Http\Controllers\Api\TramiteController::class, 'anular_recepcion'])->middleware('submoduloapi:ENVREC');  
    
    Route::post('movimientos/{id}/observar', [App\Http\Controllers\Api\TramiteController::class, 'observar'])->middleware('submoduloapi:ENVREC');
    Route::get('movimientos/{id}/observaciones', [App\Http\Controllers\Api\TramiteController::class, 'observaciones']);  
    Route::delete('movimientos/observacion/{id}/anular', [App\Http\Controllers\Api\TramiteController::class, 'anular_observacion'])->middleware('submoduloapi:ENVREC');  
    
    //ASIGNACIONES
    Route::get('asignaciones/{id}', [App\Http\Controllers\Api\AsignacionController::class, 'listar']);
    Route::post('asignaciones',  [App\Http\Controllers\Api\AsignacionController::class, 'nuevo'])->middleware('submoduloapi:ENVREC');
    Route::put('asignaciones/{id}', [App\Http\Controllers\Api\AsignacionController::class, 'modificar'])->middleware('submoduloapi:ENVREC');
    Route::put('asignaciones/{id}/estado', [App\Http\Controllers\Api\AsignacionController::class, 'estado'])->middleware('submoduloapi:ENVREC');  
    Route::delete('asignaciones/{id}', [App\Http\Controllers\Api\AsignacionController::class, 'eliminar'])->middleware('submoduloapi:ENVREC');

    //REPOSITORIOS DE CERTIFICADOS
    Route::get('repositorios', [App\Http\Controllers\Api\CertRepositorioController::class, 'listar'])->middleware('submoduloapi:CERTADMIN');    
    Route::post('repositorios', [App\Http\Controllers\Api\CertRepositorioController::class, 'nuevo'])->middleware('submoduloapi:CERTADMIN');  
    Route::put('repositorios/{id}', [App\Http\Controllers\Api\CertRepositorioController::class, 'modificar'])->middleware('submoduloapi:CERTADMIN'); 
    Route::delete('repositorios/{id}', [App\Http\Controllers\Api\CertRepositorioController::class, 'eliminar'])->middleware('submoduloapi:CERTADMIN');

    Route::get('repositorios/{id}/responsables', [App\Http\Controllers\Api\CertResponsableController::class, 'listar'])->middleware('submoduloapi:CERTADMIN');    
    Route::post('repositorios/responsables', [App\Http\Controllers\Api\CertResponsableController::class, 'nuevo'])->middleware('submoduloapi:CERTADMIN');  
    Route::delete('repositorios/responsables/eliminar', [App\Http\Controllers\Api\CertResponsableController::class, 'eliminar'])->middleware('submoduloapi:CERTADMIN');

    Route::get('repositorios/archivos/todos', [App\Http\Controllers\Api\CertArchivoController::class, 'listar_todo']);
    Route::post('repositorios/archivos',  [App\Http\Controllers\Api\CertArchivoController::class, 'nuevo'])->middleware('submoduloapi:CERTPUBLISH');
    Route::post('repositorios/archivos/mover',  [App\Http\Controllers\Api\CertArchivoController::class, 'mover'])->middleware('submoduloapi:CERTPUBLISH');
    Route::delete('repositorios/archivos/{id}',  [App\Http\Controllers\Api\CertArchivoController::class, 'eliminar'])->middleware('submoduloapi:CERTPUBLISH');
    Route::post('repositorios/archivos/publicar',  [App\Http\Controllers\Api\CertArchivoController::class, 'publicar'])->middleware('submoduloapi:CERTPUBLISH');
    
    Route::get('repositorios/archivos/firma/argumentos',  [App\Http\Controllers\Api\CertFirmaController::class, 'obtener_argumentos']);
    Route::post('repositorios/archivos/firma/cargar',  [App\Http\Controllers\Api\CertFirmaController::class, 'cargar_firmado']);

    Route::get('repositorios/carpetas', [App\Http\Controllers\Api\CertCarpetaController::class, 'listar']);
    Route::post('repositorios/carpetas',  [App\Http\Controllers\Api\CertCarpetaController::class, 'nuevo'])->middleware('submoduloapi:CERTPUBLISH');
    Route::post('repositorios/carpetas/mover',  [App\Http\Controllers\Api\CertCarpetaController::class, 'mover'])->middleware('submoduloapi:CERTPUBLISH');
    Route::put('repositorios/carpetas/{id}', [App\Http\Controllers\Api\CertCarpetaController::class, 'modificar'])->middleware('submoduloapi:CERTPUBLISH'); 
    Route::delete('repositorios/carpetas/{id}', [App\Http\Controllers\Api\CertCarpetaController::class, 'eliminar'])->middleware('submoduloapi:CERTPUBLISH');


});
