<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dependencia_id'); 
            $table->unsignedBigInteger('persona_id');
            $table->string('cargo');
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_termino')->nullable();
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();

            $table->foreign('dependencia_id')->references('id')->on('dependencias');             
            $table->foreign('persona_id')->references('id')->on('personas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
