<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmodulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submodulos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_id')->nullable();
            $table->string('titulo');
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submodulos');
    }
}
