<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            //codificacion
            $table->integer('year');
            $table->integer('correlativo')->nullable();
            $table->string('codigo', 12)->nullable();
            //pertenencia
            $table->unsignedBigInteger('tramite_id')->nullable();
            $table->unsignedBigInteger('dependencia_id')->nullable();//dependencia de registro
            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->unsignedBigInteger('persona_id')->nullable();
            $table->integer('o_numero');//correlativo por oficina que envia
            $table->unsignedBigInteger('documento_tipo_id')->nullable();
            $table->string('numero');//002-2021-GOREMAD/UI
            $table->string('remitente');//JUAN PERES
            $table->text('asunto');//SOLICITO ADQUISICION DE PC
            $table->integer('folios')->nullable();//05
            $table->text('observaciones')->nullable();
            $table->text('anexos_url')->nullable();
            $table->unsignedBigInteger('archivo_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();//usuario que registro
            $table->timestamps();

            $table->foreign('tramite_id')->references('id')->on('tramites');
            $table->foreign('dependencia_id')->references('id')->on('dependencias');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos');
            $table->foreign('archivo_id')->references('id')->on('archivos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos');
    }
}
