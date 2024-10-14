<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_eventos', function (Blueprint $table) {
            $table->increments('id');

            // Nome do Evento Criado
            $table->string('nome');

            // Adicionando uma descricão
            $table->text('descricao')->nullable();

            // Adicionando data de início
            $table->date('evento_data_inicio');

            // Adicionando data do fim
            $table->date('evento_data_fim');

            // Verificando qual usuário que concluiu o gatilho
            $table->integer('id_usuario');

            // Adicinando id do setor para verificar do calendários de todos
            $table->integer('id_setor');

            // Colocando creat e update padrão do laravel
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
        Schema::dropIfExists('tb_eventos');
    }
}
