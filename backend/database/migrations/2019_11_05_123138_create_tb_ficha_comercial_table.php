<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbFichaComercialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ficha_comercial', function (Blueprint $table) {
            $table->increments('id');
            $table->string('quem_vendeu');
            $table->string('razao_social');
            $table->string('nome_fantasia');
            $table->string('cnpj_cpf');
            $table->string('inscricao_estadual');
            $table->string('dia_boleto');
            $table->string('observacao_boleto');
            $table->string('nota_fiscal');
            $table->string('cep');
            $table->string('endereco');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('numero');
            $table->string('complemento');
            $table->enum('status',['Publicado','Pendente'])->default('Pendente');
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
        Schema::dropIfExists('tb_ficha_comercial');
    }
}
