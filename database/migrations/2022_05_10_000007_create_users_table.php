<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo')->default(1);//1:interno, 0:externo
            $table->string('codigo',8)->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->unsignedBigInteger('persona_id')->nullable();            
            $table->unsignedBigInteger('identidad_documento_id')->nullable();
            $table->string('nro_documento',25);   
            $table->string('nombre');
            $table->string('apaterno');
            $table->string('amaterno');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->tinyInteger('estado')->default(1);//activo
            $table->timestamps();

            $table->foreign('rol_id')->references('id')->on('roles');
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->foreign('identidad_documento_id')->references('id')->on('identidad_documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
