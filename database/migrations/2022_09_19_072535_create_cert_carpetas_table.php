<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertCarpetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cert_carpetas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cert_repositorio_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('cert_carpeta_id')->nullable();
            $table->string('codigo', 12)->nullable();//41SER154
            $table->string('nombre');//mi_carpeta
            $table->text('ubicacion');
            $table->tinyInteger('publico');//0:no 1:si 
            $table->timestamps();

            $table->foreign('cert_repositorio_id')->references('id')->on('cert_repositorios');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cert_carpeta_id')->references('id')->on('cert_carpetas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cert_carpetas');
    }
}
