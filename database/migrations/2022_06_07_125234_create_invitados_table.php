<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('persona_id');
            $table->string('ruc',25);
            $table->string('razon_social');            
            $table->string('dependencia')->nullable();
            $table->string('cargo')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono',50)->nullable();
            $table->string('direccion')->nullable();
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();            

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
        Schema::dropIfExists('invitados');
    }
}
