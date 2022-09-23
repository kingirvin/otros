<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertRepositorioUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cert_repositorio_users', function (Blueprint $table) {
            $table->unsignedBigInteger('cert_repositorio_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();     
            $table->timestamps();

            $table->foreign('cert_repositorio_id')->references('id')->on('cert_repositorios');    
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['cert_repositorio_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cert_repositorio_users');
    }
}
