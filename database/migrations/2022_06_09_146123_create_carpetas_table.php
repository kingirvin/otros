<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarpetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpetas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dependencia_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('carpeta_id')->nullable();
            $table->string('codigo', 12)->nullable();//41SER154
            $table->string('nombre');//mi_carpeta
            $table->text('ubicacion');
            $table->tinyInteger('publico');//0:no 1:si 
            $table->timestamps();

            $table->foreign('dependencia_id')->references('id')->on('dependencias');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('carpeta_id')->references('id')->on('carpetas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carpetas');
    }
}
