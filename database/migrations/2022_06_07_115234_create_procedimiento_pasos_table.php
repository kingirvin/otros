<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedimientoPasosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimiento_pasos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procedimiento_id'); 
            $table->unsignedBigInteger('dependencia_id');
            $table->integer('orden');
            $table->string('accion')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('plazo_atencion');
            $table->integer('plazo_subsanacion')->nullable();
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();

            $table->foreign('procedimiento_id')->references('id')->on('procedimientos');
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
        Schema::dropIfExists('procedimiento_pasos');
    }
}
