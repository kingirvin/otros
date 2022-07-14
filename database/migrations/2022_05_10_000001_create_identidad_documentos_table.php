<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentidadDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identidad_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('abreviatura',25)->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('largo');
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identidad_documentos');
    }
}
