<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbClienteDominiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cliente_dominios', function (Blueprint $table) {
            $table->increments('id_dominio');
            // Relacionamento do dominio com o cliente
            $table->integer('id_cliente')->unsigned();
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->string('dominio');
            $table->enum('tipo_hospedagem',['Redirecionamento','Hospedagem Interna', 'Hospedagem Externa'])->default('Hospedagem Interna');
            $table->enum('dominio_principal',['Sim','Não'])->default('Sim');
            $table->enum('status',['Ativo','Inativo'])->default('Ativo');

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
        Schema::dropIfExists('tb_cliente_dominios');
    }
}
