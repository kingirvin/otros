<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoAsignacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movimiento_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('accion_id')->nullable();//si es proveido
            $table->text('detalles')->nullable();
            $table->tinyInteger('estado');//0:pendiente, 1:en proceso, 2:finalizado
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('movimiento_id')->references('id')->on('movimientos');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->foreign('accion_id')->references('id')->on('acciones');            
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
        Schema::dropIfExists('movimiento_asignaciones');
    }
}
