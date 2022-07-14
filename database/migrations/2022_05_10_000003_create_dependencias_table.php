<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sede_id')->nullable();
            $table->unsignedBigInteger('dependencia_id')->nullable();
            $table->string('abreviatura',20)->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();
            $table->foreign('sede_id')->references('id')->on('sedes');
            $table->foreign('dependencia_id')->references('id')->on('dependencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencias');
    }
}
