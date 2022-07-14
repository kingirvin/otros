<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivoHistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_historicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archivo_id'); 
            $table->unsignedBigInteger('user_id')->nullable();           
            $table->string('formato', 10);//pdf
            $table->bigInteger('size');//545461
            $table->text('ruta');//storage/archivos/dadlh1f6a5f46ds1f6sf4sdsdf.pdf
            $table->text('nombre_real');//dadlh1f6a5f46ds1f6sf4sdsdf.pdf
            $table->tinyInteger('estado');//0:inicial 1:cargado 2:firmado
            $table->timestamps();      

            $table->foreign('archivo_id')->references('id')->on('archivos');
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
        Schema::dropIfExists('archivo_historicos');
    }
}
