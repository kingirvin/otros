<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('tramite_id');//a que tramite pertenece el movimiento
            $table->unsignedBigInteger('documento_id');//que documento se esta moviendo
            $table->unsignedBigInteger('accion_id')->nullable();//si es proveido
            $table->text('accion_otros')->nullable();//detalle de acciones a tomar (en proveido)
            $table->unsignedBigInteger('anterior_id')->nullable();//movimiento que precede a este
            $table->tinyInteger('tipo');//0:inicio de tramite, 1:derivacion (proveido), 2:derivacion (adjuntando nuevo documento)
            $table->tinyInteger('copia');//es copia 

            //QUIEN ENVIA 
            $table->tinyInteger('o_tipo')->default(0);//0:interno, 1:externo
            //--interno
            $table->unsignedBigInteger('o_dependencia_id')->nullable();//dependencia de remitente           
            $table->unsignedBigInteger('o_empleado_id')->nullable();
            $table->unsignedBigInteger('o_persona_id')->nullable();
            //--externo
            $table->string('o_descripcion')->nullable();//descripcion general
            //--
            $table->dateTime('o_fecha')->nullable();//fecha de envio
            $table->unsignedBigInteger('o_user_id')->nullable();//quien registra el envio
            $table->integer('o_year')->nullable();//año de correlativo en oficina que envia
            $table->integer('o_numero')->nullable();//correlativo de documento por oficina que envia (solo nuevo)
            
            //QUIEN RECIBE 
            $table->tinyInteger('d_tipo')->default(0);//0:interno, 1:externo
            //--interno
            $table->unsignedBigInteger('d_dependencia_id')->nullable();//dependencia de destino         
            $table->unsignedBigInteger('d_empleado_id')->nullable();
            $table->unsignedBigInteger('d_persona_id')->nullable();
            //--externo
            $table->unsignedBigInteger('d_identidad_documento_id')->nullable();//tipo de documento   
            $table->string('d_nro_documento')->nullable();//documento de persona destino (persona externa)
            $table->string('d_nombre')->nullable();//nombre/razon de persona destino
            //--
            $table->dateTime('d_fecha')->nullable();//fecha de recepcion
            $table->unsignedBigInteger('d_user_id')->nullable();//quien registro la recepcion
            $table->integer('d_year')->nullable();//año de correlativo que recibe
            $table->integer('d_numero')->nullable();//correlativo por oficina que recibe
            
            $table->text('d_observacion')->nullable();//observaciones al momento de recibir

            //QUIEN TERMINA
            $table->unsignedBigInteger('f_user_id')->nullable();//usuario que atiende/anula
            $table->dateTime('f_fecha')->nullable();//fecha de finalizar
            $table->text('f_observacion')->nullable();//observaciones al momento de finalizar

            $table->integer('asignaciones')->default(0);
            //------------
            $table->tinyInteger('estado');//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado            
            $table->timestamps();

            $table->foreign('tramite_id')->references('id')->on('tramites');
            $table->foreign('documento_id')->references('id')->on('documentos');
            $table->foreign('accion_id')->references('id')->on('acciones');
            $table->foreign('anterior_id')->references('id')->on('movimientos');

            $table->foreign('o_dependencia_id')->references('id')->on('dependencias');
            $table->foreign('o_empleado_id')->references('id')->on('empleados');
            $table->foreign('o_persona_id')->references('id')->on('personas');
            $table->foreign('o_user_id')->references('id')->on('users');

            $table->foreign('d_dependencia_id')->references('id')->on('dependencias');
            $table->foreign('d_empleado_id')->references('id')->on('empleados');
            $table->foreign('d_persona_id')->references('id')->on('personas');
            $table->foreign('d_identidad_documento_id')->references('id')->on('identidad_documentos');
            $table->foreign('d_user_id')->references('id')->on('users');

            $table->foreign('f_user_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
}
