<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbFichaComercialProjetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ficha_comercial_projetos', function (Blueprint $table) {
            $table->increments('id');
            
            // Id Ficha Comercial
            $table->integer('id_ficha_comercial')->unsigned();

            $table->string('data_contrato',128);
            $table->integer('prazo')->unsigned();
            $table->enum('tipo_manutenco',['Mensal','Hora Técnica'])->default('Hora Técnica');
            $table->enum('conteudo',['Responsabilidade do Cliente','Nós vamos desenvolver','Baseado no site antigo','Reestrutura do Conteúdo'])->default('Responsabilidade do Cliente');
            $table->enum('idiomas',['Português','Inglês','Espanhol','Italiano','Outro'])->default('Português');
            $table->enum('ssl_cdn',['SSL','CDN','SSL/CDN','Não Terá'])->default('SSL');
            $table->text('itens_menu')->nullable();
            $table->text('itens_pagina_principal')->nullable();

            // Slider
            $table->enum('slider_pagina_principal',['Sim','Não'])->default('Sim');
            $table->enum('slider_nos_desenvolver',['Sim','Não'])->default('Sim');
            $table->integer('slider_quantidade')->unsigned();
            $table->enum('slider_feito_uma_vez',['Sim','Não'])->default('Não');
            $table->enum('slider_periocidade',['Mensal','Trimestral','Semestral','Anual','Sazional'])->default('Sazional');
            $table->text('slider_observacao')->nullable();

            // Domínio
            $table->string('domino_nome',128);
            $table->enum('dominio_registrado',['Sim','Não'])->default('Sim');
            $table->enum('dominio_criacao_migracao',['Migração','Criação','Criação com E-mail','MX Externo','Hospedagem Externa','Apontamento WWW'])->default('Criação');
            $table->enum('dominio_momento_execucao',['Imediatamente','Após Lançamento'])->default('Imediatamente');
            $table->enum('dominio_email',['Sim','Não'])->default('Não');
            $table->text('dominio_observacao')->nullable();

            // Redirects
            $table->enum('redirects_havera',['Sim','Não'])->default('Não');
            $table->text('redirects_quais')->nullable();
            $table->text('redirects_observacao')->nullable();

            // Marketing
            $table->string('marketing_data_inicio');
            $table->string('marketing_investimento');
            $table->string('marketing_quantidade');

            // Datas
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
        Schema::dropIfExists('tb_ficha_comercial_projetos');
    }
}
