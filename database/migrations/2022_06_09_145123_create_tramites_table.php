<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTramitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();            
            //codificacion
            $table->integer('year');
            $table->integer('correlativo')->nullable();
            $table->string('codigo', 12)->nullable();
            //tipo de origen de trámite
            $table->tinyInteger('o_tipo');//0:interno, 1:externo
            //--interno
            $table->unsignedBigInteger('o_dependencia_id')->nullable();
            //--externo
            $table->tinyInteger('o_externo_tipo')->nullable();//0:persona externa sin usuario, 1:persona externa con usuario            
            $table->unsignedBigInteger('o_user_id')->nullable();//si tiene usuario externo     
            //si no se tiene usuario externo
            $table->string('ruc',25)->nullable();
            $table->string('razon_social')->nullable();
            $table->unsignedBigInteger('o_identidad_documento_id')->nullable();//tipo de documento            
            $table->string('o_nro_documento',25)->nullable();//envia externo (nombre/razon)            
            $table->string('o_nombre')->nullable();//envia externo (nombre/razon)
            $table->string('o_apaterno')->nullable();
            $table->string('o_amaterno')->nullable();
            $table->string('o_telefono')->nullable();//telefono externo 
            $table->string('o_correo')->nullable();//correo externo 
            $table->string('o_direccion')->nullable();//direccion externo 
            //
            $table->unsignedBigInteger('procedimiento_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();//usuario que registra
            $table->tinyInteger('estado')->default(1);//0:anulado, 1:activo, 2:observado 
            $table->timestamps();

            $table->foreign('o_dependencia_id')->references('id')->on('dependencias');
            $table->foreign('o_user_id')->references('id')->on('users');
            $table->foreign('o_identidad_documento_id')->references('id')->on('identidad_documentos');
            $table->foreign('procedimiento_id')->references('id')->on('procedimientos');
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
        Schema::dropIfExists('tramites');
    }
}
