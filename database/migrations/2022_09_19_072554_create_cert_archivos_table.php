<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cert_archivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cert_repositorio_id')->nullable();            
            $table->unsignedBigInteger('cert_carpeta_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('codigo', 20)->nullable();//alpha
            $table->string('cvd', 20)->nullable();//CVD
            $table->string('nombre');//mi_archivo.pdf
            $table->string('formato', 10);//pdf
            $table->bigInteger('size');//545461
            $table->text('ruta');//storage/archivos/dadlh1f6a5f46ds1f6sf4sdsdf.pdf
            $table->text('nombre_real');//dadlh1f6a5f46ds1f6sf4sdsdf.pdf
            $table->text('descripcion')->nullable();
            $table->longText('informacion')->nullable();
            $table->tinyInteger('para_firma')->default(0);//0:simple, 1:pdf_adecuado
            $table->tinyInteger('estado');//0:inicial 1:incrustado 2:firmado
            $table->tinyInteger('publico')->default(0);//0:no 1:si
            $table->timestamps();

            $table->foreign('cert_repositorio_id')->references('id')->on('cert_repositorios');            
            $table->foreign('cert_carpeta_id')->references('id')->on('cert_carpetas');
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
        Schema::dropIfExists('cert_archivos');
    }
}
