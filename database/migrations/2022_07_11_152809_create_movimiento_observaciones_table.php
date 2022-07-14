<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoObservacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_observaciones', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('tramite_id');    
            $table->unsignedBigInteger('movimiento_id');
            $table->unsignedBigInteger('user_id');
            $table->text('detalle');
            $table->timestamps();

            $table->foreign('tramite_id')->references('id')->on('tramites');
            $table->foreign('movimiento_id')->references('id')->on('movimientos');
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
        Schema::dropIfExists('movimiento_observaciones');
    }
}
