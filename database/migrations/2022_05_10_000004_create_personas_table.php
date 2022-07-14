<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo')->default(0);//0:natural, 1:juridico
            $table->unsignedBigInteger('identidad_documento_id')->nullable();
            $table->string('nro_documento',25);
            $table->string('nombre');
            $table->string('apaterno');
            $table->string('amaterno');
            $table->string('correo')->nullable();
            $table->string('telefono',50)->nullable();
            $table->string('direccion')->nullable();
            $table->dateTime('nacimiento')->nullable();
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();

            $table->foreign('identidad_documento_id')->references('id')->on('identidad_documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
