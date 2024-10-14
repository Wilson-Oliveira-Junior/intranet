<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroSenhaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
    idRegistroSenha
idCliente
idDominio
strURL
strLogin
strSenha
idTipoRegistro
Observacao
admin bool
    */  
    public function up()
    {
        Schema::create('tbRegistroSenhas', function (Blueprint $table) {
            $table->increments('idRegistroSenha');
            $table->string('strURL',128)->nullable();
            $table->string('strLogin',128);
            $table->string('strSenha',64);
            $table->text('observacao')->nullable();
            $table->boolean('admin')->default(0);
            $table->integer('idTipoRegistro')->unsigned()->default(0);
            $table->integer('idDominio')->unsigned()->default(0);
            $table->integer('idCliente')->unsigned();
            $table->foreign('idCliente')->references('id')->on('clientes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbRegistroSenhas');
    }
}
