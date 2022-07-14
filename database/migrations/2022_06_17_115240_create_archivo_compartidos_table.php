<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivoCompartidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_compartidos', function (Blueprint $table) {
            $table->unsignedBigInteger('archivo_id'); 
            $table->unsignedBigInteger('user_id');             
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();         
            $table->foreign('archivo_id')->references('id')->on('archivos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['archivo_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivo_compartidos');
    }
}
