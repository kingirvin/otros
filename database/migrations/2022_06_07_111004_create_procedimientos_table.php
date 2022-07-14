<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimientos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo');//0:interno, 1:universitario, 2:externo
            $table->string('codigo')->nullable();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->text('requisitos')->nullable();
            $table->text('normatividad')->nullable();
            $table->unsignedBigInteger('presentar_id'); 
            $table->tinyInteger('presentar_modalidad')->nullable();//0:presencial, 1:virtual
            $table->decimal('pago_monto', 10, 2)->nullable();
            $table->string('pago_entidad')->nullable();
            $table->string('pago_codigo')->nullable();
            $table->integer('plazo');
            $table->tinyInteger('calificacion');//0:aprovacion automatica, 1:evaluacion previa
            $table->unsignedBigInteger('atender_id')->nullable(); 
            $table->tinyInteger('atender_modalidad')->nullable();//0:presencial, 1:virtual
            $table->tinyInteger('estado')->default(1);//1:activo
            $table->timestamps();

            $table->foreign('presentar_id')->references('id')->on('dependencias');
            $table->foreign('atender_id')->references('id')->on('dependencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedimientos');
    }
}
