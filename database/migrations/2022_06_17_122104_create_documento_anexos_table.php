<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentoAnexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_anexos', function (Blueprint $table) {
            $table->unsignedBigInteger('documento_id'); 
            $table->unsignedBigInteger('archivo_id'); 
            $table->Integer('principal');
            $table->timestamps();

            $table->primary(['documento_id', 'archivo_id']); 
            $table->foreign('documento_id')->references('id')->on('documentos');             
            $table->foreign('archivo_id')->references('id')->on('archivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento_anexos');
    }
}
