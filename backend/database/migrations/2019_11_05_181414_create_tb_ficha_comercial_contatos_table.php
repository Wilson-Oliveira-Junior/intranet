<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbFichaComercialContatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ficha_comercial_contatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_ficha_comercial');
            $table->string('nome');
            $table->string('cargo');
            $table->string('tipo_contato');
            $table->string('telefone');
            $table->string('celular');
            $table->string('email');
            $table->string('perfil');
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
        Schema::dropIfExists('tb_ficha_comercial_contatos');
    }
}
